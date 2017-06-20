<?php
/*
 * NIML Compiler
 */
trait NIML_traits_analyzer_calls {
	private function display(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$args = $this->attribute($this->tokens[$this->current++]['value']);
			if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current]['value']==='/include'){
				$this->current++;
			}

			if(empty($args['src'])||$this->string($args['src'])===''){
				return [
					'type'  =>  'EchoExpression',
					'name'  =>  'echo',
					'body'  =>  [
						'type'  =>  'String',
						'value' =>  ''
					]
				];
			}
			return [
				'type'  =>  'CallExpression',
				'name'  =>  'display',
				'args'  =>  [
					[
						'type'  =>  'String',
						'value' =>  $args['src']
					]
				]
			];
		}
		exit('NIML_COMPILATION_ERROR: ERROR INCLUDE TAG');
	}

	private function gettext(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='strings'){
			$value = $this->tokens[$this->current++]['value'];
			// var_dump($value, $this->strings);
			// die;
			return [
				'type'  =>  'CallExpression',
				'name'  =>  'gettext',
				'args'  =>  [
					[
						'type'  =>  'String',
						'value' =>  $this->unescape($value)
					]
				]
			];
		}
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current++]['value']==='/dict'){
			return [
				'type'  =>  'EchoExpression',
				'name'  =>  'echo',
				'body'  =>  [
					'type'  =>  'String',
					'value' =>  ''
				]
			];
		}
		exit('NIML_COMPILATION_ERROR: ERROR DICT TAG');
	}

	private function call(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
			$attrs = $this->attribute($this->tokens[$this->current++]['value']);
			if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current]['value']==='/call'){
				$this->current++;
			}
			return [
				'type'  =>  'CallExpression',
				'name'  =>  $attrs['name'],
				'args'  =>  $this->arguments($attrs['args'])
			];
		}
		exit('NIML_COMPILATION_ERROR: ERROR CALL TAG');
	}
}
