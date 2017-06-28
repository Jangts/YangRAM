<?php
/*
 * NIML Compiler
 */
final class NIML_Translator {

	use NIML_traits_helpers_translator;
	use NIML_traits_translator_echos;
	use NIML_traits_translator_calls;
	use NIML_traits_translator_branches;
	use NIML_traits_translator_assigns;
	use NIML_traits_translator_loops;

	protected $leftTAG, $rightTAG;

	public function __construct($leftTAG = '{{', $rightTAG = '}}'){
		$this->leftTAG = '/' . preg_replace('(\$|\{|\[|\||\]|\})', '\\\\' . '$0', $leftTAG);
		$this->rightTAG = preg_replace('(\$|\{|\[|\||\]|\})', '\\\\' . '$0', $rightTAG). '/';
	}

	public function translate($input, $nimlonly = true){
		$input = $this->el($input);
		if($nimlonly){
			$input = $this->format($input);
			$input = str_replace('<html:', '<', $input);
			$input = str_replace('</html:', '</', $input);
		}
		return $this->cl($this->ct($this->at($this->_($this->i($this->h($this->b($this->d($this->w($this->ss($this->v($this->def($this->f($this->e($this->s($this->c($input))))))))))))))));
	}

	protected function el($input){
		if(preg_match_all($this->leftTAG . 'system:(\w+)' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$labels = $matches[1];
			foreach($labels as $n => $label) {
				$code = SNC::ByLabel($label)->code;
				if(is_string($code)){
					$input = str_replace($tags[$n], $code, $input);
				}
			}
		}
		return $input;
	}

	protected function format($input){
		$keywords = array(
			'autoinc',
			'call',
			'case',
			'decode',
			'def',
			'each',
			'echo',
			'else',
			'extract',
			'for',
			'has',
			'if',
			'include',
			'dict',
			'json',
			'let',
			'loop',
			'php',
			'set',
			'sql',
			'str',
			'switch',
			'time',
			'var',
			'w',
			'while',
			'xl'
		);
		$preg = '/<(\/){0,1}(' . join('|', $keywords) . ')(\s+|(\/){0,1}>)/';
		return preg_replace($preg, '<$1ni:$2$3', $input);
	}
}
