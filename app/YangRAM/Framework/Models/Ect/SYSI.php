<?php
namespace AF\Models\Ect;

use Status;
use Model;
use RDO;
use Library;
/**
 *
 */
class SYSI extends Model {

	// System Informations
	protected $data = [
		'ADDR'		=>	'',
		'BRIEF'		=>	'',
		'LANG'		=>	'',
		'LOC_ID'	=>	'',
		'NAME'		=>	'',
		'OWNER'		=>	'',
		'REMARK'	=>	'',
		'ID'		=>	'',
		'LAT'		=>	'',
		'LNG'		=>	'',
		'TEL1'		=>	'',
		'TEL2'		=>	'',
		'EMAIL'		=>	''
	];

	public function __construct(){
		$la_info = RDO::one(DB_REG.'languages', "LANG = '".$GLOBALS['RUNTIME']->LANGUAGE."'");
		if(empty($la_info)){
			$la_info = RDO::one(DB_REG.'languages', "lang = '"._LANG_."'");
		}
		if(empty($la_info)){
			$la_info = RDO::one(DB_REG.'languages');
		}
		if(!empty($la_info)){
			$lo_info = RDO::id(DB_SYS.'locations', $la_info["LOC_ID"]);
			if(!empty($lo_info)){
				$info = array_merge($la_info, $lo_info);
				foreach($info as $key	=>$val){
					$this->data[strtoupper($key)] = $val;
				}
			}
		}
	}
}
