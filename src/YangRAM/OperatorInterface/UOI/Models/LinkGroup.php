<?php
namespace UOI\Models;

class LinkGroup extends \Model {
	protected static $defaults = [
		'name'		=>	'New Group',
		'menu' 		=>	'bookmark-group',
		'links'		=>	[]
	];

	public function __construct($data){
        $this->build($data);
    }
	
	public function addLink($linkdata){
		$link = new Link($linkdata);
		$this->data['links'][] = $link->toArray();
	}
}