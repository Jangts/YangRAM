<?php
/*
 * NIML Compiler
 */
trait NIML_traits_translator_echos {
	protected function w($input){
		if(preg_match_all($this->leftTAG . '\s*([\$\w\[\`\]\.\/]+)\s*' . $this->rightTAG, $input, $matches)){
			$tags = array_unique($matches[0]);
			$echos = array_unique($matches[1]);
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:w ' . $this->transformer($echos[$n]) . ' />', $input);
			}
		}
		return $input;
	}

	protected function ss($input){
		if(preg_match_all($this->leftTAG . '\s*([\$\w\[\`\]\.\/]+)\s*,\s*(\d+)\s*,\s*(\d+)\s*' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$strs = $matches[1];
			$starts = $matches[2];
			$lens = $matches[3];
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:str value="' . $this->transformer($strs[$n]) . '" start="' . $starts[$n] . '" length="' . $lens[$n] . '" />', $input);
			}
		}
		if(preg_match_all($this->leftTAG . '\s*([\$\w\[\`\]\.\/]+)\s*,\s*(\d+)\s*' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$strs = $matches[1];
			$lens = $matches[2];
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:str value="' . $this->transformer($strs[$n]) . '" start="0" length="' . $lens[$n] . '" />', $input);
			}
		}
		return $input;
	}

	protected function d($input){
		if(preg_match_all($this->leftTAG . '\s*>>>([\$\w\[\`\]\.\/]+)\s*' . $this->rightTAG, $input, $matches)){
			$tags = array_unique($matches[0]);
			$echos = array_unique($matches[1]);
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:decode value="' . $this->transformer($echos[$n]) . '" />', $input);
			}
		}
		return $input;
	}

	protected function b($input){
		if(preg_match_all($this->leftTAG . '\s*\*\*\*\s*([\$\w\[\`\]\.\/]+)\s*\*\*\*\s*' . $this->rightTAG, $input, $matches)){
			$tags = array_unique($matches[0]);
			$echos = array_unique($matches[1]);
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:base64 value="' . $this->transformer($echos[$n]) . '" />', $input);
			}
		}
		return $input;
	}
}
