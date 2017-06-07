<?php
/*
 * NIML Compiler
 */
trait NIML_traits_analyzer_assigns {
	private function assign_var(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$attrs = $this->attribute($this->tokens[$this->current++]['value']);
			if(isset($attrs['name'])&&preg_match('/^\$\w[\$\w\[\`\]]+$/', $attrs['name'])){
				if(isset($attrs['type'])){
					$_type = strtolower($attrs['type']);
					switch($_type){
						case 'string':
						case 'number':
						case 'bool':
						case 'null':
						$type = ucfirst($_type);
						break;
						case 'ae':
						case 'var':
						case 'const':
						$type = 'Unknow';
						break;
						default:
						$type = 'String';
					}
				}else{
					$type = 'Unknow';
				}
				if(isset($attrs['value'])){
					$value = $attrs['value'];
					if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='strings'&&$this->tokens[$this->current+1]['type']==='operator'&&$this->tokens[$this->current+1]['value']==='/var'){
						$this->current += 2;
					}
				}else{
					if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='strings'&&$this->tokens[$this->current+1]['type']==='operator'&&$this->tokens[$this->current+1]['value']==='/var'){
						$value = $this->tokens[$this->current]['value'];
						$this->current += 2;
					}else{
						$values = array(
							'String'	=>	'',
							'Unknow'	=>	'',
							'Number'	=>	0,
							'Bool'		=>	false,
							'Null'		=>	NULL
						);
						if($type==='Unknow'){
							$type = 'String';
						}
						$value = $values[$type];
					}
				}
				switch($type){
					case 'Number':
					if(!is_numeric($value)){
						exit('NIML_COMPILATION_ERROR: VAR VALUE MUST BE A NUMBER');
					}
					break;
					case 'Bool':
					if($value=='false'||$value==0||$value==''){
						$value = false;
					}else{
						$value = true;
					}
					break;
					case 'Null':
					$value = NULL;
					break;
					case 'Unknow':
					$var = $this->get_type($value);
					if($var['type']==='ArrayElement'||$var['type']==='StaticArrayElement'){
						return array(
							'type'  =>  'AssignExpression',
							'name'  =>  $attrs['name'],
							'body'  =>  $var
						);
					}
					$type = $var['type'];
					$value = $var['value'];
				}
				return array(
					'type'  =>  'AssignExpression',
					'name'  =>  $attrs['name'],
					'body'  =>  array(
						'type'  =>  $type,
						'value'  =>  $value
					)
				);
			}
			exit('NIML_COMPILATION_ERROR: ERROR VAR TAG PARAMETERS');
		}
		exit('NIML_COMPILATION_ERROR: ERROR VAR TAG');
	}

	private function assign_let(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$body = [];
			$string = preg_replace('/\s*(=|,|\(|\))\s*/', '\1', trim($this->tokens[$this->current++]['value']));

	        $array = preg_split('/\s+/', $string);
	        foreach ($array as $str) {
	            $param = explode('=', $str);
	            if(isset($param[1])&&preg_match('/^\$\w[\$\w\[\`\]]+$/', $param[0])){
					$body[] = array(
						'type'  =>  'AssignExpression',
						'name'  =>  $param[0],
						'body'  => $this->get_type($param[1])
					);
				}
	        }
			if(count($body)>1){
				return array(
					'type'  =>  'ExpressionGroup',
					'body'  =>	$body
				);
			}elseif(count($body)>0){
				return $body[0];
			}
			return array(
				'type'  =>  'Unknow',
				'value' =>  $string
			);
		}
		exit('NIML_COMPILATION_ERROR: ERROR VAR LET PARAMETERS');
	}

	private function assign_array_json(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$attrs = $this->attribute($this->tokens[$this->current++]['value']);
			if(isset($attrs['name'])&&preg_match('/^\$\w[\$\w\[\`\]]+$/', $attrs['name'])){
				if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='strings'){
					$json = $this->unescape(preg_replace('/[\r\n\t\s]+/', '', $this->tokens[$this->current++]['value']));
					if($array = json_decode($json, true)){
						if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current++]['value']==='/json'){
							return array(
								'type'  =>  'AssignExpression',
								'name'  =>  $attrs['name'],
								'body'  =>  array(
									'type'  =>  'CallExpression',
									'name'  =>  'json_decode',
									'args'  =>  array(
										array(
											'type'	=>	'String',
											'value'	=>	$json
										),
										array(
											'type'	=>	'Bool',
											'value'	=>	true
										)
									)
								)
							);
						}
						exit('NIML_COMPILATION_ERROR: JSON TAG MUST BE CLOSED');
					}
					exit('NIML_COMPILATION_ERROR: ERROR JSON TAG VALUE');
				}
				exit('NIML_COMPILATION_ERROR: ERROR JSON TAG NAME');
			}
			exit('NIML_COMPILATION_ERROR: ERROR JSON TAG PARAMETERS');
		}
		exit('NIML_COMPILATION_ERROR: ERROR JSON TAG');
	}

	private function assign_array_xml(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$attrs = $this->attribute($this->tokens[$this->current++]['value']);
			if(isset($attrs['name'])&&preg_match('/^\$\w[\$\w\[\`\]]+$/', $attrs['name'])){
				$name = $attrs['name'];
				if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='strings'){
					$string = preg_replace('/[\r\n]+/', '', $this->tokens[$this->current++]['value']);
					if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current++]['value']==='/xl'){
						$eles = [];
						if(preg_match_all('/<li\s+([^>]+)\s*(\/\s*)?>/', $string, $matches)){
							$xl = $matches[1];
							foreach ($xl as $param) {
								$attrs = $this->attribute($param);
								if(isset($attrs['type'])&&in_array(strtolower($attrs['type']), array('string', 'number', 'bool', 'null'))){
									$type = ucfirst(strtolower($attrs['type']));
								}else{
									$type = 'String';
								}
								if(isset($attrs['value'])){
									$value = $attrs['value'];
								}else{
									$values = array(
										'String'	=>	'',
										'Number'	=>	0,
										'Bool'		=>	false,
										'Null'		=>	NULL
									);
									$value = $values[$type];
								}
								$eles[] = array(
									'type'	=>	$type,
									'value'	=>	$value
								);
							}
						}
						return array(
							'type'  =>  'AssignExpression',
							'name'  =>  $name,
							'body'  =>  array(
								'type'  =>  'Array',
								'eles' =>  $eles
							)
						);
					}
					exit('NIML_COMPILATION_ERROR: ERROR LI TAG');
				}
				exit('NIML_COMPILATION_ERROR: ERROR XL TAG VALUE');
			}
			exit('NIML_COMPILATION_ERROR: ERROR XL TAG NAME');
		}
		exit('NIML_COMPILATION_ERROR: ERROR XL TAG');
	}

	private function assign_def(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$attrs = $this->attribute($this->tokens[$this->current++]['value']);
			return array(
				'type'  =>  'AssignExpression',
				'name'  =>  $attrs['name'],
				'body'  =>  array(
					'type'  =>  'CallExpression',
					'name'  =>  $attrs['onload'],
					'args'  =>  $this->arguments($attrs['args'])
				)
			);
		}
		exit('NIML_COMPILATION_ERROR: ERROR DEF TAG');
	}
}
