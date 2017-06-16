<?php
namespace CM;

use PDO;
use System\NIDO\DataObject;
use Model;
use AF\Models\BaseR3Model;
use System\ORM\Counter;

/**
 *	Model Of Custom Content In General Use 
 *	通用自定义格式内容模型
 *  通用内容，独立内容
 *  提供针对通用内容进行增删改查的接口
 *  比REC多出几个附加模块，一般用来建立页面/文档等完整内容
 */
final class GEC extends BaseR3Model {
	const
	ALL = 0,
	RECYCLED = 1,
	UNRECYCLED = 2, 

	ID_DESC = [['ID', true, DataObject::SORT_REGULAR]],
	ID_ASC = [['ID', false, DataObject::SORT_REGULAR]],
	CTIME_DESC = [['KEY_CTIME', true, DataObject::SORT_REGULAR]],
	CTIME_ASC = [['KEY_CTIME', false, DataObject::SORT_REGULAR]],
	MTIME_DESC = [['KEY_MTIME', true, DataObject::SORT_REGULAR]],
	MTIME_ASC = [['KEY_MTIME', false, DataObject::SORT_REGULAR]],
	HIT_DESC = [['KEY_COUNT', true, DataObject::SORT_REGULAR]],
	HIT_ASC = [['KEY_COUNT', false, DataObject::SORT_REGULAR]],
	TITLE_DESC = [['TITLE', true, DataObject::SORT_REGULAR]],
	TITLE_ASC = [['TITLE', false, DataObject::SORT_REGULAR]],
	TITLE_DESC_GBK = [['TITLE', true, DataObject::SORT_CONVERT_GBK]],
	TITLE_ASC_GBK = [['TITLE', false, DataObject::SORT_CONVERT_GBK]],
	GROUP_DESC = [['GROUPCODE', true, DataObject::SORT_REGULAR]],
	GROUP_ASC = [['GROUPCODE', false, DataObject::SORT_REGULAR]];

	protected static
    $table = DB_CNT.'in_general_use',
    $indexes = ['ID'],
    $aikey = 'ID',
    $lifetime = 0,
	$defaults = [
		'ID'				=>	0,
		'GROUPCODE'			=>	'Temporary Group',
		'ALIAS'				=>	'new_content',
		'BANNER'			=>	'',
		'TITLE'				=>	'New Content',
		'KEYWORDS'			=>	'',
		'DESCRIPTION'		=>	'',
		'CONTENT'			=>	'the contents here',
		'CUSTOM_I'			=>	'',
		'CUSTOM_II'			=>	'',
		'KEY_COUNT'		=>	0,
		'KEY_CTIME'		=>	DATETIME,
		'KEY_MTIME'		=>	DATETIME,
		'KEY_IS_RECYCLED'		=>	0
    ];

	public static function create(array $post){
		//$inserts = self::checkPostData($post);
		$obj = new self;
		$obj->build(self::$defaults);
		if($obj->put($post)->save()){
			return $obj;
		}
		return null;
	}

	public static function groups(){
		$rdo = self::getRDO();
		$tsr = $rdo->requiring()->distinct('GROUPCODE');
		if($tsr){
			return $tsr->toArray();
		}
		return [];
	}

	public static function byId($group, $alias = NULL){
		if($alias){
			$contents = self::query(['GROUPCODE'=>$group,'ALIAS'=>$alias], GEC::ID_ASC, 1);
		}else{
			$contents = self::query(['GROUPCODE'=>$group], GEC::ID_ASC, 1);
		}
		if(isset($contents[0])) return $contents[0];
		return false;
	}

	public static function count($GROUPCODE = NULL, $status = GEC::UNRECYCLE) {
		$rdo = self::getRDO();
		$rdo->requiring()->orderby(false)->take(0);
		if(is_string($GROUPCODE)){
			$rdo->where('GROUPCODE', $GROUPCODE);
		}
		if($status){
			$rdo->where('KEY_IS_RECYCLED', 2 - $status);
		}
		return $rdo->count();
	}

	public static function all(){
		return self::query();
	}

	public static function getList($GROUPCODE = NULL, array $orderby = GEC::ID_DESC, $start = 0, $num = 18, $status = GEC::UNRECYCLE, $format = Model::LIST_AS_OBJ){
        $objs = [];
		$rdo = self::getRDO();
		$rdo->requiring()->orderby(false);
		if(is_string($GROUPCODE)){
			$rdo->where('GROUPCODE', $GROUPCODE);
		}
		if($status){
			$rdo->where('KEY_IS_RECYCLED', 2 - $status);
		}
		if (is_numeric($num)){
			if (is_numeric($start)){
				$rdo->take($num, $start);
			}else{
				$rdo->take($num);
			}
		}else{
			$rdo->take(0);
		}
        if(is_array($orderby)){
            foreach ($orderby as $order) {
                if(isset($order[0])&&isset($order[1])){
                    $rdo->orderby((string)$order[0], !!$order[1]);
                }
            }
        }
        $result = $rdo->select();
        if($result){
			if($format===Model::LIST_AS_ARR){
                return array_map(array('CM\SPCLite', 'restoreroot'), $result->toArray());
            }
            $pdos = $result->getPDOStatement();
            while($pdos&&$data = $pdos->fetch(PDO::FETCH_ASSOC)){
                $objs[] = (new static)->build($data, true);
            }
        }
        return $objs;
	}

	public static function remove($require, $status = 1){
		$objs = self::query($require);
        $successed = [];
		foreach($objs as $obj){
            if($obj->recycle($status)){
                $successed[] = $obj;
            }
		}
		return $successed;
	}

	public function toggleStatus($status){
		$status = intval(!!$status);
		$this->data['KEY_STATE'] =	$status;
		if($this->posted){
			return $this->save();
		}
		return $this;
	}

	public function recycle($status = 1){
		$status = intval(!!$status);
		$this->data['KEY_IS_RECYCLED'] =	$status;
		if($this->posted){
			return $this->save();
		}
		return $this;
	}

	public function view(){
		$counter = new Counter(self::$table);
		if($counter->setKey('ID')->point($this->data['ID'])->add()){
			return true;
		}
		return false;
	}
}
