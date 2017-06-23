<?php
namespace CM;

use PDO;
use Tangram\NIDO\DataObject;
use Status;
use Model;
use Storage;
use RDO;

/**
 *	System File Resource Lite Data
 *	系统文件资源轻数据
 *  是一个忽略五类文件资源数据差异的精简的单一标准模型
 */
final class SRCLite extends BaseModel {
	const
	CTIME_DESC = [['SRC_ID', true, DataObject::SORT_REGULAR]],
	CTIME_ASC = [['SRC_ID', false, DataObject::SORT_REGULAR]],
	MTIME_DESC = [['KEY_MTIME', true, DataObject::SORT_REGULAR]],
	MTIME_ASC = [['KEY_MTIME', false, DataObject::SORT_REGULAR]],
	FSIZE_DESC = [['FILE_SIZE', true, DataObject::SORT_REGULAR]],
	FSIZE_ASC = [['FILE_SIZE', false, DataObject::SORT_REGULAR]],
	FTYPE_DESC = [['SUFFIX', true, DataObject::SORT_REGULAR]],
	FTYPE_ASC = [['SUFFIX', false, DataObject::SORT_REGULAR]],
	FNAME_DESC = [['FILE_NAME', true, DataObject::SORT_REGULAR]],
	FNAME_ASC = [['FILE_NAME', false, DataObject::SORT_REGULAR]],
	FNAME_DESC_GBK = [['FILE_NAME', true, DataObject::SORT_CONVERT_GBK]],
	FNAME_ASC_GBK = [['FILE_NAME', false, DataObject::SORT_CONVERT_GBK]],

	UNRECYCLE = 0,
	RECYCLE = 1,
	HIDE = 2;
	
	private static $memory, $storage, $rdo;

	protected static
	$ca_path = PATH_DAT_CNT.'resources/',
	$defaults = [
		'ID'         	=>  '',
		'SRC_ID'        =>  0,
		'FLD_ID'        =>  6,
		'FILE_NAME'     =>  '',
		'FILE_TYPE'     =>  'archive',
		'FILE_SIZE'     =>  0,
		'SUFFIX'        =>  '',
		'KEY_MTIME'   =>  DATETIME,
        'KEY_IS_RECYCLED' =>  0,
		'USR_ID'        =>  0
    ];

	private static function init(){
		if(!self::$rdo){
			self::$memory = [];
			self::$rdo = new RDO;
			self::$storage = new Storage(static::$ca_path, Storage::JSN, true);
		}
	}

	private static function checkName($name, $suffix, $folder, $id){
		if($suffix){
			$basename = preg_replace('/\\.'.$suffix.'$/', '', $name);
			$suffix = '.'.$suffix;
		}else{
			$basename = $name;
			$suffix = '';
		}
		$result = self::$rdo->requiring()->where('FILE_NAME', $name)->where('FLD_ID', $folder)->where('KEY_IS_RECYCLED', 0)->where('ID', $id, '<>')->take(1)->orderby(false)->count('ID');
		$num = 1;
		while($result){
			$name = $basename . '(' . $num++ . ')'.$suffix;
			$result = self::$rdo->requiring()->where('FILE_NAME', $name)->where('FLD_ID', $folder)->where('KEY_IS_RECYCLED', 0)->where('ID', $id, '<>')->take(1)->orderby(false)->count('ID');
		}
		return $name;
	}

	public static function count($type = 'all'){
		self::setQueryType($type);
		return self::$rdo->count('ID');
	}

	public static function all($type = 'all'){
		self::setQueryType($type);
		return self::$rdo->count('ID');
	}

	public static function byId($ID, $type){
		return new self($ID, $type);
	}

	public static function bySrouce($SRC_ID, $type = 'all', $take = 0){
		if(is_numeric($take)){
			return self::query($type, "`SRC_ID` = $SRC_ID", SRCLite::CTIME_ASC, [0, $take]);
		}
		return self::query($type, "`SRC_ID` = $SRC_ID", SRCLite::CTIME_ASC);
	}

	public static function byFolder($FLD_ID, $type = 'all', $orderby = SRCLite::CTIME_ASC){
		return self::query($type, "`FLD_ID` = $FLD_ID AND `KEY_IS_RECYCLED` = 0", $orderby);
	}

	public static function type($filetype){
		switch($filetype){
			case 'image':
			return 'img';
			
			case 'text':
			return 'txt';
			
			case 'video':
			return 'vod';
			
			case 'audio':
			return 'wav';
			
			default:
			return 'doc';
		}
	}

	public static function setQueryType($type = 'all'){
		self::init();
		if($type=='all'){
			self::$rdo->using(DB_SRC.'doc', DB_SRC.'img', DB_SRC.'txt', DB_SRC.'vod', DB_SRC.'wav');
		}else{
			self::$rdo->using(DB_SRC.$type);
		}
	}

	public static function query($type = 'all', $require = "0" , array $orderby = SRCLite::CTIME_ASC, $range = 0, $format = Model::LIST_AS_OBJ){
        $objs = [];
		self::setQueryType($type);
        $result = self::querySelect(self::$rdo, $require, $orderby, $range);
        if($result){
			if($format===Model::LIST_AS_ARR){
                return $result->toArray();
            }
            $pdos = $result->getPDOStatement();
            while($pdos&&$data = $pdos->fetch(PDO::FETCH_ASSOC)){
				$type = self::type($data['FILE_TYPE']);
                self::$memory[$type .'_'. $data['ID']] = $data;
                $objs[] = new self($data['ID'], $type);
            }
        }
        return $objs;
    }

	public static function create(array $data, $type){
		$obj = new self('', $type);
		return $obj->buildByData($data, $type);
	}

	public static function updateBySource($SRC_ID, array $data, $type){
		$objs = self::query($type, ['SRC_ID' => $SRC_ID]);
		foreach($objs as $obj){
			$obj->put($data);
		}
		return $objs;
	}

	public static function moveto($require, $folder = 0){
        $objs = self::query($require);
		foreach($objs as $obj){
			$obj->FLD_ID = $folder;
			$obj->save();
		}
		return $objs;
	}

	public static function remove($require, $status = SRCLite::RECYCLE, $type = 'all'){
		$objs = self::query($type, $require);
		foreach($objs as $obj){
			$obj->recycle($status);
		}
		return $objs;
	}

	private $_hash, $posted, $type;

	public function __construct($ID, $type){
		self::init();
		self::$rdo->using(DB_SRC.$type);
		if($ID){
			$this->_hash = $name = $type .'_'. $ID;
			if(isset(self::$memory[$name])){
				$this->type = $type;
				$this->data = self::$memory[$name];
				$this->posted = self::$memory[$name];
			}else{
				$this->buildById($ID, $type);
			}
		}
	}

	protected function buildById($ID, $type){
		if(in_array($type, ['img', 'doc', 'txt', 'wav', 'vod'])){
			$this->type = $type;
			if($cache = self::$storage->take($this->_hash)){
				$this->data = $cache;
                $this->posted = $cache;
			}else{
				$result = self::$rdo->requiring()->where('ID', $ID)->take(1)->select();
				if($result&&$data = $result->getRow()){
					$this->data = $data;
					$this->posted = $data;
					self::$storage->store($this->_hash, $data);
				}else{
					$this->data = self::$defaults;
					$this->posted = NULL;
				}
			}
		}else{
			new Status(703.4, 'Using Module Error', 'No Such Resource Type [' .$type. '].', true);
		}
	}

	protected function buildByData(array $data, $type){
		if(in_array($type, ['img', 'doc', 'txt', 'wav', 'vod'])){
			$this->type = $type;
			$this->data = [];
			unset($data['KEY_CTIME']);
        	foreach(static::$defaults as $key=>$val){
            	if(isset($data[$key])){
                	$this->data[$key] = $data[$key];
            	}else{
                	$this->data[$key] = $val;
            	}
            	$this->posted = NULL;
        	}
		}else{
			new Status(703.4, 'Using Module Error', 'No Such Resource Type [' .$type. '].', true);
		}
		return $this;
	}

	public function put($data){
		unset($data['KEY_CTIME']);
        foreach(static::$defaults as $key=>$val){
			if(array_key_exists($key, $data)){
				$this->data[$key] = $data[$key];
			}
		}
        return $this;
    }

	public function cln(){
		$obj = new self('', $this->type);
		return $obj->buildByData($this->data, $this->type);
	}

	public function save(){
		$rdo = self::$rdo;
		$this->data['FILE_NAME'] = self::checkName($this->data['FILE_NAME'], $this->data['SUFFIX'], $this->data['FLD_ID'], $this->data['ID']);
		$this->data['KEY_MTIME']   =	DATETIME;
        if($this->posted){
            if(empty($this->data['ID'])){
                return false;
            }
            $diff = $this->diff($this->data, $this->posted, DataObject::DIFF_SIMPLE);
            $data = $diff['__M__'];
            if(count($data)==0){
                return true;
            }
            if($rdo->requiring()->where('ID', $this->data['ID'])->update($data)){
                foreach ($data as $key => $val) {
                    $this->posted[$key] = $val;
                }
            }else{
                return false;
            }
        }else{
            if(!$rdo->insert($this->data)){
                return false;
            }
            $this->posted = $this->data;
        }
        self::$storage->store($this->_hash);
        return $this;
	}

	public function extend(){
		return SRC::byId($this->data['ID'], $this->type);
	}

	public function rename($newname){
		$this->data['FILE_NAME'] =	$newname;
		$this->posted&&$this->save();
		return $this->data['FILE_NAME'];
	}

	public function recycle($status = SRCLite::RECYCLE){
		$status = in_array($status, [0, 1, 2]) ? $status : 1;
		$this->data['KEY_IS_RECYCLED'] =	$status;
		if($this->posted){
			return $this->save();
		}
		return $this;
	}
}