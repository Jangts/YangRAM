<?php
/*
 * NIML Compiler
 */
trait NIML_traits_translator_loops {
	protected function e($input){
		if(preg_match_all($this->leftTAG . '\s*([\$\w\[\`\]\.\/]+)\s+as\s+(\$\w+)\s+(\$\w+)\s*' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$lists = $matches[1];
			$keys = $matches[2];
			$items = $matches[3];
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:each list="' . $this->transformer($lists[$n]) . '" index="' . $this->transformer($keys[$n]) . '" item="' . $this->transformer($items[$n]) . '" >', $input);
			}
		}
		$input = str_replace('{{/each}}', '</ni:each>', $input);
		return $input;
	}

	protected function f($input){
		if(preg_match_all($this->leftTAG . '\s*(\$\w+)\s+(in|of)\s+([\$\w\[\`\]\.\/]+)\s*' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$items = $matches[1];
			$methods = $matches[2];
			$lists = $matches[3];
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:for ' . $this->transformer($items[$n]) . ' ' . $methods[$n] . ' ' . $this->transformer($lists[$n]) . ' >', $input);
			}
		}
		if(preg_match_all($this->leftTAG . '\s*(\[|\()\s*(\d+)\s*,\s*(\d+)\s*(\]|\))\s*->\s*(\$\w+)\s*' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$starttags = $matches[1];
			$starts = $matches[2];
			$ends = $matches[3];
			$endtags = $matches[4];
			$items = $matches[5];
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:for ' . $this->transformer($items[$n]) . ' in ' . $starttags[$n] . $starts[$n] . ',' . $ends[$n] . $endtags[$n] . '>', $input);
			}
		}
		if(preg_match_all($this->leftTAG . '\s*(\[)\s*(\d+|[\$\w\[\`\]\.\/]+)\s*,\s*(\d+|[\$\w\[\`\]\.\/]+)\s*(\]|\))\s*->\s*(\$\w+)\s*' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$starttags = $matches[1];
			$starts = $matches[2];
			$ends = $matches[3];
			$endtags = $matches[4];
			$items = $matches[5];
			foreach($tags as $n => $tag) {
				$input = str_replace($tag, '<ni:for ' . $this->transformer($items[$n]) . ' in [' . $this->transformer($starts[$n]) . ',' . $this->transformer($ends[$n]) . $endtags[$n] . '>', $input);
			}
		}
		if(preg_match_all($this->leftTAG . '\s*\[\s*([\$\w\[\`\]\.\/\s,]+)\s*\]\s*=>\s*(\$\w+)\s*' . $this->rightTAG, $input, $matches)){
			$tags = $matches[0];
			$arrays = $matches[1];
			$items = $matches[2];
			foreach($tags as $n => $tag) {
				$els = preg_split('/\s*,\s*/', $arrays[$n]);
				$elems  = [];
				foreach($els as $el) {
					$elems[] =$this->transformer($el);
				}
				$input = str_replace($tag, '<ni:for ' . $this->transformer($items[$n]) . ' of [' . join(',', $elems) .  ']>', $input);
			}
		}
		$input = str_replace('{{/for}}', '</ni:for>', $input);
		return $input;
	}
}
