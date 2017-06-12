<?php
namespace CM;

use PDO;
use Status;
use System\NIDO\DataObject;
use Storage;
use RDO;
use Model;
use System\ORM\RDOAdvanced;
use CM\SPC\Preset;
use AF\Models\Certificates\Passport;

/**
*  Model Of Formatted Data In Special Use
*  专有用途格式化数据模型
*  预设内容，专用内容
*  提供针对专用内容进行增删改查的接口
*/
final class SPC extends BaseModel {
    private static
    $memory = [],
    $extends = [],
    $ca_path = PATH_DAT_CNT.'',
    $storage,
    $tables = [],
    $capaths = [],
    $rdo;

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

    private static function checkPostData($post){
        $post = self::checkStatus($post);
        $defaults = SPC\Defaults::byType($post['SET_ALIAS'])->values('defaults');
        $extends = SPC\Defaults::byType($post['SET_ALIAS'])->values('extends');
        $form = array_map(function($val){
            return htmlspecialchars($val, ENT_COMPAT, 'UTF-8');
        }, $post);
        $intersect_base = array_intersect_key($form, $defaults);
        $intersect_xtnd = array_intersect_key($form, $extends);
        $intersect_base["KEY_MTIME"] = DATETIME;
        $intersect_base["TAGS"] = trim(strip_tags($intersect_base["TAGS"]));
        $intersect_base = array_merge($defaults, $intersect_base);
        $intersect_xtnd = array_merge($extends, $intersect_xtnd);
        $intersect_base["SET_ALIAS"] = $post['SET_ALIAS'];
        $intersect_base["USR_ID"] = Passport::instance()->uid;
        $intersect_xtnd["KEY_CTIME"] = DATETIME;
        return self::checkSEO($intersect_base, $intersect_xtnd);
    }

    private static function checkSEO($intersect_base, $intersect_xtnd){
        $presetinfo = Preset::alias($intersect_base['SET_ALIAS'])->toArray();
        switch($presetinfo["basic_type"]){
            case 'msgs':
            case 'arti':
            case 'news':
                $preparation = isset($intersect_xtnd["CONTENT"]) ? $intersect_xtnd["CONTENT"] : '';
                break;
            case 'wiki':
                $preparation = isset($intersect_xtnd["MEANING"]) ? $intersect_xtnd["MEANING"] : '';
                break;
            case 'item':
                $preparation = isset($intersect_xtnd["DETAILS"]) ? $intersect_xtnd["DETAILS"] : '';
                break;
            case 'resm':
                $preparation = isset($intersect_xtnd["RESUME"]) ? $intersect_xtnd["RESUME"] : '';
                break;
            default:
                $preparation = isset($intersect_base["TITLE"]) ? $intersect_base["TITLE"] : '';
        }
        $desc = trim(preg_replace('/\s+/', ' ', preg_replace('/[\n\r\t]+/', '', strip_tags(htmlspecialchars_decode($intersect_base["DESCRIPTION"])))));
        $kw = trim(preg_replace('/\s+/', ' ', preg_replace('/[\n\r\t]+/', '', trim(strip_tags(htmlspecialchars_decode($intersect_xtnd["KEYWORDS"]))))));

        $intersect_base["DESCRIPTION"] = $desc!='' ? $desc : trim(preg_replace('/\s+/', ' ', preg_replace('/[\n\r\t]+/', '', strip_tags(htmlspecialchars_decode($preparation)))));
        $intersect_base["DESCRIPTION"] = mb_substr($intersect_base["DESCRIPTION"], 0, 128, "utf-8");  

        $intersect_xtnd["KEYWORDS"] = $kw!='' ? $kw : trim(preg_replace('/\s+/', ' ', preg_replace('/[\n\r\t]+/', '', $intersect_base["TAGS"])));
        $intersect_xtnd["KEYWORDS"] = $kw!='' ? $kw : trim(preg_replace('/\s+/', ' ', preg_replace('/[\n\r\t]+/', '', strip_tags(htmlspecialchars_decode($intersect_base["TITLE"])))));
        $intersect_xtnd["KEYWORDS"] = mb_substr($intersect_xtnd["KEYWORDS"], 0, 24, "utf-8");
        return [$intersect_base, $intersect_xtnd];
    }

    private static function checkUpdateData($post, $posted){
        $post = self::checkStatus($post);
        $defaults = SPC\Defaults::byType($post['SET_ALIAS'])->values('defaults');
        $extends = SPC\Defaults::byType($post['SET_ALIAS'])->values('extends');
        $form = array_map('htmlspecialchars', $post);
        $intersect_base = array_intersect_key($form, $defaults);
        $intersect_xtnd = array_intersect_key($form, $extends);
        $intersect_base["KEY_MTIME"] = DATETIME;
        $intersect_base["ID"] = $posted["ID"];
        $intersect_base["SET_ALIAS"] = $posted['SET_ALIAS'];
        $intersect_base["USR_ID"] = $posted['USR_ID'];
        $intersect_xtnd["KEY_CTIME"] = $posted['KEY_CTIME'];
        return self::checkSEO($intersect_base, $intersect_xtnd);
    }
    
    private static function init(){
        if(!self::$storage){
            self::$storage = new Storage(static::$ca_path, Storage::JSN, true);
            self::$rdo = new RDOAdvanced();
        }
    }
    
    public static function byId($id, $published = false){
        if(isset(self::$memory[$id])){
            return $obj->build(self::$memory[$id], true);
        }
        if(is_numeric($id)){
            $base = SPCLite::byId($id, $published);
            if($base && ($xtnd = self::extended($id, $base->SET_ALIAS))){
                $data = array_merge($base->toArray(), $xtnd);
                self::$memory[$id] = $data;
                $obj = new static();
                return $obj->build($data, true);
            }
            $base && $base->destroy();
            return NULL;
        }else{
            new Status(703.4, 'Using Module Error', 'CM\SPC::byId Must be given a numeric.', true);
        }
    }
    
    public static function extended($id, $set_alias){
        self::init();
        if($data = self::$storage->setBefore('spcnts/xtnds/')->take($id)){
            return $data;
        }
        $result = self::$rdo->using(DB_CNT.'of_'.$set_alias)->requiring()->where('CNT_ID', $id)->select();
        if($result&&$data = $result->getRow()){
            self::$storage->store($id, $data);
            return $data;
        }
        return false;
    }

    public static function count($preset = NULL, $category = NULL, $status = SPCLite::UNRECYCLED) {
		return SPCLite::count($preset, $category, $status);
	}

	public static function all(){
        $bases = SPCLite::all();
        $objs = [];
        foreach($bases as $base){
            if($xtnd = self::extended($base->ID, $base->SET_ALIAS)){
                $obj = new static();
                $objs[] = $obj->build(array_merge($base->toArray(), $xtnd), true);
            }
        }
		return $objs;
	}

    private static function getCacheHash($preset, $category, $orderkey, $type){
        if(in_array($orderkey, SPCLite::SORTKEYS)){
            self::init();
            if(is_numeric($category)&&$category!=0){
                self::$storage->setBefore($type.'/categories/'.$category.'/');
                return $orderkey;
            }elseif(is_string($preset)){
                if($category===0){
                    self::$storage->setBefore($type.'/presets/unclassified/');
                    return $orderkey;
                }else{
                    self::$storage->setBefore($type.'/presets/allcategories//');
                    return $orderkey;
                }
            }
        }
        return NULL;
    }

    public static function getList($preset = NULL, $category = NULL, $status = SPCLite::UNRECYCLED, array $orderby = SPCLite::ID_DESC, $start = 0, $num = 18, $format = Model::LIST_AS_OBJ, array $notnulls = []){
        if(count($notnulls)&&is_string($preset)){
            self::init();
            $objs = [];
            
            $rdo = SPCLite::sendQS2RDO(self::$rdo->using(RDO::multtable([
                'table' =>  DB_CNT.'of_'.$preset,
                'field' =>  'CNT_ID',
                'as'    =>  'A'
            ], [
                'table' =>  DB_CNT.'in_special_use',
                'field' =>  'ID',
                'as'    =>  'B'
            ])),
            $preset, $category, $status, $orderby, $start, $num);
            foreach ($notnulls as $field) {
                $rdo->where($field, '', '<>');
    	    }
            $result = $rdo->select();
            
            if($result){
			    if($format===Model::LIST_AS_ARR){
                    return $result->toArray();
                }
                $pdos = $result->getPDOStatement();
                while($pdos&&$data = $pdos->fetch(PDO::FETCH_ASSOC)){
                    $obj = new static();
                    $objs[] = $obj->build($data, true);
                }
            }
            return $objs;
        }
        
        
        if($status === SPCLite::PUBLISHED){
            $hash = self::getCacheHash($preset, $category, $orderby[0], 'spclist');
        }
        elseif($status === SPCLite::ISTOP){
            $hash = self::getCacheHash($preset, $category, $orderby[0], 'spctops');
        }
        elseif($status === SPCLite::NOTOP){
            $hash = self::getCacheHash($preset, $category, $orderby[0], 'spcnmls');
        }else{
            $hash = NULL;
        }
        if($hash){
            if($rows = self::$storage->take($hash)){
                if($format===Model::LIST_AS_ARR){
                    return $rows;
                }else{
                    $objs = [];
                    foreach($rows as $row){
                        $obj = new static();
                        $objs[] = $obj->build($row, true);
                    }
                    return $objs;
                }
            }
        }
        $bases = SPCLite::getList($preset, $category, $status, $orderby, $start, $num, Model::LIST_AS_ARR, $notnulls);
        $objs = [];
        if($format===Model::LIST_AS_ARR){
            foreach($bases as $base){
                if($xtnd = self::extended($base['ID'], $base['SET_ALIAS'])){
                    $objs[] = array_merge($base, $xtnd);
                }
                if($hash){
                    self::$storage->store($hash, $objs);
                }
            }
        }else{
            $rows = [];
            foreach($bases as $base){
                if($xtnd = self::extended($base['ID'], $base['SET_ALIAS'])){
                    $obj = new static();
                    $row = array_merge($base, $xtnd);
                    $rows[] = $row;
                    $objs[] = $obj->build($row, true);
                }
            }
            if($hash){
                self::$storage->store($hash, $rows);
            }
        }
		return $objs;
    }

    public static function byTag($tag, $class = NULL, array $orderby = SPCLite::ID_ASC, $range = 0, $format = Model::LIST_AS_OBJ){
		$bases = SPCLite::byTag($tag, $class, $orderby, $range, Model::LIST_AS_ARR);
        $objs = [];
        if($format===Model::LIST_AS_ARR){
            foreach($bases as $base){
                if($xtnd = self::extended($base['ID'], $base['SET_ALIAS'])){
                    $objs[] = array_merge($base, $xtnd);
                }
            }
        }else{
            foreach($bases as $base){
                if($xtnd = self::extended($base['ID'], $base['SET_ALIAS'])){
                    $obj = new static();
                    $objs[] = $obj->build(array_merge($base, $xtnd), true);
                }
            }
        }
		return $objs;
	}

    public static function create(array $post){
        $inserts = self::checkPostData($post);
        self::init();
        #使用事务
        #开启事务
        self::$rdo->begin();
        if(self::$rdo->using(DB_CNT.'in_special_use')->insert($inserts[0])){
            $inserts[1]["CNT_ID"] = self::$rdo->lastInsertId('ID');
            if(self::$rdo->using(DB_CNT.'of_'.$post['SET_ALIAS'])->insert($inserts[1])){
                if($inserts[0]["TAGS"] !== ''){
                    $tags = explode(',', $inserts[0]["TAGS"]);
                    $intersect_base["TAGS"] = join(",", $tags);
                    SPC\Tag::posttags($tags, $inserts[1]["CNT_ID"], $post['SET_ALIAS']);
                }
                #提交事务
                self::$rdo->commit();
                return self::byId($inserts[1]["CNT_ID"]);
            }
        }
        #回滚事务
        self::$rdo->rollBack();
        return false;
	}

    public static function moveto($require, $cat = 0){
        $bases = SPCLite::moveto($require, $cat);
		$objs = [];
        foreach($bases as $base){
            if($xtnd = self::extended($base->ID, $base->SET_ALIAS)){
                $obj = new static();
                $objs[] = $obj->build(array_merge($base->toArray(), $xtnd), true);
            }
        }
		return $objs;
	}

    public static function remove($require, $status = 1){
        $bases = SPCLite::remove($require, $status);
		$objs = [];
        foreach($bases as $base){
            if($xtnd = self::extended($base->ID, $base->SET_ALIAS)){
                $obj = new static();
                $objs[] = $obj->build(array_merge($base->toArray(), $xtnd), true);
            }
        }
		return $objs;
	}

	public static function delete($require){
        self::init();
        self::$rdo->begin();
		$bases =SPCLite::query($require);
		foreach($bases as $base){
            if($xtnd = self::extended($base->ID, $base->SET_ALIAS)){
                $obj = new static();
                $obj->build(array_merge($base->toArray(), $xtnd), true)->destroy();
            }else{
                self::$rdo->rollBack();
                return false;
            }
		}
        self::$rdo->commit();
		return true;
	}

    private $_hash, $posted = NULL;
    
    private function __construct(){
        self::init();
    }
    
    protected function build($data, $posted = false){
        $this->data = $data;
        if(isset($this->data['ID'])){
            $this->_hash = $this->data['ID'];
        }
        if($posted){
            $this->posted = $this->data;
        }
        $this->xml = NULL;
        $this->readonly = false;
        return $this;
    }

    public function contexts(){
        $base = new SPCLite($this->data['ID']);

        return [
            'Previous' => $base->prev(SPCLite::SEARCH_ALL),
    		'Previous_InSameUsage' => $base->prev(SPCLite::SEARCH_USE),
            'Previous_InSameCategory' => $base->prev(SPCLite::SEARCH_CAT),

            'Next' => $base->next(SPCLite::SEARCH_ALL),
	    	'Next_InSameUsage' => $base->next(SPCLite::SEARCH_USE),
            'Next_InSameCategory' => $base->next(SPCLite::SEARCH_CAT),
        
            'Earlier' => $base->early(SPCLite::SEARCH_ALL),
            'Earlier_InSameUsage' => $base->early(SPCLite::SEARCH_USE),
            'Earlier_InSameCategory' => $base->early(SPCLite::SEARCH_CAT),

            'Later' => $base->later(SPCLite::SEARCH_ALL),
            'Later_InSameUsage' => $base->later(SPCLite::SEARCH_USE),
            'Later_InSameCategory' => $base->later(SPCLite::SEARCH_CAT),
        ];
    }

    public function put($data){
        return $this->build($data);
    }

	public function cln(){
		return NULL;
	}

	public function save(){
        if($this->posted){
			if(empty($this->data['ID'])){
                return false;
            }
			$rdo = self::$rdo;
            $rdo->begin();
            $base = new SPCLite($this->data['ID']);
            $update = self::checkUpdateData($this->data, $this->posted);
            if($base&&($a = $base->put($update[0])->save())){
                $diff = $this->diff($update[1], $this->posted, DataObject::DIFF_SIMPLE);
                $data = $diff['__M__'];
                if(count($data)==0){
                    $rdo->commit();
                    return $this->updateCache();
                }
                $result = self::$rdo->using(DB_CNT.'of_'.$this->data['SET_ALIAS'])->requiring()->where('CNT_ID', $this->data['ID'])->update($data);
                if($result!==false){
                    foreach ($data as $key => $val) {
                        $this->posted[$key] = $val;
                    }
                    $this->data = $this->posted;
                    $rdo->commit();
                    return $this->updateCache();
                }
            }
            $rdo->rollBack();
        }
        return false;
	}

    public function updateCache(){
        self::$storage->setBefore('spcnts/bases/')->store($this->data['ID']);
        self::$storage->setBefore('spcnts/xtnds/')->store($this->data['ID']);
        $cachetypes = ['spclist', 'spctops', 'spcnmls'];
        foreach(SPCLite::SORTKEYS as $sort){
			self::$storage->setBefore('spcnts/spcpubs/'.$this->data['SET_ALIAS'].'/')->store($sort);
            foreach($cachetypes as $type){
			    if($this->data['CAT_ID']){
				    self::$storage->setBefore($type.'/categories/'.$this->data['CAT_ID'].'/')->store($sort);
			    }else{
                    self::$storage->setBefore($type.'/presets/unclassified/')->store($sort);
                }
                self::$storage->setBefore($type.'/presets/unclassified/')->store($sort);
		    }
        }
        return $this;
    }

	public function recycle($status = 1){
		$base = new SPCLite($this->data['ID']);
        return $base->recycle($status);
	}

    public function destroy() {
        #使用事务
        self::$rdo->begin();
        if(self::$rdo->using(DB_CNT.'in_special_use')->requiring('ID = '.$this->data['ID'])->delete()){
            if(self::$rdo->using(DB_CNT.'of_'.$this->data['SET_ALIAS'])->requiring('CNT_ID = '.$this->data['ID'])->delete()){
                if(SPC\Tag::delete('cnt_id = '.$this->data['ID'])){
                    self::$rdo->commit();
                    return true;
                }
            }
        }
        self::$rdo->rollBack();
        return false;
    }
}
        