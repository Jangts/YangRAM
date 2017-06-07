<?php
/*
 * NIML Compiler
 */
trait NIML_traits_analyzer_branches {
	private function branch_if(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$branches = [
				'type'  =>  'BranchExpression',
				'name'  =>  'if',
				'branches'  =>  []
			];
			$branch = [
				'type'  =>  'If',
				'condition'  =>  $this->bool_condition($this->tokens[$this->current++]['value']),
				'body'  =>  []
			];
			//$branches['branches'][] = $branch;
			$length = count($this->tokens);
			while ($this->current < $length) {
				if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current]['value']==='/if'){
					$branches['branches'][] = $branch;
					$this->current++;
					break;
				}
				elseif(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current]['value']==='else'){
					$branches['branches'][] = $branch;
					$this->current++;
					if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
						$branch = [
							'type'  =>  'ElseIf',
							'condition'  =>  $this->bool_condition($this->tokens[$this->current++]['value']),
							'body'  =>  []
						];
					}else{
						$branch = [
							'type'  =>  'Else',
							'body'  =>  []
						];
					}
					continue;
				}
				$branch['body'][] = $this->walk();
			}
			return $branches;
		}
		exit('NIML_COMPILATION_ERROR');
	}

	private function branch_has(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$attrs = $this->attribute($this->tokens[$this->current++]['value']);
			if(isset($attrs['value'])&&(isset($attrs['list'])||isset($attrs['array']))){
				return [
					'type'  =>  'BranchExpression',
					'name'  =>  'if',
					'branches'  =>  [
						[
							'type'  =>  'If',
							'condition'  =>  [$this->branch_has_condition($attrs)],
							'body'  =>  $this->branch_has_body()
						]
					]
				];
			}
		}
		exit('NIML_COMPILATION_ERROR');
	}

	private function branch_has_condition($attrs){
		$var = NIML_Generator::assign_transer_printer($this->get_type($attrs['value']));
		if(isset($attrs['list'])){
			$array = '['.$attrs['list'].']';
		}else{
			$array = NIML_Generator::assign_transer_printer($this->get_type($attrs['array']));
		}
		return [
			'type'	=>	'ConditionSegment',
			'outleft'	=>	'',
			'inner'	=>	[
				'left'		=>	[
					'type'  =>  'Expression',
					'value'  =>  "in_array($var, $array)"
				],
				'symbol'	=>	'!=',
				'right'		=>	[
	                'type'  =>  'Bool',
	                'value' =>  false
				]
			],
			'outright'	=>	''
		];
	}

	private function branch_has_body(){
		$length = count($this->tokens);
		$body = [];
		while ($this->current < $length) {
			if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current]['value']==='/has'){
				$this->current++;
				break;
			}
			$body[] = $this->walk();
		}
		return $body;
	}

	private function branch_switch() {
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$attrs = $this->attribute($this->tokens[$this->current++]['value']);
			$branches = [
				'type'  	=>  'BranchExpression',
				'name'  	=>  'switch',
				'identity'	=>	$attrs['name'],
				'branches'  =>  []
			];
			$default = [
				'type'  =>  'Default',
				'body'  =>  []
			];
			$branch = $default;
			$length = count($this->tokens);
			while ($this->current < $length) {
				if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current]['value']==='/switch'){
					$branches['branches'][] = $branch;
					$this->current++;
					break;
				}
				elseif(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current]['value']==='case'){
					if($branch['type']==='Case'){
						$branches['branches'][] = $branch;
					}elseif($branch['type']==='Default'){
						$default = $branch;
					}
					$this->current++;
					if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
						$attrs = $this->attribute($this->tokens[$this->current++]['value']);
						$branch = [
							'type'  =>  'Case',
							'condition'  =>  $attrs['value'],
							'body'  =>  []
						];
						continue;
					}else{
						exit('NIML_COMPILATION_ERROR');
					}
				}
				elseif(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current]['value']==='/case'){
					if($branch['type']==='Case'){
						$branch['body'][] = [
							'type'  	=>  'GoExpression',
							'name'  	=>  'break',
						];
						$branches['branches'][] = $branch;
					}
					$branch = $default;
					$this->current++;
					continue;
				}
				$branch['body'][] = $this->walk();
			}
			return $branches;
		}else{
			exit('NIML_COMPILATION_ERROR');
		}
	}

	private function bool_condition($string){
		$conditions = [];
		$array = preg_split('/(&&|\|\|)/', $string);
		foreach($array as $str){
			$conditions[] = $this->bool_condition_single(trim($str));
			$string = substr($string, strlen($str));
            if(strpos($string, '&&')===0){
                $conditions[] = [
                    'type'	=>	'JointMark',
                    'value'	=>	'&&'
                ];
                $string = substr($string, 2);
            }elseif(strpos($string, '||')===0){
                $conditions[] = [
                    'type'	=>	'JointMark',
                    'value'	=>	'||'
                ];
				$string = substr($string, 2);
            }
		}
		return $conditions;
	}

	private function bool_condition_single($string){
		if(preg_match('/^(\!\(*|\(+){0,1}\s*(.+?)\s*(\)+){0,1}$/', $string, $matches)){
			$array = preg_split('/(=|\s+(=|eq|is|ne|not|ge|gt|le|lt)\s+)/', $matches[2]);			
			if(count($array)===2){
				$symbol = $this->bool_condition_single_symbol($matches[2], $array);
				$inner = [
					'left'		=>	$this->get_type(trim($array[0])),
					'symbol'	=>	$symbol,
					'right'		=>	$this->get_type(trim($array[1]))
				];
			}elseif(count($array)===1){
				$inner = [
					'left'		=>	$this->get_type(trim($array[0])),
					'symbol'	=>	'==',
					'right'		=>	[
	                	'type'  =>  'Bool',
	                	'value' =>  true
					]
				];
			}else{
				exit('NIML_COMPILATION_ERROR: BOOLEN CONDITION GIVEN ERROR');
			}
			return [
				'type'	=>	'ConditionSegment',
				'outleft'	=>	$matches[1],
				'inner'	=>	$inner,
				'outright'	=>	isset($matches[3]) ? $matches[3] : ''
			];
		}
		exit('NIML_COMPILATION_ERROR: CONDITION GIVEN ERROR');
	}

	private function bool_condition_single_symbol($string, $array){
		foreach($array as $str){
			$string = str_replace($str, '', $string);
		}
		return trim($string);
	}
}
