<?php
namespace AF\Models\Ect;

use PDO;
use System\NIDO\DataObject;
use RDO;
use Model;
use AF\Models\BaseR3Model;

class THMI extends BaseR3Model {
	//Template Theme Informations
	const
	ID_DESC = [['id', true, DataObject::SORT_REGULAR]],
	ID_ASC = [['id', false, DataObject::SORT_REGULAR]],
	ALIAS_DESC = [['alias', true, DataObject::SORT_REGULAR]],
	ALIAS_ASC = [['alias', false, DataObject::SORT_REGULAR]],
	NAME_DESC = [['name', true, DataObject::SORT_REGULAR]],
	NAME_ASC = [['name', false, DataObject::SORT_REGULAR]],
	NAME_DESC_GBK = [['name', true, DataObject::SORT_CONVERT_GBK]],
	NAME_ASC_GBK = [['name', false, DataObject::SORT_CONVERT_GBK]];

	protected static
    $table = DB_MAP.'themes',
    $indexes = ['alias', 'id'],
    $aikey = 'id',
    $lifetime = 0,
    $defaults = [
        'id'				=>	0,
		'type'     			=>  1,
		'opn_id'            =>  NULL,
        'alias'             =>  1,
        'name'              =>  '',   
        'description'      	=>  ''      
    ];

	public static function byAppid($appid, $format = BaseModel::LIST_AS_OBJ){
		$objs = [];
        $result = RDO::join([
			'table'	=>	DB_MAP.'appthemes',
			'field'	=>	'alias',
			'as'	=>	'A'
		], [
			'table'	=>	DB_MAP.'themes',
			'field'	=>	'alias',
			'as'	=>	'B'
		], 'A.app_id = '.$appid, 'B.name ASC', '*', 0);
        if($result){
            if($format===BaseModel::LIST_AS_ARR){
                return $result->toArray();
            }
            $pdos = $result->getPDOStatement();
            while($pdos&&$data = $pdos->fetch(PDO::FETCH_ASSOC)){
                $obj = new static();
                $obj->build($data, true);
                $objs[] = $obj;
            }
        }
        return $objs;
	}

	public static function getDefault($appid){
        $result = RDO::join([
			'table'	=>	DB_MAP.'appthemes',
			'field'	=>	'thm_alias',
			'as'	=>	'A'
		], [
			'table'	=>	DB_MAP.'themes',
			'field'	=>	'alias',
			'as'	=>	'B'
		], 'A.app_id = '.$appid, 'A.is_default ASC, B.name ASC', '*', 0);
        if($result){
            $pdos = $result->getPDOStatement();
            if($pdos&&$data = $pdos->fetch(PDO::FETCH_ASSOC)){
                $obj = new static();
                $obj->build($data, true);
                return $obj;
            }
        }
        return null;
	}
}