<?php
/*
 * NIML Compiler
 */
trait NIML_traits_translator_branches {
	protected function c($input){
		if(preg_match_all($this->leftTAG . 'case ([^\)]+)' . $this->rightTAG, $input, $matches)){
			$tags = array_unique($matches[0]);
			$vals = array_unique($matches[1]);
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:case value="' . $vals[$n] . '">', $input);
			}
		}
		$input = str_replace('{{break;}}', '</ni:case>', $input);
		return $input;
	}

	protected function s($input){
		if(preg_match_all($this->leftTAG . 'switch ([\$\w\[\`\]\.\/]+)' . $this->rightTAG, $input, $matches)){
			$tags = array_unique($matches[0]);
			$vars = array_unique($matches[1]);
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:switch name="' . $this->transformer($vals[$n]) . '">', $input);
			}
		}
		$input = str_replace('{{/switch}}', '</ni:switch>', $input);
		$input = str_replace('{{default:}}', '', $input);
		return $input;
	}

	protected function i($input){
		if(preg_match_all($this->leftTAG . '\s*([\$\w\[\`\]\.\/]+)(=|\s+(=|eq|is|ne|not|ge|gt|le|lt)\s+)([\$\w\[\`\]\.\/]+|\'[^\']+\'|"[^"]+")\s*=>([^>]+)>>(.+)' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$lefts = $matches[1];
			$symbols = $matches[2];
			$rights = $matches[4];
			$echos1 = $matches[5];
			$echos2 = $matches[6];
			foreach($tags as $n => $tag) {
				$input = @str_replace($tag, '<ni:if ' . $this->transformer($lefts[$n]) . ' ' . $symbols[$n] . ' ' . $this->transformer($rights[$n]) . '>' . $echos1[$n] . '<ni:else>' . $echos2[$n] . '</ni:if>', $input);
			}
		}
		if(preg_match_all($this->leftTAG . '\s*([\$\w\[\`\]\.\/]+)\s*=>([^>]+)>>(.+)' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$lefts = $matches[1];
			$echos1 = $matches[2];
			$echos2 = $matches[3];
			foreach($tags as $n => $tag) {
				$input = @str_replace($tag, '<ni:if ' . $this->transformer($lefts[$n]) . ' ' . $symbols[$n] . ' ' . $this->transformer($rights[$n]) . '>' . $echos1[$n] . '<ni:else>' . $echos2[$n] . '</ni:if>', $input);
			}
		}
		if(preg_match_all($this->leftTAG . '\s*([\$\w\[\`\]\.\/]+)(=|\s+(=|eq|is|ne|not|ge|gt|le|lt)\s+)([\$\w\[\`\]\.\/]+|\'[^\']+\'|"[^"]+")\s*=>(.+)' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$lefts = $matches[1];
			$symbols = $matches[2];
			$rights = $matches[4];
			$echos = $matches[5];
			foreach($tags as $n => $tag) {
				$input = @str_replace($tag, '<ni:if ' . $this->transformer($lefts[$n]) . ' ' . $symbols[$n] . ' ' . $this->transformer($rights[$n]) . '>' . $echos[$n] . '</ni:if>', $input);
			}
		}
		if(preg_match_all($this->leftTAG . '\s*([\$\w\[\`\]\.\/]+)\s*=>(.+)' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$lefts = $matches[1];
			$echos = $matches[2];
			foreach($tags as $n => $tag) {
				$input = @str_replace($tag, '<ni:if ' . $this->transformer($lefts[$n]) . '>' . $echos[$n] . '</ni:if>', $input);
			}
		}
		return $input;
	}

	protected function h($input){
		if(preg_match_all($this->leftTAG . '\s*(\'[^\']+\'|\"[^\"]+\")(\s*>>\s*|\s+in\s+)([\$\w\[\`\]\.\/]+)\s*=>([^>]+)' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$needles = $matches[1];
			$arrays = $matches[3];
			$echos = $matches[4];
			foreach($tags as $n => $tag) {
				$input = @str_replace($tag, '<ni:has array="' . $this->transformer($arrays[$n]) . '" value="' . $needles[$n] . '">' . $echos[$n] . '</ni:has>', $input);
			}
		}
		if(preg_match_all($this->leftTAG . '\s*([\$\w\[\`\]\.\/]+)(\s*>>\s*|\s+in\s+)([\$\w\[\`\]\.\/]+)\s*=>([^>]+)' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$needles = $matches[1];
			$arrays = $matches[3];
			$echos = $matches[4];
			foreach($tags as $n => $tag) {
				$input = @str_replace($tag, '<ni:has array="' . $this->transformer($arrays[$n]) . '" value="' . $this->transformer($needles[$n]) . '">' . $echos[$n] . '</ni:has>', $input);
			}
		}
		if(preg_match_all($this->leftTAG . '\s*([\$\w\[\`\]\.\/]+)(\s*>>\s*|\s+in\s+)\[([\s\S]+?)\]\s*=>(.+)' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$needles = $matches[1];
			$arrays = $matches[3];
			$echos = $matches[4];
			foreach($tags as $n => $tag) {
				$input = @str_replace($tag, '<ni:has list="' . $this->transformer($arrays[$n]) . '" value="' . $this->transformer($needles[$n]) . '">' . $echos[$n] . '</ni:has>', $input);
			}
		}
		return $input;
	}
}
