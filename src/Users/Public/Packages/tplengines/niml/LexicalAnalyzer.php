<?php
/*
 * NIML Generator
 */
final class NIML_LexicalAnalyzer {

	use NIML_traits_helpers_analyzer;
	use NIML_traits_analyzer_echos;
	use NIML_traits_analyzer_calls;
	use NIML_traits_analyzer_branches;
	use NIML_traits_analyzer_assigns;
	use NIML_traits_analyzer_loops;

	private $compiler, $strings, $strings_L, $strings_R, $tokens, $length, $current;

	public function __construct($compiler, $strings, $strings_L, $strings_R){
		$this->compiler = $compiler;
		$this->tokens = $compiler->TKS;
		$this->strings = $strings;
		$this->strings_L = $strings_L;
		$this->strings_R = $strings_R;
		//var_dump($strings, $strings_L, $strings_R);
		//die;
		$this->length = count($this->tokens);
		$this->current = 0;
	}

	private function walk() {
        $token = $this->tokens[$this->current];

        if($token['type']==='strings'){
            $this->current++;

            $string = $this->unescape($token['value']);
            $array = preg_split('/[\r\n]+\s*/', $string);
            $body = [];
            foreach ($array as $str) {
                $body[] = array(
                    'type'  =>  'String',
					'value' =>  $this->unescape($str)
                );
            }

            return array(
                'type'  =>  'EchoExpression',
                'name'  =>  'echo',
                'body'  =>  $body
            );
        }

        if($token['type']==='operator'){
            switch ($token['value']) {
				case 'autoinc':
				return array(
					'type'  =>  'Unknow',
					'body' =>  $token['value']
				);

				case 'call':
				return $this->call();

				case 'decode':
				return $this->html_decode();

				case 'base64':
				return $this->base64_encode();

				case 'def':
				return $this->assign_def();

				case 'each':
				return $this->loop_each();

				case 'echo':
				return $this->write();

				case 'extract':
				return array(
					'type'  =>  'Unknow',
					'body' =>  $token['value']
				);

				case 'for':
				return $this->loop_for();

				case 'has':
				return $this->branch_has();

				case 'if':
				return $this->branch_if();

				case 'include':
				return $this->display();

				case 'json':
				return $this->assign_array_json();

				case 'let':
				return $this->assign_let();

				case 'loop':
				return $this->loop_times();

				case 'php':
				return $this->php();

				case 'set':
				return array(
					'type'  =>  'Unknow',
					'body' =>  $token['value']
				);

				case 'sql':
				return array(
					'type'  =>  'Unknow',
					'body' =>  $token['value']
				);

				case 'str':
				return $this->write_str();

				case 'switch':
				return $this->branch_switch();

				case 'time':
				return $this->time_format();

				case 'var':
				return $this->assign_var();

				case 'w':
				return $this->w();

				case 'while':
				return $this->loop_while();

				case 'xl':
				return $this->assign_array_xml();				

                default:
				if(substr($token['value'], 0, 1)!='/'){
					$this->current++;
					$args = [];
					if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='parameters'){
						$attrs = $this->attribute($this->tokens[$this->current++]['value']);
					}
					$alias = $token['value'];
					return array(
						'type'  =>  'CustomCallExpression',
						'alias'  =>  $alias,
						'params'  => $attrs
					);
				}
            }
        }
        $this->current++;
		return array(
			'type'  =>  'Unknow',
			'body' =>  $token['value']
		);
	}

	private function php(){
		$this->current++;
		if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='strings'){
			$value = $this->tokens[$this->current++]['value'];
			if(isset($this->tokens[$this->current])&&$this->tokens[$this->current]['type']==='operator'&&$this->tokens[$this->current]['value']==='/php'){
				$this->current++;
			}
			return array(
				'type'  =>  'PHPCodes',
				'body'	=>  $this->unescape($value)
			);
		}else{
			exit('NIML_COMPILATION_ERROR: ERROR PHP TAG');
		}
	}

    public function parser() {
        $midast = array(
                'type'  =>  'MIDAST',
                'body'  =>  []
        );
        while ($this->current < $this->length) {
			$midast['body'][] = $this->walk();
        }
		//var_dump($midast);
		//exit;
		$this->compiler->GRR = new NIML_Generator($this->compiler, $midast);
	}
}
