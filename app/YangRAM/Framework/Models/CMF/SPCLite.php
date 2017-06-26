<?php
namespace CM;

use PDO;
use Status;
use Tangram\NIDO\DataObject;
use Model;
use Storage;
use RDO;
use CM\SPC\Preset;
use CM\SPC\Category;

/**
 *	Special Use Content Light Model
 *	专用内容轻模型
 *  是一个忽略不同预设字段差异的精简的单一标准模型
 */
final class SPCLite extends ContentModel_BC {
	const
	SEARCH_ALL = 0,
	SEARCH_USE = 1,
	SEARCH_CAT = 2,

	ALL = 0,
	RECYCLED = 1,
	UNRECYCLED = 2,
	PUBLISHED = 3,
	UNPUBLISHED = 4,
	ISTOP = 5,
	NOTOP = 6,
	POSTED = 7,

	ID_DESC = [['ID', true, DataObject::SORT_REGULAR]],
	ID_ASC = [['ID', false, DataObject::SORT_REGULAR]],
	CTIME_DESC = [['ID', true, DataObject::SORT_REGULAR]],
	CTIME_ASC = [['ID', false, DataObject::SORT_REGULAR]],
	PUBTIME_DESC = [['PUBTIME', true, DataObject::SORT_REGULAR]],
	PUBTIME_ASC = [['PUBTIME', false, DataObject::SORT_REGULAR]],
	MTIME_DESC = [['KEY_MTIME', true, DataObject::SORT_REGULAR]],
	MTIME_ASC = [['KEY_MTIME', false, DataObject::SORT_REGULAR]],
	HIT_DESC = [['KEY_COUNT', true, DataObject::SORT_REGULAR]],
	HIT_ASC = [['KEY_COUNT', false, DataObject::SORT_REGULAR]],
	RANK_DESC = [['RANK', true, DataObject::SORT_REGULAR]],
	RANK_ASC = [['RANK', false, DataObject::SORT_REGULAR]],
	LEVEL_DESC = [['LEVEL', true, DataObject::SORT_REGULAR]],
	LEVEL_ASC = [['LEVEL', false, DataObject::SORT_REGULAR]],
	TITLE_DESC = [['TITLE', true, DataObject::SORT_REGULAR]],
	TITLE_ASC = [['TITLE', false, DataObject::SORT_REGULAR]],
	TITLE_DESC_GBK = [['TITLE', true, DataObject::SORT_CONVERT_GBK]],
	TITLE_ASC_GBK = [['TITLE', false, DataObject::SORT_CONVERT_GBK]],

	SORTKEYS = ['ID', 'PUBTIME', 'KEY_MTIME', 'RANK', 'TITLE'];
	
	private static $memory, $rdo, $storage;

	protected static
	$ca_path = PATH_DAT_CNT.'',
	$defaults = [
		'ID'				=>	NULL,
		'SET_ALIAS'			=>	'articles',
		'CAT_ID'			=>	0,
		'TITLE'				=>	'',
		'TAGS'				=>	'',
		'DESCRIPTION'		=>	'',
		'PUBTIME'		=>	NULL,
		'RANK'				=>	6,
		'LEVEL'				=>	0,
		'IS_TOP'			=>	0,
		'KEY_MTIME'		=>	DATETIME,
		'KEY_STATE'		=>	1,
		'KEY_COUNT'	=>	0,
		'KEY_IS_RECYCLED'		=>	0,
		'USR_ID'			=>	0
    ];

	private static function checkStatus($post){
        if($post["KEY_STATE"]==1){
            if(empty($post["PUBTIME"])){
                $post["PUBTIME"] = DATETIME;
            }
            $post["KEY_STATE"] = '1';
        }else{
            unset($post["PUBTIME"]);
            $post["KEY_STATE"] = '0';
        }
        return $post;
    }

	private static function checkSubmitData($post){
        $post = self::checkStatus($post);
		$form = array_map(function($val){
            $val = SPCLite::replaceroot(SPCLite::checkContentPager($val));
            return htmlspecialchars($val, ENT_COMPAT, 'UTF-8');
        }, $post);
        $intersect = array_intersect_key($form, self::$defaults);
        $intersect["KEY_MTIME"] = DATETIME;
        $intersect["TAGS"] = trim(strip_tags($intersect["TAGS"]));
        $intersect["ID"] = $post["ID"];
        if($intersect["TAGS"] !== ''){
            $tags = explode(',', $intersect["TAGS"]);
            $intersect["TAGS"] = join(",", $tags);
            SPC\Tag::posttags($tags, $intersect["ID"], $post['SET_ALIAS']);
        }
		$desc = trim(preg_replace('/\s+/', ' ', preg_replace('/[\n\r\t]+/', '', str_replace('{{@page_break}}', '', $intersect["DESCRIPTION"]))));
		$intersect["DESCRIPTION"] = $desc;
        return $intersect;
	}

	private static function init(){
		if(!self::$rdo){
			self::$memory = [];
			self::$rdo = new RDO;
			self::$storage = new Storage(static::$ca_path, Storage::JSN, true);
		}
		self::$rdo->using(DB_CNT.'in_special_use');
	}

	public static function byId($id, $published = false){
		self::init();
		$content = new self($id);
		if($content->ID&&($published==false||($content->KEY_STATE==1&&$content->KEY_IS_RECYCLED==0))){
			return $content;
		}
		return NULL;
	}

	public static function count($preset = NULL, $category = NULL, $status = SPCLite::UNRECYCLED) {
		if(is_string($preset)||is_numeric($category)){
			self::init();
			$rdo = self::$rdo;
			$rdo->requiring();
			if(is_string($preset)){
				$rdo->where('SET_ALIAS', $preset);
			}
			if(is_numeric($category)){
				$rdo->where('CAT_ID', $category);
			}
			switch($status){
				case SPCLite::RECYCLED:
				$rdo->where('KEY_IS_RECYCLED', 1);
				break;
				case SPCLite::UNRECYCLED:
				$rdo->where('KEY_IS_RECYCLED', 0);
				break;
				case SPCLite::PUBLISHED:
				$rdo->where('KEY_IS_RECYCLED', 0);
				$rdo->where('KEY_STATE', 1);
				break;
				case SPCLite::UNPUBLISHED:
				$rdo->where('KEY_IS_RECYCLED', 0);
				$rdo->where('KEY_STATE', 0);
				break;
				case SPCLite::ISTOP:
				$rdo->where('KEY_IS_RECYCLED', 0);
				$rdo->where('IS_TOP', 1);
				break;
				case SPCLite::NOTOP:
				$rdo->where('KEY_IS_RECYCLED', 0);
				$rdo->where('IS_TOP', 0);
				break;
				case SPCLite::POSTED:
				$rdo->where('KEY_IS_RECYCLED', 0);
				$rdo->where('KEY_STATE', -1);
				break;
			}
			return $rdo->orderby(false)->take(0)->count();
		}else{
			return 0;
		}
	}

	public static function all(){
		self::init();
		$objs = [];
        $result = self::$rdo->requiring()->take(0)->orderby(false)->select();
        if($result){
            $pdos = $result->getPDOStatement();
            while($pdos&&$data = $pdos->fetch(PDO::FETCH_ASSOC)){
				self::$memory[$data['ID']] = $data;
                $objs[] = new self($data['ID']);
            }
        }
        return $objs;
	}

	public static function sendQS2RDO($rdo, $preset = NULL, $category = NULL, $status = SPCLite::UNRECYCLED, array $orderby = SPCLite::ID_DESC, $start = 0, $num = 18){
		$rdo->requiring();
		if(is_string($preset)||(is_numeric($category)&&$category!=0)){
			if(is_string($preset)){
				$rdo->where('SET_ALIAS', $preset);
			}
			if(is_numeric($category)){
				$rdo->where('CAT_ID', $category);
			}
		}
		switch($status){
			case SPCLite::RECYCLED:
			$rdo->where('KEY_IS_RECYCLED', 1);
			break;
			case SPCLite::UNRECYCLED:
			$rdo->where('KEY_IS_RECYCLED', 0);
			break;
			case SPCLite::PUBLISHED:
			$rdo->where('KEY_IS_RECYCLED', 0);
			$rdo->where('KEY_STATE', 1);
			break;
			case SPCLite::UNPUBLISHED:
			$rdo->where('KEY_IS_RECYCLED', 0);
			$rdo->where('KEY_STATE', 0);
			break;
			case SPCLite::ISTOP:
			$rdo->where('KEY_IS_RECYCLED', 0);
			$rdo->where('IS_TOP', 1);
			break;
			case SPCLite::NOTOP:
			$rdo->where('KEY_IS_RECYCLED', 0);
			$rdo->where('IS_TOP', 0);
			break;
			case SPCLite::POSTED:
			$rdo->where('KEY_IS_RECYCLED', 0);
			$rdo->where('KEY_STATE', -1);
			break;
		}
		
		foreach ($orderby as $order) {
            if(isset($order[0])&&isset($order[1])){
            	$rdo->orderby((string)$order[0], !!$order[1]);
        	}
		}
		return $rdo->take($num, $start);
	}

	public static function getList($preset = NULL, $category = NULL, $status = SPCLite::UNRECYCLED, array $orderby = SPCLite::ID_DESC, $start = 0, $num = 18, $format = Model::LIST_AS_OBJ){
		self::init();
		$objs = [];
		
		$result = self::sendQS2RDO(self::$rdo, $preset, $category, $status, $orderby, $start, $num)->select();
    	if($result){
			if($format===Model::LIST_AS_ARR){
                return array_map(array('CM\SPCLite', 'restoreroot'), $result->toArray());
            }
            $pdos = $result->getPDOStatement();
            while($pdos&&$data = $pdos->fetch(PDO::FETCH_ASSOC)){
                self::$memory[$data['ID']] = $data;
				$objs[] = new self($data['ID']);
            }
        }
        return $objs;
	}	

	public static function published($class, $range = 0, array $orderby = ['PUBTIME', false], $format = Model::LIST_AS_ARR){
		if(in_array($orderkey[0], SPCLite::SORTKEYS)){
			$array = self::$storage->setBefore('spcnts/spcpubs/'.$class.'/')->take($orderby[0]);
		}else{
			$array = false;
		}
		if(!$array){
			if(is_numeric($class)&&$class!=0){
				$array = self::query("`CAT_ID` = $class AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0", [[$orderby[0], true]], 0, $format);
				self::$storage->store($class, $array);
			}elseif(is_string($class)){
				$array = self::query("`SET_ALIAS` = '$class' AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0", [[$orderby[0], true]], 0, $format);
				self::$storage->store($orderby[0], $array);
			}else{
				return [];
			}
		}
		if($orderby[1]===false){
			$array = array_reverse($array);
		}
		if($range){
            if(is_numeric($range)){
                $start = 0;
                $length = $range;
            }elseif(is_array($range)){
                $start = intval($range[0]);
                $length = intval($range[1]);
            }
			$array = array_slice($array, $start, $length);
        }
		return $array;
	}

	public static function of($class, array $orderby = SPCLite::ID_ASC, $range = 0, $format = Model::LIST_AS_OBJ){
		if(is_numeric($class)){
			return self::query("`CAT_ID` = $class", $orderby, $range, $format);
		}elseif(is_string($class)){
			return self::query("`SET_ALIAS` = '$class'", $orderby, $range, $format);
		}
		return [];
	}

	public static function byCat($cat_id, array $orderby = SPCLite::ID_ASC, $range = 0, $format = Model::LIST_AS_OBJ){
		if(is_numeric($cat_id)){
			return self::query("`CAT_ID` = $cat_id", $orderby, $range, $format);
		}
		return [];
	}

	public static function countByTag($tag, $class = NULL){
		if(is_string($tag)){
			$rdo = new RDO;
			$rdo->using(DB_CNT.'map_spctags')->where('tag', $tag);
			if(is_numeric($class)){
				$cat = Category::identity($class);
				$preset = $cat->preset();
				$preset_alias = $preset->alias;
				if($preset_alias){
					$rdo->where('set_alias', $preset_alias);
				}else{
					return [];
				}
			}elseif(is_string($class)){
				$rdo->where('set_alias', $class);
			}
			$result = $rdo->select('cnt_id');
			if($result){
				$array1 = $result->toArray();
				$array2 = [];
				foreach($array1 as $row){
					$array2[] = $row['cnt_id'];
				}
				return $rdo->using(DB_CNT.'in_special_use')->requiring("`ID` in (".join(',', $array2).") AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0")->orderby(false)->take(0)->count();
			}
		}
		return 0;
	}

	public static function byTag($tag, $class = NULL, array $orderby = SPCLite::ID_ASC, $range = 0, $format = Model::LIST_AS_OBJ){
		if(is_string($tag)){
			$rdo = new RDO;
			$rdo->using(DB_CNT.'map_spctags')->where('tag', $tag);
			if(is_numeric($class)){
				$cat = Category::identity($class);
				$preset = $cat->preset();
				$preset_alias = $preset->alias;
				if($preset_alias){
					$rdo->where('set_alias', $preset_alias);
				}else{
					return [];
				}
			}elseif(is_string($class)){
				$rdo->where('set_alias', $class);
			}
			$result = $rdo->select('cnt_id');
			if($result){
				$array1 = $result->toArray();
				$array2 = [];
				foreach($array1 as $row){
					$array2[] = $row['cnt_id'];
				}
				return self::query("`ID` in (".join(',', $array2).") AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0");
			}
		}
		return [];
	}

	public static function ByUse($type, array $orderby = SPCLite::ID_ASC, $range = 0, $format = Model::LIST_AS_OBJ){
		if(is_numeric($type)){
			if($SET = Preset::id($type)){
                $type = $SET->alias;
            }else{
				return [];
			}
		}
		if(preg_match('/^\w+$/', $type)){
			return self::query("`SET_ALIAS` = '$type'", $orderby, $range, $format);
		}
		return [];
	}

	public static function unclassified($type, array $orderby = SPCLite::ID_ASC, $range = 0){
		if(preg_match('/^\w+$/', $type)){
			return self::query("`SET_ALIAS` = '$type' AND `CAT_ID` = 0", $orderby, $range);
		}
		return [];
	}

	public static function before($time, $range = SPCLite::SEARCH_ALL, $class = NULL){
		$time = date('Y-m-d H:i:s', strtotime($time));
		switch($range){
			case 0:
			return self::query("`PUBTIME` <= '$this->PUBTIME' AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0", SPCLite::PUBTIME_DESC);

			case 1:
			return self::query("`PUBTIME` <= '$this->PUBTIME' AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0 AND `SET_ALIAS` = '$class'", SPCLite::PUBTIME_DESC);

			case 2:
			return self::query("`PUBTIME` <= '$this->PUBTIME' AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0 AND `CAT_ID` = $class", SPCLite::PUBTIME_DESC);
		}
		return [];
	}

	public static function after($time, $range = SPCLite::SEARCH_ALL, $class = NULL){
		$time = date('Y-m-d H:i:s', strtotime($time));
		switch($range){
			case 0:
			return self::query("`PUBTIME` >= '$this->PUBTIME' AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0", SPCLite::PUBTIME_ASC);

			case 1:
			return self::query("`PUBTIME` >= '$this->PUBTIME' AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0 AND `SET_ALIAS` = '$class'", SPCLite::PUBTIME_ASC);

			case 2:
			return self::query("`PUBTIME` >= '$this->PUBTIME' AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0 AND `CAT_ID` = $class", SPCLite::PUBTIME_ASC);
		}
		return [];
	}

	public static function query ($require = "0" , array $orderby = SPCLite::ID_ASC, $range = 0, $format = Model::LIST_AS_OBJ){
        self::init();
        $objs = [];
		$result = self::querySelect(self::$rdo, $require, $orderby, $range);
        if($result){
			if($format===Model::LIST_AS_ARR){
                return array_map(array('CM\SPCLite', 'restoreroot'), $result->toArray());
            }
            $pdos = $result->getPDOStatement();
            while($pdos&&$data = $pdos->fetch(PDO::FETCH_ASSOC)){
                self::$memory[$data['ID']] = $data;
                $objs[] = new self($data['ID']);
            }
        }
        return $objs;
    }

	public static function moveto($require, $cat = 0){
        $objs = self::query($require);
		foreach($objs as $obj){
			$obj->CAT_ID = $cat;
			$obj->save();
		}
		return $objs;
	}

	public static function build(array $data, $type){
		$obj = new self(0);
		return $obj->buildByData($data, $type);
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

	private $_hash, $posted = NULL;

	public function __construct($id){
		self::init();
		if(isset(self::$memory[$id])){
			$this->data = self::$memory[$id];
			$this->posted = $this->data;
		}else{
			$this->buildById($id);
		}
	}

	protected function buildById($id){
		if(is_numeric($id)){
			if($id){
				if($data = self::$storage->setBefore('spcnts/bases/')->take($id)){
                	$this->data = array_map(array('CM\SPCLite', 'restoreroot'), $data);
					$this->posted = $this->data;
					self::$memory[$id] = $this->data;
					$this->_hash = $this->data['ID'];
            	}else{
					$result = self::$rdo->requiring()->where('ID', $id)->take(1)->select();
					if($result&&$data = $result->getRow()){
						$this->data = array_map(array('CM\SPCLite', 'restoreroot'), $data);
						$this->posted = $this->data;
						self::$storage->store($id, $data);
						self::$memory[$id] = $this->data;
						$this->_hash = $this->data['ID'];
					}else{
						$this->data = self::$defaults;
					}
				}
			}else{
				$this->data = self::$defaults;
			}
		}else{
			new Status(703.4, 'Using Module Error', 'CM\SPCLite::byId Must be given a numeric.', true);
		}
	}

	protected function buildByData($data){
        $this->xml = NULL;
        if(is_array($data)){
            $this->data = array_merge(static::$defaults, array_intersect_key($data, static::$defaults));
        }
        return $this;
    }

	public function extend(){
		if($id = $this->data['ID']){
			$xtnd = SPC::extended($this->data['ID'], $this->data['SET_ALIAS']);
			$this->data = array_merge($this->data, $xtnd);
		}
		return $this;
	}

	public function category(){
		if($this->CAT_ID){
			return Category::byId($this->CAT_ID);
		}
		return NULL;
	}

	public function preset(){
		if($this->data['SET_ALIAS']){
			return Preset::alias($this->data['SET_ALIAS']);
		}
		return NULL;
	}

	public function prev($range = SPCLite::SEARCH_ALL){
		$array = [];
		if($id = $this->data['ID']){
			switch($range){
				case SPCLite::SEARCH_USE:
				$array = self::query("`ID` < $id AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0 AND `SET_ALIAS` = '$this->SET_ALIAS'", SPCLite::ID_DESC, 1);
				break;
				case SPCLite::SEARCH_CAT:
				$array = self::query("`ID` < $id AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0 AND `SET_ALIAS` = '$this->SET_ALIAS' AND `CAT_ID` = $this->CAT_ID", SPCLite::ID_DESC, 1);
				break;
				default:
				$array = self::query("`ID` < $id AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0", SPCLite::ID_DESC, 1);
			}
		}
		if(isset($array[0])){
			return $array[0];
		}
		return NULL;
	}

	public function next($range = SPCLite::SEARCH_ALL){
		$array = [];
		if($id = $this->data['ID']){
			switch($range){
				case SPCLite::SEARCH_USE:
				$array = self::query("`ID` > $id AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0 AND `SET_ALIAS` = '$this->SET_ALIAS'", SPCLite::ID_ASC, 1);
				break;
				case SPCLite::SEARCH_CAT:
				$array = self::query("`ID` > $id AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0 AND `SET_ALIAS` = '$this->SET_ALIAS' AND `CAT_ID` = $this->CAT_ID", SPCLite::ID_ASC, 1);
				break;
				default:
				$array = self::query("`ID` > $id AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0", SPCLite::ID_ASC, 1);
			}
		}
		if(isset($array[0])){
			return $array[0];
		}
		return NULL;
	}

	public function early($range = SPCLite::SEARCH_ALL){
		$array = [];
		if($id = $this->data['ID']){
			switch($range){
				case SPCLite::SEARCH_USE:
				$array = self::query("`PUBTIME` <= '$this->PUBTIME' AND `ID` <> $id AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0 AND `SET_ALIAS` = '$this->SET_ALIAS'", SPCLite::PUBTIME_DESC, 1);
				break;
				case SPCLite::SEARCH_CAT:
				$array = self::query("`PUBTIME` <= '$this->PUBTIME' AND `ID` <> $id AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0 AND `SET_ALIAS` = '$this->SET_ALIAS' AND `CAT_ID` = $this->CAT_ID", SPCLite::PUBTIME_DESC, 1);
				break;
				default:
				$array = self::query("`PUBTIME` <= '$this->PUBTIME' AND `ID` <> $id AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0", SPCLite::PUBTIME_DESC, 1);
			}
		}
		if(isset($array[0])){
			return $array[0];
		}
		return NULL;
	}

	public function later($range = SPCLite::SEARCH_ALL){
		$array = [];
		if($id = $this->data['ID']){
			switch($range){
				case SPCLite::SEARCH_USE:
				$array = self::query("`PUBTIME` >= '$this->PUBTIME' AND `ID` <> $id AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0 AND `SET_ALIAS` = '$this->SET_ALIAS'", SPCLite::PUBTIME_ASC, 1);
				break;
				case SPCLite::SEARCH_CAT:
				$array = self::query("`PUBTIME` >= '$this->PUBTIME' AND `ID` <> $id AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0 AND `SET_ALIAS` = '$this->SET_ALIAS' AND `CAT_ID` = $this->CAT_ID", SPCLite::PUBTIME_ASC, 1);
				break;
				default:
				$array = self::query("`PUBTIME` >= '$this->PUBTIME' AND `ID` <> $id AND `KEY_STATE` = 1 AND `KEY_IS_RECYCLED` = 0", SPCLite::PUBTIME_ASC, 1);
			}
		}
		if(isset($array[0])){
			return $array[0];
		}
		return NULL;
	}

	public function put($data){
        return $this->buildByData($data);
    }

	public function cln(){
		return false;
	}

	public function save(){
        if($this->posted){
			if(empty($this->data['ID'])){
                return false;
            }
			$rdo = self::$rdo;
			$this->data = self::checkSubmitData($this->data);
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
        }
        self::$storage->setBefore('spcnts/bases/')->store($this->_hash);
		foreach(SPCLite::SORTKEYS as $sort){
			self::$storage->setBefore('spcnts/spcpubs/'.$this->data['SET_ALIAS'].'/')->store($sort);
			if($this->data['CAT_ID']){
				self::$storage->setBefore('spcnts/spcpubs/'.$this->data['CAT_ID'].'/')->store($sort);
			}
		}
        return $this;
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

	public function destroy(){
		if($this->posted){
			if(self::$rdo->requiring()->where('ID', $this->data['ID'])->delete()){
				self::$storage->setBefore('spcnts/bases/')->store($this->_hash);
				return true;
			}
			return false;
		}
		return true;
	}

	public function view(){
		$counter = new Counter(self::$table);
		if($counter->setKey('ID')->point($this->data['ID'])->add()){
			return true;
		}
		return false;
	}
}