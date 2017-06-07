<?php
namespace Library\ect;

Class DataTree {
	public $result = [];
	private $data;
	private $index;
	private $parent;
	private $levelkey;
	public function __construct($array, $index = 'id', $parent = 'parent', $levelkey = 'level') {
		$this->data = $array;
		$this->index = $index;
		$this->parent = $parent;
		$this->levelkey = $levelkey;
	}

	public function getAllOrderByRoot($rootId = 0, $level = 1) {
		foreach($this->data as $leaf) {
			if ($leaf[$this->parent] == $rootId) {
				$leaf[$this->levelkey] = $level;
				$this->result[] = $leaf;
				$this->getAllOrderByRoot($leaf[$this->index], $level + 1);
			}
		}
	}
	
	public function getRootsWithChildren($rootId = 0, $level = 1) {
		foreach($this->data as $leaf) {
			if ($leaf[$this->parent] == $rootId) {
				$leaf[$this->levelkey] = $level;
				$leaf['children'] = $this->getChildren($leaf[$this->index], $level + 1);
				$this->result[] = $leaf;
			}
		}
	}
	
	public function getAllWithChildren($rootId = 0, $level = 1) {
		foreach($this->data as $leaf) {
			$leaf[$this->levelkey] = $level;
			$leaf['children'] = $this->getChildren($leaf[$this->index], $level + 1);
			$this->result[] = $leaf;
		} 
	}
	
	public function getAllWithParents($rootId = 0, $level = 1) {
		foreach($this->data as $leaf) {
			$leaf[$this->levelkey] = $level;
			$leaf['parents'] = $this->getParents($leaf[$this->parent], $level + 1);
			$this->result[] = $leaf;
		}
	}
	
	protected function getChildren($parent, $level) {
		$children = [];
		foreach($this->data as $leaf) {
			if ($leaf[$this->parent] == $parent) {
				$leaf[$this->levelkey] = $level;
				$leaf['children'] = $this->getChildren($leaf[$this->index], $level + 1);
				$children[] = $leaf;
			}
		}
		return $children;
	}
	
	protected function getParents($index, $level) {
		$parents = [];
		foreach($this->data as $leaf) {
			if ($leaf[$this->index] == $index) {
				$leaf[$this->levelkey] = $level;
				$leaf['parents'] = $this->getParents($leaf[$this->parent], $level + 1);
				$parents[] = $leaf;
			}
		}
		return $parents;
	}
}