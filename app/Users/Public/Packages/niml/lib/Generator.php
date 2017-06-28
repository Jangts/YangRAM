<?php
/*
 * NIML Generator
 */
final class NIML_Generator {

	use NIML_traits_helpers_generator;

	use NIML_traits_optimizer_assigns;
	use NIML_traits_optimizer_echos;
	use NIML_traits_optimizer_loops;
	use NIML_traits_optimizer_calls;
	use NIML_traits_optimizer_branches;

	private $compiler, $midast, $ast;

	public function __construct($compiler, $midast) {
		if(is_array($midast)&&isset($midast['type'])&&($midast['type']==='MIDAST')&&isset($midast['body'])&&is_array($midast['body'])){
			$this->compiler = $compiler;
			$this->midast = $midast;
		}else{
			exit('NIML_COMPILATION_ERROR: ERROR MIDAST');
		}
	}

	public function transformer($theme) {
		$this->compiler->AST = array(
			'type' => 'AST',
			'body' => []
		);
		//var_dump($this->midast);
		$this->walk($this->midast['body'], $theme);
		$this->compiler->AST = $this->ast;
		//var_dump($this->ast);
		//exit;
	}

	private function walk($body, $theme = 'default', $indent = 0) {
		foreach ($body as $segment) {
			if(isset($segment['type'])){
				switch ($segment['type']) {
					case 'PHPCodes':
					$this->ast['body'][] = array(
			    		'type'	=>	'Expression',
						'indent'=>	0,
			    		'value'	=>	$segment['body']
		    		);
					break;

					case 'AssignExpression':
					$this->assign_transer($segment, $theme, $indent);
					break;

					case 'EchoExpression':
					$this->echo_transer($segment, $theme, $indent);
					break;

					case 'LoopExpression':
					$this->loop_transer($segment, $theme, $indent);
					break;

					case 'OpenLoopExpression':
					$this->openloop_transer($segment, $theme, $indent);
					break;

					case 'CallExpression':
					$this->call_transer($segment, $theme, $indent);
					break;

					case 'BranchExpression':
					$this->branch_transer($segment, $theme, $indent);
					break;

					case 'GoExpression':
					$this->ast['body'][] = array(
			    		'type'	=>	'Expression',
						'indent'=>	$indent,
			    		'value'	=>	'break;'
		    		);
					break;

					case 'ExpressionGroup':
					$this->walk($segment['body'], $theme, $indent + 1);
					break;

					case 'CustomCallExpression':
					var_dump($segment);
					die;
					$this->call_custom($segment, $theme, $indent);
					break;
				}
			}else{
				exit('NIML_COMPILATION_ERROR: ERROR SEGMENT');
			}
		}
	}

	public function coder() {
		$body = (array)$this->ast['body'];
		$this->codes = array(
			array(
				'type' => 'Start',
				'value'	=> ''
			)
		);
		//var_dump($body);
		foreach ($body as $n => $segment) {
			if(isset($segment['type'])){
				switch ($segment['type']) {
					case 'Expression':
					case 'CloseBracketWithOpenBrace':
					$this->codes[] = array(
						'type'	=>	'Codes',
						'value'	=>	str_repeat("\t", $segment['indent']).$segment['value'].PHP_EOL
					);
					break;
					case 'InLineExpression':
					$this->codes[] = array(
						'type'	=>	'Codes',
						'value'	=>	str_repeat("\t", $segment['indent']).$segment['value'].' '
					);
					break;
					case 'EchoString':
					if($n>0){
						$this->echo_coder($segment, $body[$n-1]);
					}else{
						$this->echo_coder($segment, false);
					}
					break;
					case 'EchoVariable':
					$this->codes[] = array(
						'type'	=>	'Codes',
						'value'	=>	str_repeat("\t", $segment['indent']).'echo ' . $segment['value'] . ';'.PHP_EOL
					);
					break;
					case 'ForStatement':
					$value = 'for(';
					$value .= $segment['index'] . '=' . $segment['start'] . ';';
					if($segment['start']<=$segment['end']){
						$value .= $segment['index']. '<=' . $segment['end'] . ';';
					}else{
						$value .= $segment['index']. '>=' . $segment['end'] . ';';
					}
					if($segment['step']==1){
						$value .= $segment['index'] . '++';
					}
					elseif($segment['step']==-1){
						$value .= $segment['index'] . '--';
					}
					elseif($segment['step']>0){
						$value .= $segment['index'] . '+=' . $segment['step'];
					}
					elseif($segment['step']<0){
						$value .= $segment['index'] . '-=' . abs($segment['step']);
					}
					$this->codes[] = array(
						'type'	=>	'Codes',
						'value'	=>	 str_repeat("\t", $segment['indent']).$value
					);
					break;
					case 'EachStatement':
					$value = 'foreach(' . $segment['array'];
					$value .= ' as ' . $segment['key'];
					$value .= ' => ' . $segment['value'];
					$this->codes[] = array(
						'type'	=>	'Codes',
						'value'	=>	 str_repeat("\t", $segment['indent']).$value
					);
					break;
					case 'CloseBrace':
					$this->close_coder($segment, $body[$n-1]);
					break;
				}
			}else{
				exit('NIML_COMPILATION_ERROR: CANNOT FIND SEGMENT TYPE');
			}
		}
		$this->codes[] = array(
			'type'	=>	'End',
			'value'	=>	''
		);
		$this->compiler->CDS = $this->codes;
	}

	private function echo_coder($segment, $lastone){
		if($lastone&&$lastone['type'] === 'EchoString'){
			$this->codes[count($this->codes)-1]['values'][] = $segment['value'];
		}else{
			$this->codes[] = array(
				'type'		=>	'Strings',
				'indent'=>	$segment['indent'],
				'values'	=>	array($segment['value'])
			);
		}
	}

	private function close_coder($segment, $lastone){
		if($lastone['type'] === $segment['type']){
			$this->codes[count($this->codes)-1]['value'] .= $segment['value'].PHP_EOL;
		}else{
			$this->codes[] = array(
				'type'		=>	'Codes',
				'value'		=>	str_repeat("\t", $segment['indent']).$segment['value'].PHP_EOL
			);
		}
	}
}
