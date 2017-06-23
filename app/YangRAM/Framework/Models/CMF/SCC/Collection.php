<?php
namespace CM\SCC;
use Tangram\NIDO\DataObject;

/**
 *	Collegiate Data Type Model
 *	非关系型数据类型模型
 *	用来创建、修改、删除非关系型数据类型的模型，相当于关系型数据库中的一个数据表，但是数据组织结构宽松得多
 *  所有类型基于如下五大类型衍生而成：(后面时对应得数据单元)
 *  **  Collection 			CollectionUnit
 *  **  ItemList 			Item
 *  **  Schedule 			Project
 *  **  Survey 			 	Questionaire
 *  **  OrderCollection 	OrderForm
 *  **  其中Collection是所有其他类型得基本型，对应的CollectionUnit模型则是其他单元模型的基类
 *  数据细节正在设计之中……
 */
final class Collection extends Model  {
	const
	ID_DESC = [['hash', true, DataObject::SORT_REGULAR]],
	ID_ASC = [['hash', false, DataObject::SORT_REGULAR]],
	MTIME_DESC = [['KEY_MTIME', true, DataObject::SORT_REGULAR]],
	MTIME_ASC = [['KEY_MTIME', false, DataObject::SORT_REGULAR]],
	TITLE_DESC = [['TITLE', true, DataObject::SORT_REGULAR]],
	TITLE_ASC = [['TITLE', false, DataObject::SORT_REGULAR]],
	TITLE_DESC_GBK = [['TITLE', true, DataObject::SORT_CONVERT_GBK]],
	TITLE_ASC_GBK = [['TITLE', false, DataObject::SORT_CONVERT_GBK]];
	
	private static $storage, $memory, $rdo;

	protected static
    $ca_path = PATH_DAT_CNT.'',
	$defaults = [
		'hash'				=>	'',
		'basic_type'		=>	'base',
		'name'				=>	'New Set',
		'owner_uid'			=>	1,
		'administrator'		=>	'',
		'can_contribute'	=>	0,
		'nonaudit'			=>	1,
		'KEY_STATE'		=>	1
    ];

	private static function init(){
		if(!self::$storage){
			self::$storage = new Storage(static::$ca_path, Storage::JSN, true);;
			self::$storage->useHashKey(false)->setAfter('.json');
			self::$rdo = new RDOAdvanced();
		}
		self::$rdo->using(DB_SPC.'setinfos');
	}

	final public static function create(array $data, array $defaults){
		
	}
	
	public static function query($require = "0" , array $orderby = Collection::ID_ASC, $range = 0, $format = Model::LIST_AS_OBJ){
		
	}

	public static function byBasicType($basicType, array $orderby = Collection::ID_ASC){

	}

	public static function find ($key, $val, $index = false, $ok = '1'){
        if(is_numeric($index)){
            if($index>=0){
                return self::query([$key=>$val], [[$ok, false, DataObject::SORT_REGULAR]], [1, $index]);
            }
            return self::query([$key=>$val], [[$ok, true, DataObject::SORT_REGULAR]], [1, -1 - $index]);
        }
        if(is_bool($index)){
            return self::query([$key=>$val], [[$ok, $index, DataObject::SORT_REGULAR]], 0);
        }
        return self::query([$key=>$val], [[$ok, false, DataObject::SORT_REGULAR]], 0);
    }


	public static function hash($hash){
		self::init();
		$data = self::$storage->take($hash);
		return new self($data, true);
	}

	protected function __construct(){
        self::init();
    }

    protected function build($data, $posted = false){
		
    }

    public function save(){
        
    }

    public function on(){
        
    }

    public function off(){
        
    }

    public function addField($name, array $attrs){
        
    }

    public function modField(){

    }

    public function delField(){

    }

	public function destroy(){
        return false;
	}
}