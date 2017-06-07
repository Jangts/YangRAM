<?php
/*
 * NIML Compiler
 */
trait NIML_traits_analyzer_loops {
	private function loop_each(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$attrs = $this->attribute($this->tokens[$this->current++]['value']);
			$array = [];
			if(isset($attrs['list'])){
				$array[] = $attrs['list'];
				$array[] = 'as';
				if(isset($attrs['index'])){
					$array[] = $attrs['index'];
				}else{
					$array[] = '$index';
				}
				if(isset($attrs['item'])){
					$array[] = $attrs['item'];
				}else{
					$array[] = '$item';
				}
				$loop = $this->for_as($array);
				$length = count($this->tokens);
				while ($this->current < $length) {
					if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current]['value']==='/each'){
						$this->current++;
						break;
					}
					$loop['body'][] = $this->walk();
				}
				return $loop;
			}
			echo "NIML_COMPILATION_ERROR: ERROR EACH TAG PARAMETERS:\n";
			var_dump($attrs);
			exit;
		}
		exit('NIML_COMPILATION_ERROR: ERROR EACH TAG');
	}

	private function loop_for(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$loop = $this->for_options($this->tokens[$this->current++]['value']);
			$length = count($this->tokens);
			while ($this->current < $length) {
				if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current]['value']==='/for'){
					$this->current++;
					break;
				}
				$loop['body'][] = $this->walk();
			}
			return $loop;
		}
		exit('NIML_COMPILATION_ERROR: ERROR FOR TAG');
	}

	private function loop_times(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$attrs = $this->attribute($this->tokens[$this->current++]['value']);
			if(isset($attrs['times'])&&(is_numeric($attrs['times']))){
				$times = intval($attrs['times']);
				if(isset($attrs['step'])&&(is_numeric($attrs['step']))){
					$step = intval($attrs['step']);
				}else{
					$step = -1;
				}
				if($step>=0){
					$start = 0;
					$end = ($times - 1) * $step;
				}else{
					$start = (1 - $times) * $step;
					$end = 0;
				}
				$loop = array(
					'type'  =>  'LoopExpression',
					'name'  =>  'for',
					'index' =>	'$curr',
					'start'	=>	$start,
					'end'	=>	$end,
					'step'	=>	$step
				);
				$length = count($this->tokens);
				while ($this->current < $length) {
					if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current]['value']==='/loop'){
						$this->current++;
						break;
					}
					$loop['body'][] = $this->walk();
				}
				return $loop;
			}
			exit('NIML_COMPILATION_ERROR: ERROR LOOP TAG PARAMETERS');
		}
		exit('NIML_COMPILATION_ERROR: ERROR LOOP TAG');
	}

	private function loop_while(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$loop = array(
				'type'  =>  'LoopExpression',
				'name'  =>  'while',
				'condition'  =>  $this->bool_condition($this->tokens[$this->current++]['value']),
				'body'  =>  []
			);
			$length = count($this->tokens);
			while ($this->current < $length) {
				if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current]['value']==='/while'){
					$this->current++;
					break;
				}
				$loop['body'][] = $this->walk();
			}
			return $loop;
		}
		exit('NIML_COMPILATION_ERROR: ERROR WHILE TAG');
	}

	private function for_options($string){
        $array = preg_split('/\s+/', preg_replace('/\s*,\s*/', ',', trim($string)));
        if(count($array)>2){
            switch ($array[1]) {
                case 'of':
				return $this->for_of($array);

				case 'in':
				return $this->for_in($array);

				case 'as':
				return $this->for_as($array);
            }
        }
		exit('NIML_COMPILATION_ERROR: ERROR FOR TAG PARAMETERS');
    }

	private function for_of($array){
		if(preg_match('/^\$\w+$/', $array[0])){
			if(preg_match('/^\$\w[\$\w\[\`\]]+$/', $array[2])){
				return array(
					'type'  =>  'LoopExpression',
					'name'  =>  'foreach',
					'array_expression'    =>  array(
						'type'  =>  'Variable',
						'value' =>  $array[2]
					),
					'index' => '$index',
					'item' => $array[0]
				);
			}
			if(preg_match('/^\[([\s\S]+)\]$/', $array[2], $matches)){
				return array(
					'type'  =>  'LoopExpression',
					'name'  =>  'foreach',
					'array_expression'    =>  array(
						'type'  =>  'Array',
						'eles' =>  $this->get_array($matches[1])
					),
					'index' => '$index',
					'item' => $array[0]
				);
			}
		}
		exit('NIML_COMPILATION_ERROR: ERROR FOR TAG PARAMETERS [ FOR OF ]');
	}

	private function for_in($array){
		if(preg_match('/^\$\w+$/', $array[0])){
			if(preg_match('/^\$\w[\$\w\[\`\]]+$/', $array[2])){
				return array(
					'type'  =>  'LoopExpression',
					'name'  =>  'foreach',
					'array_expression'    =>  array(
						'type'  =>  'Variable',
						'value' =>  $array[2]
					),
					'index' => $array[0],
					'item' => '$item'
				);
			}
			if(preg_match('/^(\(|\[)(\d+)\s*,\s*(\d+)(\]|\))$/', $array[2], $matches)){
				if($matches[1]==='('){
					$start = intval($matches[2]) + 1;
				}else{
					$start = $matches[2];
				}
				if($matches[4]===')'){
					$end = intval($matches[3]) - 1;
				}else{
					$end = $matches[3];
				}
				if($end>=$start){
					$step = 1;
				}else{
					$step = -1;
				}
				return array(
					'type'  =>  'LoopExpression',
					'name'  =>  'for',
					'index' =>	$array[0],
					'start'	=>	intval($start),
					'end'	=>	intval($end),
					'step'	=>	intval($step)
				);
			}
			if(preg_match('/^(\[)(\d+|[\$\w\[\`\]\.\/]+)\s*,\s*(\d+|[\$\w\[\`\]\.\/]+)(\]|\))$/', $array[2], $matches)){
				$start = $matches[2];
				if($matches[4]===')'){
					$isOpen = true;
				}else{
					$isOpen = false;
				}
				$end = $matches[3];
				return array(
					'type'  		=>  'OpenLoopExpression',
					'index' 		=>	$array[0],
					'start'			=>	$start,
					'end'			=>	$end,
					'isOpen'		=>	$isOpen
				);
			}
		}
		exit('NIML_COMPILATION_ERROR: ERROR FOR TAG PARAMETERS [ FOR IN ]');
	}

	private function for_as($array){
		if(preg_match('/^\$\w[\$\w\[\`\]]+$/', $array[0])){
			if(preg_match('/^\$\w+$/', $array[2])){
				if(isset($array[3])&&preg_match('/^\$\w+$/', $array[3])){
					return array(
						'type'  =>  'LoopExpression',
						'name'  =>  'foreach',
						'array_expression'    =>  array(
							'type'  =>  'Variable',
							'value' =>  $array[0]
						),
						'index' => $array[2],
						'item' => $array[3]
					);
				}
			}
		}
		exit('NIML_COMPILATION_ERROR: ERROR FOR TAG PARAMETERS [ FOR AS ] OR ERROR EACH TAG PARAMETERS');
	}
}
