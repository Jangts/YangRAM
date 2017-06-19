<?php
/*
 * NIML Compiler
 */
trait NIML_traits_translator_calls {
	protected function cl($input){
		if(preg_match_all($this->leftTAG . '\s*([\s\$\w\:\.\-\>\\\]+)\(([^\}]*)\);*' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$names = $matches[1];
			$args = $matches[2];
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:call name="' . $names[$n] . '" args="' . $args[$n] . '" />', $input);
			}
		}
		return $input;
	}

	protected function ct($input){
		// call $this->methods
		if(preg_match_all($this->leftTAG . '\s*(\.|->)\s*([\w\.\-\>]+)\(([^\}]*)\);*' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$names = $matches[2];
			$args = $matches[3];
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:call name="$this.' . $names[$n] . '" args="' . $args[$n] . '" />', $input);
			}
		}
		return $input;
	}

	protected function at($input){
		if(preg_match_all($this->leftTAG . '\s*@([\w\.\/]+)\s*' . $this->rightTAG, $input, $matches)){
			$tags = array_unique($matches[0]);
			$includes = array_unique($matches[1]);
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:include src="' . $includes[$n] . '" />', $input);
			}
		}
		return $input;
	}

	protected function _($input){
		if(preg_match_all($this->leftTAG . '\s*_\(([\s\S]+)\)\s*' . $this->rightTAG, $input, $matches)){
			$tags = array_unique($matches[0]);
			$includes = array_unique($matches[1]);
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:dict value="' . $includes[$n] . '" />', $input);
			}
		}
		return $input;
	}
}
