<?php
/*
 * NIML Compiler
 */
abstract class NIML_SyntacticAnalyzer {
	private
	$opened = false,
	$strings = [],
	$strings_L = [],
	$strings_R = [];

	public $LAR, $TKS;

	private function escape($input){
		$input = str_replace('\"', '@__quote__;', $input);
		$inputs = explode('"', $input);
		$length = count($inputs);
		$string = $inputs[0];
		for ($i = 1; $i < $length; $i++) {
			if($i%2){
				if($length-$i>1){
					$string .= '@__string__'.count($this->strings).';';
					$this->strings[] = $inputs[$i];
				}else{
					die('Error Escape "'.$input.'" ['.$i.'/'.$length.']');
				}
			}else{
				$string .= $inputs[$i];
			}
		}
		return $string;
	}

	private function string_escape($input){
		$input = str_replace('\"', '@__quote__;', $input);
		$inputs = explode('"', $input);
		$length = count($inputs);
		if($length>1){
			if($this->opened){
			//if($length>1){
				$string = '@__string__right__'.count($this->strings_R).';';
				$this->strings_R[] = $inputs[0];
				$this->opened = false;
				for ($i = 1; $i < $length; $i++) {
					if($i%2){
						$string .= $inputs[$i];
					}else{
						if($length-$i>1){
							$string .= '@__string__'.count($this->strings).';';
							$this->strings[] = $inputs[$i];
						}else{
							$string .= '@__string__left__'.count($this->strings_L).';';
							$this->strings_L[] = $inputs[$i];
							$this->opened = true;
						}
					}
				}
			}else{
				$string = $inputs[0];
				for ($i = 1; $i < $length; $i++) {
					if($i%2){
						if($length-$i>1){
							$string .= '@__string__'.count($this->strings).';';
							$this->strings[] = $inputs[$i];
						}else{
							$string .= '@__string__left__'.count($this->strings_L).';';
							$this->strings_L[] = $inputs[$i];
							$this->opened = true;
						}
					}else{
						$string .= $inputs[$i];
					}
				}
			
			}
		
			//var_dump($string);
			$string = preg_replace('/\/\*[\s\S]*?\*\//', '', $string);
			$string = preg_replace('/\/\/.*/', '', $string);
			//var_dump($string);
			return $string;
		}else{
			return $input;
			//$string = '@__string__'.count($this->strings).';';
			//$this->strings[] = $inputs[0];
		}
	}

	public function tokenizer(){
		$_tokens = [];
		$segments = explode('<ni', preg_replace_callback('/<ni:\/{0,1}w+/i', function ($matches) {
            return strtolower($matches[0]);
        }, $this->input));
		foreach ($segments as $segment) {
			$segment = preg_replace('/>[\r\n\s]+/', '>', $segment);
			$segment = preg_replace('/\s*\/\s*>\s*/', '>', $segment);
			$segment = preg_replace('/<!--.*?-->/', '', $segment);
			$segment = preg_replace('/\/\s*>/', '\>', $segment);
			if(substr($segment, 0, 1 )===':'){
				//$segment = $this->escape($segment);
				if(preg_match('/^:([a-zA-Z0-9]+)(\s+([^>]+))?>([\s\S]*)$/', $segment, $matches)){
					$_tokens[] = array(
						'type'	=>	'operator',
						'value'	=>	$matches[1]
					);

					if($matches[2]){
						$_tokens[] = array(
							'type'	=>	'parameters',
							'value'	=>	$this->escape($matches[3])
						);
					}

					$ma4 = explode('</ni', $matches[4]);
					if(count($ma4)>1){
						$_tokens[] = array(
							'type'	=>	'strings',
							'value'	=>	$this->string_escape($ma4[0])
						);
						$l = count($ma4);
						for($i = 1; $i < $l; $i++){
							if(preg_match('/^:([a-zA-Z0-9]+)\s*>([\s\S]*)$/', $ma4[$i], $ma4i)){
								$_tokens[] = array(
									'type'	=>	'operator',
									'value'	=>	'/'.$ma4i[1]
								);
								$_tokens[] = array(
									'type'	=>	'strings',
									'value'	=>	$this->string_escape($ma4i[2])
								);
							}
						}
					}else{
						$_tokens[] = array(
							'type'	=>	'strings',
							'value'	=>	$this->string_escape($matches[4])
						);
					}
				}
				continue;
			}
			$_tokens[] = array(
				'type'	=>	'strings',
				'value'	=>	$this->string_escape($segment)
			);
		}
		$tokens = [];
		foreach ($_tokens as $token) {
			if($token['type']==='strings'&&$token['value']===''){
				continue;
			}
			$tokens[] = $token;
		}
		//var_dump($this->strings, $this->strings_L, $this->strings_R);
		//var_dump($this->strings, 'left', $this->strings_L, 'right', $this->strings_R, $tokens);
		//exit;
		$this->TKS = $tokens;
		$this->LAR = new NIML_LexicalAnalyzer($this, $this->strings, $this->strings_L, $this->strings_R);
	}
}