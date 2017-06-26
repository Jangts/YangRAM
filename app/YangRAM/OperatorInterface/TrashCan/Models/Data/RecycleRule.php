<?php
namespace TC\Models\Data;
use Tangram\NIDO\DataObject;
use Model;
use AF\Models\R3Model_BC;

class RecycleRule extends R3Model_BC {
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