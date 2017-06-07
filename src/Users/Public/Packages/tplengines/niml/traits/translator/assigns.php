<?php
/*
 * NIML Compiler
 */
trait NIML_traits_translator_assigns {
	protected function def($input){
		if(preg_match_all($this->leftTAG . '\s*(\$\w+)\s*=([\s\$\w\:\.\-\>\\\]+)\(([^\}]*)\);*' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$names = $matches[1];
			$fns = $matches[2];
			$args = $matches[3];
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:def name="' . $this->transformer($names[$n]) . '" onload="' . $fns[$n] . '" args="' . $args[$n] . '" />', $input);
			}
		}
		return $input;
	}

    protected function v($input){
		if(preg_match_all($this->leftTAG . '\s*(\$\w+)\s*=\s*(\$[\`\$\w\[\`\]\.\/]+);*' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$names = $matches[1];
			$vars = $matches[2];
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:var name="' . $this->transformer($names[$n]) . '" value="' . $this->transformer($vars[$n]) . '" />', $input);
			}
		}
		if(preg_match_all($this->leftTAG . '\s*(\$\w+)\s*=\s*\'([^\']+)\';*' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$names = $matches[1];
			$vars = $matches[2];
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:var name="' . $this->transformer($names[$n]) . '" value="' . $vars[$n] . '" type="string" />', $input);
			}
		}
		if(preg_match_all($this->leftTAG . '\s*(\$\w+)\s*=\s*"([^\']+)";*' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$names = $matches[1];
			$vars = $matches[2];
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:var name="' . $this->transformer($names[$n]) . '" value="' . $vars[$n] . '" type="string" />', $input);
			}
		}
		if(preg_match_all($this->leftTAG . '\s*(\$\w+)\s*=\s*([^\'\"].+);*' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$names = $matches[1];
			$vars = $matches[2];
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:var name="' . $this->transformer($names[$n]) . '" value="' . $vars[$n] . '" />', $input);
			}
		}
		return $input;
	}
}
