<?php
/*
 * NIML Compiler
 */
trait NIML_traits_helpers_translator {
	protected function transformer($value){
		if(preg_match('/^\d+$/', $value)){
			return $value;
		}elseif(preg_match('/^(\'|")/', $value)){
			return $value;
		}elseif(preg_match('/^([\$\w\.]+)$/', $value)){
			return $value;
		}else{
			$vars = explode('.', $value);
			foreach ($vars as $n => $val) {
				$vars[$n] = $this->array_transformer($val);
			}
			return join('.', $vars);
		}
	}

	protected function array_transformer($val){
		$vars = explode('/', $val);
		foreach ($vars as $i => $v) {
			if($i===0){
				$index = $v;
			}else{
				$index .= '[' . $v . ']';
			}
		}
		return $index;
	}
}
