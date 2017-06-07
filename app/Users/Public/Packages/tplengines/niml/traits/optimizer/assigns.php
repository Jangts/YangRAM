<?php
/*
 * NIML Compiler
 */
trait NIML_traits_optimizer_assigns {
	private function assign_transer($segment, $theme, $indent){
		if(isset($segment['name'])&&isset($segment['body'])&&is_array($segment['body'])){
			if($segment['body']['type']==='ExpressionGroup'){
				$this->walk($segment['body'], $theme, $indent + 1);
			}else{
				$this->ast['body'][] = array(
					'type'	=>	'Expression',
					'indent'=>	$indent,
					'value'	=>	$segment['name'] . '=' . self::assign_transer_printer($segment['body']) . ';'
				);
			}
		}else{
			exit('NIML_COMPILATION_ERROR: NUKNOW ASSIGN EXPRESSION');
		}
	}

	public static function assign_transer_printer($var){
		switch($var['type']){
			case 'Number':
			case 'Variable':
			case 'Const':
			return $var['value'];

			case 'Bool':
			return $var['value'] ? 'true' : 'false';

			case 'String':
			return '\'' . str_replace("'", "\'", sprintf('%s', $var['value'])) . '\'';
			
			case 'Null':
			return 'NULL';
			
			case 'ArrayElement':
			case 'StaticArrayElement':
			return self::expression_agreement_generator($var);
			
			case 'Array':
			return self::array_expression_generator($var);
			
			case 'CallExpression':
			return self::call_expression_generator($var);
		}
	}
}
