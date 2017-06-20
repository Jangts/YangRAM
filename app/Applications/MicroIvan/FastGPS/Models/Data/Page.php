<?php
namespace GPS\Models\Data;

use Status;
use System\NIDO\DataObject;
use AF\Models\BaseR3Model;
use System\ORM\Counter;
use CM\SPCLite;

class Page extends BaseR3Model {
    const
	ALL = 0,
	INUSE = 1,
	UNUSE = 2, 

    PID_DESC = [['pid', true, DataObject::SORT_REGULAR]],
	PID_ASC = [['pid', false, DataObject::SORT_REGULAR]],
    CTIME_DESC = [['KEY_CTIME', true, DataObject::SORT_REGULAR]],
	CTIME_ASC = [['KEY_CTIME', false, DataObject::SORT_REGULAR]],
	MTIME_DESC = [['KEY_MTIME', true, DataObject::SORT_REGULAR]],
	MTIME_ASC = [['KEY_MTIME', false, DataObject::SORT_REGULAR]],
	VC_DESC = [['KEY_COUNT', true, DataObject::SORT_REGULAR]],
	VC_ASC = [['KEY_COUNT', false, DataObject::SORT_REGULAR]],
    NAME_DESC = [['name', true, DataObject::SORT_REGULAR]],
	NAME_ASC = [['name', false, DataObject::SORT_REGULAR]],
	NAME_DESC_GBK = [['name', true, DataObject::SORT_CONVERT_GBK]],
	NAME_ASC_GBK = [['name', false, DataObject::SORT_CONVERT_GBK]];

	protected static
	$ca_path = PATH_CACA.AI_CURR.'/pages/',
    $conn = 0,
    $table = TP_CURR.'pages',
    $indexes = ['pid'],
    $aikey = 'pid',
    $lifetime = 0,
	$spcsorts = [
		SPCLite::ID_DESC,
		SPCLite::ID_DESC,
		SPCLite::ID_ASC,
		SPCLite::PUBTIME_DESC,
		SPCLite::PUBTIME_ASC,
		SPCLite::MTIME_DESC,
		SPCLite::MTIME_ASC,
		SPCLite::HIT_DESC,
		SPCLite::HIT_ASC,
		SPCLite::RANK_DESC,
		SPCLite::RANK_ASC,
		SPCLite::LEVEL_DESC,
		SPCLite::LEVEL_ASC,
		SPCLite::TITLE_DESC,
		SPCLite::TITLE_ASC,
		SPCLite::TITLE_DESC_GBK,
		SPCLite::TITLE_ASC_GBK
	],
	$defaults = [
		'pid'				=>	0,
		'type'				=>	0,
		'mark'				=>	'',
		'gec_default_alias'	=>	'',
		'category_id'		=>	0,
		'name'				=>	'New Page',
		'title'				=>	'A YangRAM Page',
		'keywords'			=>	NULL,
		'description'		=>	NULL,
		'sort_order'		=>	3,
		'prepage_number'	=>	8,
		'use_base64'		=>	1,
		'use_context'		=>	0,
		'theme'				=>	'default',
		'template'			=>	'default.niml',
		'remark'			=>	NULL,
		'KEY_IS_RECYCLED'		=>	0,
		'KEY_COUNT'	=>	0,
		'KEY_CTIME'		=>	DATETIME,
		'KEY_MTIME'		=>	DATETIME,
		'KEY_STATE'		=>	0
	],
	$validFields = [
		'1'	=>	['pid','type','mark','name','title','keywords','description','theme','template','remark','KEY_IS_RECYCLED','KEY_COUNT','KEY_CTIME','KEY_MTIME','KEY_STATE'],
		'2'	=>	['pid','type','mark','name','title','keywords','description','gec_default_alias','theme','template','remark','KEY_IS_RECYCLED','KEY_COUNT','KEY_CTIME','KEY_MTIME','KEY_STATE'],
		'3'	=>	['pid','type','mark','name','title','keywords','description','theme','template','remark','KEY_IS_RECYCLED','KEY_COUNT','KEY_CTIME','KEY_MTIME','KEY_STATE'],
		
		'4'	=>	['pid','type','mark','name','title','keywords','description','sort_order','prepage_number','theme','template','remark','KEY_IS_RECYCLED','KEY_COUNT','KEY_CTIME','KEY_MTIME','KEY_STATE'],
		'5'	=>	['pid','type','mark','name','title','keywords','description','sort_order','prepage_number','theme','template','remark','KEY_IS_RECYCLED','KEY_COUNT','KEY_CTIME','KEY_MTIME','KEY_STATE'],
		'6'	=>	['pid','type','mark','name','title','keywords','description','sort_order','prepage_number','theme','template','remark','KEY_IS_RECYCLED','KEY_COUNT','KEY_CTIME','KEY_MTIME','KEY_STATE'],
		

		'7'	=>	['pid','type','mark','name','title','keywords','description','use_base64','use_context','theme','template','remark','KEY_IS_RECYCLED','KEY_COUNT','KEY_CTIME','KEY_MTIME','KEY_STATE'],
		'8'	=>	['pid','type','mark','name','title','keywords','description','use_base64','use_context','theme','template','remark','KEY_IS_RECYCLED','KEY_COUNT','KEY_CTIME','KEY_MTIME','KEY_STATE'],
	];

	public static function byFeature($type, $mark, $category_id = 0){
		$pages = self::query(array('type'=>$type, 'mark'=>$mark, 'category_id'=>$category_id, 'KEY_IS_RECYCLED'=>0), array(array('1', false)), 1);
		if(isset($pages[0])){
			return $pages[0];
		}
		switch($type){
			case 3:
			$type = 2;
			break;
			case 5:
			case 6:
			$type = 4;
			break;
			case 8:
			$type = 7;
			break;
			default:
			return false;
		}
		$pages = self::query(array('type'=>$type, 'mark'=>$mark, 'KEY_IS_RECYCLED'=>0), array(array('1', false)), 1);
		if(isset($pages[0])){
			return $pages[0];
		}
		return false;
	}

	public static function byId($pid, $status = Page::ALL){
		$pid = intval($pid);
		switch($status){
			case Page::INUSE:
			$pages = self::query(['pid'=>$pid, 'KEY_IS_RECYCLED'=>0, 'KEY_STATE'=>1], ['1', false], 1);
			break;

			case Page::UNUSE:
			$pages = self::query(['pid'=>$pid, 'KEY_IS_RECYCLED'=>0, 'KEY_STATE'=>0], ['1', false], 1);
			break;

			default:
			$pages = self::query(array('pid'=>$pid, 'KEY_IS_RECYCLED'=>0), array(array('1', false)), 1);
		}
		if(isset($pages[0])){
			return $pages[0];
		}
		return false;
	}

    public static function getList($type, $order, $status, $start = 0, $num = 18, $format = Model::LIST_AS_OBJ){
		switch ($status) {
			case Page::INUSE:
			return self::query(['type'=>$type, 'KEY_IS_RECYCLED'=>0, 'KEY_STATE'=>1], $order, [$start, $num], $format);

			case Page::UNUSE:
			return self::query(['type'=>$type, 'KEY_IS_RECYCLED'=>0, 'KEY_STATE'=>0], $order, [$start, $num], $format);
			
			default:
			return self::query(['type'=>$type, 'KEY_IS_RECYCLED'=>0], $order, [$start, $num], $format);
		}
	}

	public function countit(){
		$counter = new Counter(self::$table, self::$conn);
		$counter->setFields('KEY_COUNT', 'pid')->point($this->data['pid'])->add();
		//var_dump($counter);
	}

	public function orderby(){
		$sort = $this->data['sort_order'];
		if(isset(self::$spcsorts[$sort])){
			return self::$spcsorts[$sort];
		}
		return self::$spcsorts[0];
	}

	public function take($page = 1){
		$length = $this->data['prepage_number'];
		if(is_numeric($page)&&$page>0){
			$page = $page - 1;
		}else{
			$page = 0;
		}
		$currpage = $page + 1;
		return [$currpage, $page * $length, $currpage * $length, $length];
	}

	public function getValidFields(){
		if(isset(self::$validFields[$this->data['type']])){
			$array = [];
			foreach(self::$validFields[$this->data['type']] as $key){
				$array[$key] = $this->data[$key];
			}
			return $array;
		}
		return $this->data;
	}
}
