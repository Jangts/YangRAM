<?php
namespace TC\Models\Data;
use System\NIDO\DataObject;
use Model;
use AF\Models\BaseR3Model;

class RecycleRule extends BaseR3Model {
    protected static
    $table = DB_AST.'trashcan_rules',
    $indexes = ['id'],
    $aikey = 'id',
    $lifetime = 0,
	$defaults = [
		'id'				        =>	0,
		'typename'		        	=>	'',
		'handle_appid'		        =>	0,
		'database_table'	   		=>	'',
		'index_field'				=>	'id',
		'title_field'				=>	'title',
		'recycled_state_field'		=>	'KEY_IS_RECYCLED',
		'recycled_time_field'		=>	'KEY_MTIME',
		'KEY_STATE'			    =>	0
    ];
}