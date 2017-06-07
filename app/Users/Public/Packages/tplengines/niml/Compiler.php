<?php
/*
 * NIML Compiler
 */
final class NIML_Compiler extends NIML_SyntacticAnalyzer {
	protected $input = 'MIDAST File Not Exists.';

	public $TLR, $AST, $CDS;

	public function __construct($input){
		$this->input = $input;
	}

	public function outputter($filename) {
		if (!file_exists($path = dirname($filename))){
			mkdir($path, 0777, true) or die("Unable create cache directory!");
		}
		$contents = '<?php'.PHP_EOL;
		foreach ($this->CDS as $n => $code) {
			if($code['type'] === 'Codes'){
				$contents .= $code['value'];
			}
			elseif($code['type'] === 'Strings'){
				$contents .= str_repeat("\t", $code['indent']).'echo \'';
				$max = count($code['values'])-1;
				foreach ($code['values'] as $n=>$value) {
					if($n==$max){
						$contents .= $value;
					}else{
						$contents .= $value."'.PHP_EOL.'";
					}
				}
				$contents .= '\';'.PHP_EOL;
			}
			elseif($code['type'] === 'Start'){
				$contents .= '/*'.PHP_EOL;
				$contents .= ' * PHP CODE COMPILED FROM NIML'.PHP_EOL;
				$contents .= ' * CODE START'.PHP_EOL;
				$contents .= ' */'.PHP_EOL;
			}
			elseif($code['type'] === 'End'){
				$contents .= '/*'.PHP_EOL;
				$contents .= ' * CODE END'.PHP_EOL;
				$contents .= ' */'.PHP_EOL;
			}
		}

		$file = fopen($filename, 'w') or die("Unable to open file!");
		fwrite($file, $contents);
		fclose($file);
		return $contents;
	}
}
