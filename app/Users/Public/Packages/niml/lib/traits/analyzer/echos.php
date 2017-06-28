<?php
/*
 * NIML Compiler
 */
trait NIML_traits_analyzer_echos {
	private function write(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$attrs = $this->attribute($this->tokens[$this->current++]['value']);
			$origin = $this->get_type($attrs['value']);
			if(in_array($origin['type'], array('Variable', 'ArrayElement',  'Const', 'StaticMember', 'StaticArrayElement'))){
				$body  =  array($origin);
			}else{
				$body  =  [];
			}
			return array(
				'type'  =>  'EchoExpression',
				'name'  =>  'echo',
				'body'  =>  $body
			);
		}
		exit('NIML_COMPILATION_ERROR: ERROR ECHO TAG');
	}

	private function write_str(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$attrs = $this->attribute($this->tokens[$this->current++]['value']);
			$string = $this->get_type($attrs['value']);
			if(in_array($string['type'], array('Variable', 'ArrayElement',  'Const', 'StaticMember', 'StaticArrayElement'))){
				if(isset($attrs['use-ellipsis'])){
					if($attrs['use-ellipsis']==='use-ellipsis'){
						$ellipsis = '...';
					}else{
						$ellipsis = $attrs['use-ellipsis'];
					}
				}else{
					$ellipsis = '';
				}
				return array(
					'type'  =>  'EchoExpression',
					'name'  =>  'echo',
					'body'  =>  array(
						array(
							'type'  =>  'CallExpression',
							'name'  =>  'mb_substr',
							'args'  =>  array(
								$string,
								array(
									'type'  =>  'Number',
									'value' =>  isset($attrs['start']) ? $attrs['start'] : 0
								),
								array(
									'type'  =>  'Number',
									'value' =>  $attrs['length']
								)
							)
						),
						array(
							'type'  =>  'String',
							'value' =>  $ellipsis
						)
					)
				);
			}
		}
		exit('NIML_COMPILATION_ERROR: ERROR STR TAG');
	}

	private function w(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$strings = preg_split('/\s+/', $this->tokens[$this->current++]['value']);
			$body = [];
			foreach ($strings as $string) {
				$body[] = $this->get_type($string);
				//var_dump($this->get_type($string));
			}
			return array(
				'type'  =>  'EchoExpression',
				'name'  =>  'echo',
				'body'  =>  $body
			);
		}
		exit('NIML_COMPILATION_ERROR: ERROR W TAG');
	}

	private function html_decode(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$attrs = $this->attribute($this->tokens[$this->current++]['value']);
			$encoded = $this->get_type($attrs['value']);
			if(in_array($encoded['type'], array('Variable', 'ArrayElement',  'Const', 'StaticMember', 'StaticArrayElement'))){
				return array(
					'type'  =>  'EchoExpression',
					'name'  =>  'echo',
					'body'  =>  array(
						array(
							'type'  =>  'CallExpression',
							'name'  =>  'htmlspecialchars_decode',
							'args'  =>  array($encoded)
						)
					)
				);
			}
		}
		exit('NIML_COMPILATION_ERROR: ERROR DECODE TAG');
	}

	private function base64_encode(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$attrs = $this->attribute($this->tokens[$this->current++]['value']);
			$encoded = $this->get_type($attrs['value']);
			if(in_array($encoded['type'], array('Variable', 'ArrayElement',  'Const', 'StaticMember', 'StaticArrayElement'))){
				return array(
					'type'  =>  'EchoExpression',
					'name'  =>  'echo',
					'body'  =>  array(
						array(
							'type'  =>  'CallExpression',
							'name'  =>  'base64_encode',
							'args'  =>  array($encoded)
						)
					)
				);
			}
		}
		exit('NIML_COMPILATION_ERROR: ERROR DECODE TAG');
	}

	private function time_format(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$attrs = $this->attribute($this->tokens[$this->current++]['value']);
			if(is_string($attrs['format'])){
				if(isset($attrs['value'])&&($time = $this->get_type($attrs['value']))&&in_array($time['type'], array('Variable', 'ArrayElement',  'Const', 'StaticMember', 'StaticArrayElement'))){
					$args = array(
						array(
							'type'  =>  'String',
							'value' =>  $attrs['format']
						),
						array(
							'type'  =>  'CallExpression',
							'name'  =>  'strtotime',
							'args'  => 	array($time)
						)
					);
				}else{
					$args = array(
						array(
							'type'  =>  'String',
							'value' =>  $attrs['format']
						)
					);
				}
				return array(
					'type'  =>  'EchoExpression',
					'name'  =>  'echo',
					'body'  =>  array(
						array(
							'type'  =>  'CallExpression',
							'name'  =>  'date',
							'args'  => 	$args
						)
					)
				);
			}
		}
		exit('NIML_COMPILATION_ERROR: ERROR TIME TAG');
	}
}
