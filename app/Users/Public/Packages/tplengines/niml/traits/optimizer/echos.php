<?php
/*
 * NIML Compiler
 */
trait NIML_traits_optimizer_echos {
	protected function echo_transer($segment, $theme, $indent){
		if(isset($segment['body'])&&is_array($segment['body'])){
			foreach ($segment['body'] as $row) {
				if(isset($row['type'])){
					switch($row['type']){
						case 'String':
						$this->ast['body'][] = array(
							'type'	=>	'EchoString',
							'indent'=>	$indent,
							'value'	=>	str_replace("'", "\'", sprintf('%s', $row['value']))
						);
						break;

						case 'Variable':
						case 'Const':
						case 'StaticMember':
						case 'ArrayElement':
						case 'StaticArrayElement':
						$this->ast['body'][] = array(
							'type'	=>	'EchoVariable',
							'indent'=>	$indent,
							'value'	=>	self::expression_agreement_generator($row)
						);
						break;

						case 'CallExpression':
						$this->ast['body'][] = array(
							'type'	=>	'EchoVariable',
							'indent'=>	$indent,
							'value'	=>	self::call_expression_generator($row)
						);
						break;
					}
				}
			}
		}else{
			var_dump($segment['body']);
			exit('NIML_COMPILATION_ERROR: CANNOT FIND SEGMENT BODY');
		}
	}
}
