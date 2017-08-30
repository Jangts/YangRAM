<?php
namespace UOI\Models;

class Link extends \Model {
	protected static $defaults = [
		'appid' 		=>	0,
        'type' 		    =>	'app',
		'icon' 			=>	__GET_DIR.'i/sources/icons/0/80',
		'name'			=>	'New Link',
		'description' 	=>	 'new link',
		'href'			=>	'default',
		'menu'			=>	'bookmark',
		'viewmode'		=>	'center'
	];

	public function __construct($data){
        $this->build($data);
    }
}
