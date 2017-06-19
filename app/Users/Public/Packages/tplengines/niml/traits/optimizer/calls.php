<?php
/*
 * NIML Compiler
 */
trait NIML_traits_optimizer_calls {
	protected function call_transer($segment, $theme, $indent){
		if(isset($segment['name'])){
			switch($segment['name']){
				case 'display':
				$this->call_display($segment, $theme, $indent);
				break;
				case 'gettext':
				$this->call_gettext($segment, $theme, $indent);
				break;
				default:
				$this->call_default($segment, $theme, $indent);
			}
		}else{
			exit('NIML_COMPILATION_ERROR: NUKNOW CALL EXPRESSION');
		}
	}

	protected function call_display($segment, $theme, $indent){
		if(
			isset($segment['args'])
			&&is_array($segment['args'])
			&&isset($segment['args'][0])
			&&isset($segment['args'][0]['type'])
			&&$segment['args'][0]['type']==='String'
		){
			$this->ast['body'][] = array(
				'type'	=>	'Expression',
				'indent'=>	$indent,
				'value'	=>	'$this->including(\'' . $segment['args'][0]['value'] . '\');'
			);
		}else{
			exit('NIML_COMPILATION_ERROR: CANNOT FIND DISPLAY ARGUMENTS');
		}
	}

	protected function call_gettext($segment, $theme, $indent){
		if(
			isset($segment['args'])
			&&is_array($segment['args'])
			&&isset($segment['args'][0])
			&&isset($segment['args'][0]['type'])
			&&$segment['args'][0]['type']==='String'
		){
			$this->ast['body'][] = array(
				'type'	=>	'Expression',
				'indent'=>	$indent,
				'value'	=>	'$this->gettext(\'' . $segment['args'][0]['value'] . '\');'
			);
		}else{
			exit('NIML_COMPILATION_ERROR: CANNOT FIND DICT ARGUMENTS');
		}
	}

	protected function call_default($segment, $theme, $indent){
		if(isset($segment['args'])){
			$this->ast['body'][] = array(
				'type'	=>	'Expression',
				'indent'=>	$indent,
				'value'	=>	$this->call_expression_generator($segment) . ';'
			);
		}else{
			exit('NIML_COMPILATION_ERROR: CANNOT FIND CALL ARGUMENTS');
		}
	}

	protected function call_custom($segment, $theme){
		//$this->ast['body'][] = '';
	}
}
