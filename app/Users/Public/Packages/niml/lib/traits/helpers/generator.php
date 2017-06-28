<?php
/*
 * NIML Compiler
 */
trait NIML_traits_helpers_generator {
	private static function array_expression_generator($body){
		$expression ='array(';
		foreach ($body['eles'] as $n => $el) {
			if($n===0){
				$expression .= self::expression_agreement_generator($el);
			}else{
				$expression .= ',';
				$expression .= self::expression_agreement_generator($el);
			}
		}
		$expression .= ')';
		return $expression;
	}

	private static function call_expression_generator($body){
		$expression = self::callname_transformer($body['name']) . '(';
		foreach ($body['args'] as $n => $arg) {
			if($n===0){
				$expression .= self::expression_agreement_generator($arg);
			}else{
				$expression .= ',';
				$expression .= self::expression_agreement_generator($arg);
			}
		}
		$expression .= ')';
		return $expression;
	}

	private static function expression_agreement_generator($arg){
		switch ($arg['type']) {
			case 'String':
			return '\'' . str_replace("'", "\'", sprintf('%s', $arg['value'])) . '\'';
			
			case 'Number':
			return sprintf('%d', $arg['value']);
			
			case 'Bool':
			return $arg['value'] ? 'true' : 'false';
			
			case 'Variable':
			return self::variable_transformer($arg['value']);
			
			case 'Const':
			case 'StaticMember':
			return str_replace('.', '\\', $arg['namespace']) . str_replace('.', '::', $arg['value']);
			
			case 'ArrayElement':
			return str_replace('.', '->', $arg['value']) . '[' . $arg['index'] . ']';

			case 'StaticArrayElement':
			return str_replace('.', '\\', $arg['namespace']) . str_replace('.', '->', $arg['value']) . '[' . $arg['index'] . ']';
			
			case 'Null':
			return 'NULL';
			
			case 'Array':
			return self::array_expression_generator($arg['value']);
			
			case 'CallExpression':
			return self::call_expression_generator($arg);

			case 'MathExpression':
			return $arg['left'].$arg['opchar'].$arg['right'];
		}
	}

	private static function variable_transformer($var){
		return str_replace('.', '->', $var);
	}

	private static function callname_transformer($name){
		$name = preg_replace('/[\s\t]+/', '', $name);
		$preg = '/^(([\w\.\\\]*[\.\\\])?(\w+(\.|\:+))){0,1}((\$\w+\.)*)(\w+)$/';	
		if(preg_match($preg, $name, $matches)){
			$namespace = str_replace('.', '\\', $matches[2]);
			$class = preg_replace('/(\.|\:+)/', '::', $matches[3]);
			$obj = str_replace('.', '->', $matches[5]);
			$fn = $matches[7];
			if($namespace||$class){
				if(strtolower($class)=='self::'){
					return $class . $obj . $fn;
				}
				return '\\' . $namespace . $class . $obj . $fn;
			}
			return $obj . $fn;
		}
		//var_dump($name);
		exit('NIML_COMPILATION_ERROR: ERROR CALLNAME');
	}
}
