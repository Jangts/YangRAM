<?php
/*
 * NIML Compiler
 */
trait NIML_traits_optimizer_loops {
	private function loop_transer($segment, $theme, $indent){
		if(isset($segment['name'])){
			switch($segment['name']){
				case 'foreach':
				$this->each_transer($segment, $theme, $indent);
				break;
				case 'for':
				$this->for_transer($segment, $theme, $indent);
				break;
				case 'while':
				$this->while_transer($segment, $theme, $indent);
				break;
			}
		}else{
			exit('NIML_COMPILATION_ERROR: NUKNOW LOOP EXPRESSION');
		}
	}

	private function openloop_transer($segment, $theme, $indent){
		if(isset($segment['index'])&&is_string($segment['index'])){
			$this->ast['body'][] = array(
				'type'	=>	'Expression',
				'indent'=>	$indent,
				'value'	=>	'for(' . $segment['index'] . '=' . $segment['start'] . ';' . $segment['index'] . ($segment['index'] ? '<' : '<=') . $segment['end'] .';' . $segment['index'] . '++){'
			);
		}else{
			exit('NIML_COMPILATION_ERROR: CANNOT FIND LOOP INDEX');
		}
		if(isset($segment['body'])&&is_array($segment['body'])){
			$this->walk($segment['body'], $theme, $indent + 1);
		}
		$this->ast['body'][] = array(
			'type'	=>	'CloseBrace',
			'indent'=>	$indent,
			'value'	=>	'}'
		);
	}

	private function each_transer($segment, $theme, $indent){
		switch($segment['array_expression']['type']){
			case 'Variable':
			$array = $segment['array_expression']['value'];
			break;
			case 'Array':
			$array = '$'.str_replace('.', '_', uniqid('array_', true));
			$this->ast['body'][] = array(
				'type'	=>	'Expression',
				'indent'=>	$indent,
				'value'	=>	$array . '=' . self::array_expression_generator($segment['array_expression'])
			);
			break;
			default:
			exit('NIML_COMPILATION_ERROR: CANNOT FIND ARRAY ELEMENT TYPE');
		}
		$this->ast['body'][] = array(
			'type'	=>	'EachStatement',
			'indent'=>	$indent,
			'array'	=>	$array,
			'key'	=>	$segment['index'],
			'value'	=>	$segment['item'],
		);
		$this->ast['body'][] = array(
            'type'	=>	'CloseBracketWithOpenBrace',
            'indent'=>	0,
            'value'	=>	'){'
        );
		if(isset($segment['body'])&&is_array($segment['body'])){
			$this->walk($segment['body'], $theme, $indent + 1);
		}
		$this->ast['body'][] = array(
			'type'	=>	'CloseBrace',
			'indent'=>	$indent,
			'value'	=>	'}'
		);
	}

	private function for_transer($segment, $theme, $indent){
		$this->ast['body'][] = array(
			'type'	=>	'ForStatement',
			'indent'=>	$indent,
			'index'	=>	$segment['index'],
			'start'	=>	$segment['start'],
			'end'	=>	$segment['end'],
			'step'	=>	$segment['step']
		);
		$this->ast['body'][] = array(
            'type'	=>	'CloseBracketWithOpenBrace',
            'indent'=>	0,
            'value'	=>	'){'
        );
		if(isset($segment['body'])&&is_array($segment['body'])){
			$this->walk($segment['body'], $theme, $indent + 1);
		}
		$this->ast['body'][] = array(
			'type'	=>	'CloseBrace',
			'indent'=>	$indent,
			'value'	=>	'}'
		);
	}

	private function while_transer($segment, $theme, $indent){
		$this->ast['body'][] = array(
			'type'	=>	'InLineExpression',
			'indent'=>	$indent,
			'value'	=>	'while('
		);
		$this->condition_transer($segment['condition']);
		$this->ast['body'][] = array(
			'type'	=>	'CloseBracketWithOpenBrace',
			'indent'=>	0,
			'value'	=>	'){'
		);
		if(isset($segment['body'])&&is_array($segment['body'])){
			$this->walk($segment['body'], $theme, $indent + 1);
		}
		$this->ast['body'][] = array(
			'type'	=>	'CloseBrace',
			'indent'=>	$indent,
			'value'	=>	'}'
		);
	}
}
