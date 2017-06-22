<?php
namespace CM\SPC;

use PDO;
use Status;
use System\NIDO\DataObject;
use Model;
use Storage;
use RDO;
use System\ORM\RDOAdvanced;
use CM\SPC;
use CM\SPCLite;

/**
 *	Special Use Content Type Model
 *	专用内容类型模型，又称为预设模型
 *	用来创建、修改、删除专用内容类型的模型
 *  因专用内容类型相对专用内容而言，需要预先设置，故又称为预设
 *  预设主要有如下10种基本类型
            case 'base 通　　用类型         精简型/复合型/另类型
            case 'msgs 消　　息类型         公告/通知等类型
            case 'arti 文　　章类型         文学性/学术性文章类型
            case 'news 新闻资讯类型         时效性文章类型
            case 'down 资　　源类型         用于被下载的资源类型
            case 'play 媒体资源类型         用于直接播放的资源类型
            case 'ablm 图集影集类型         成组的媒体资源类型
            case 'wiki 词　　条类型         描述物体及其属性的对象类型
            case 'item 项目产品类型         描述一般具象物体及其属性的对象类型
            case 'resm 个人履历类型         描述人物及其特征的对象类型
 *  其中arti是默认类型
 */
final class Preset extends Model  {
    const
	ID_DESC = [['id', true, DataObject::SORT_REGULAR]],
	ID_ASC = [['id', false, DataObject::SORT_REGULAR]],
    MTIME_DESC = [['KEY_MTIME', true, DataObject::SORT_REGULAR]],
	MTIME_ASC = [['KEY_MTIME', false, DataObject::SORT_REGULAR]],
	NAME_DESC = [['name', true, DataObject::SORT_REGULAR]],
	NAME_ASC = [['name', false, DataObject::SORT_REGULAR]],
	NAME_DESC_GBK = [['name', true, DataObject::SORT_CONVERT_GBK]],
	NAME_ASC_GBK = [['name', false, DataObject::SORT_CONVERT_GBK]],

    OPEN = 0,
    ALL = 1,
    CURR = 2;

	private static $storage, $memory, $rdo;

	protected static
    $ca_path = PATH_DAT_CNT.'presets/',
	$defaults = [
		'id'				=>	0,
		'basic_type'		=>	'arti',
		'name'				=>	'New Preset',
		'alias'				=>	'',
		'code'				=>	'DEMO',
        'note'				=>	'Manage Contens',
		'item_type'			=>	'Special Contents',
		'item_unit'			=>	'',
		'top_display_num'	=>	2,
		'theme'				=>	'default',
		'template'			=>	'default.niml',
        'can_contribute'	=>	0,
        'nonaudit'			=>	1,
		'KEY_STATE'		    =>	1,
        'appid'		        =>	0
    ];

	protected
	$posted;

    private static function fieldtype($input_type, $default = false){
        switch($input_type){
            case 'int':
            case 'number':
            return 'INT(11)';

            case 'tiny':
            return 'CHAR(128)';

            case 'url':
            case 'file':
            case 'email':
            case 'color':
            return 'VARCHAR(1024)';

            case 'text':
            return 'VARCHAR(65536)';
             
            case 'tags':
            case 'files':
            case 'hidden':
            case 'editor':
            case 'content':
            case 'options':
            return 'LONGTEXT';

            case 'month':
            return 'TINYINT(2)';

            case 'checkbox':
            case 'week':
            case 'radio':
            return 'TINYINT(1)';

            case 'date':
            case 'time':
            case 'year':
            case 'datetime':
            return strtoupper($input_type);
        }
    }

    private static function fieldvalue($input_type, $default = false){
        if($default===false){
            $value = "NULL";
        }else{
            if($default === NULL){
                $value = "NOT NULL DEFAULT NULL";
            }else{
                $value = "NOT NULL DEFAULT '$default'";
            }
        }
        switch($input_type){
            case 'date':
            case 'time':
            case 'year':
            case 'datetime':
            return 'NULL';
            
            default:
            return $value;
        }
    }

    private static function init(){
		if(!self::$storage){
			self::$storage = new Storage(static::$ca_path, Storage::JSN, true);;
			self::$rdo = new RDOAdvanced();
		}
        self::$rdo->using(DB_SPC.'presets');
	}

    final public static function create(array $data, array $customFields = []){
		if(isset($data['alias'])){
			if(Preset::alias($data['alias'])){
				return false;
			}
			$obj = new static();
			$obj->build($data, true);
			$type = $obj->basic_type;
			$fieldset = [];
			// 开启事务
			//self::checkRDO();
			self::$rdo->begin(false, false);
			if($obj->save()){
				$set_id = $obj->id;
				foreach($customFields as $n=>$fieldinfo){
					$fieldinfo['name'] = 'dev_'.$fieldinfo['name'];
					$fieldinfo['sort'] = $n;
					$fieldinfo['set_id'] = $set_id;
					$field = new Field($fieldinfo);
					if($field->save()){
						if($filed->default_value){
							$fieldset[] = [$field->name, $filed->filed_type, 'DEFAULT '.$filed->default_value];
						}else{
							$fieldset[] = [$field->name, $filed->filed_type];
						}
						if(self::$rdo->using(DB_CNT.'of_'.$data['alias'])->like($fieldset)){
							self::$rdo->commit();
							return $obj;
						}else{
							// 创建子表失败，回滚全部操作，关闭事务
							self::$rdo->rollBack();
						}
					}else{
						// 新自定义字段提交失败，回滚全部操作，关闭事务
						self::$rdo->rollBack();
					}
				}
			}else{
				// 新预设提交失败，回滚新建预设的操作，关闭事务
				self::$rdo->rollBack();
			}
		}
		return false;
    }

    public static function query($require = "0" , array $orderby = Preset::ID_ASC, $range = 0, $format = Model::LIST_AS_OBJ){
        self::init();
        $objs = [];
        if(is_numeric($require)){
            $range = $require;
            $require = "1";
        }elseif(is_string($require)||is_array($require)){
            $require = $require;
        }else{
            $require = "1";
        }
        self::$rdo->requiring($require)->take($range)->orderby(false);
        foreach($orderby as $order) {
            if(isset($order[0])&&isset($order[1])){
                self::$rdo->orderby((string)$order[0], !!$order[1]);
            }
        }
        $result = self::$rdo->select();
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

	public static function byvalueType($valueType, array $orderby = Preset::ID_ASC){
		self::init();
        $objs = [];
		$result = self::$rdo->requiring()->where('basic_type', $valueType)->take(0)->orderby($orderby)->select();
        if($result){
            $pdos = $result->getPDOStatement();
            while($pdos&&$data = $pdos->fetch(PDO::FETCH_ASSOC)){
                $obj = new static();
                $objs[] = $obj->build($data, true);
            }
        }
        return $objs;
	}

    public static function all($inUse = true, array $orderby = Preset::ID_ASC, $app = Preset::ALL){
        self::init();
        $objs = [];
        self::$rdo->requiring();
        switch($app){
            case Preset::OPEN:
            self::$rdo->where('appid', 0);
            break;

            case Preset::CURR:
            self::$rdo->where('appid', AI_CURR);
            break;
        }
		if($inUse){
			self::$rdo->where('KEY_STATE', 1);
		}
		$result = self::$rdo->take(0)->orderby($orderby)->select();
        if($result){
            $pdos = $result->getPDOStatement();
            while($pdos&&$data = $pdos->fetch(PDO::FETCH_ASSOC)){
                $obj = new static();
                $objs[] = $obj->build($data, true);
            }
        }
        return $objs;
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

    public static function alias($value, $app = Preset::ALL){
		self::init();
		$storage = self::$storage;
		if($data = $storage->take($value)){
			if(self::checkApp($data['appid'], $app)){
                $obj = new static();
			    return  $obj->build($data, true);
            }
            return false;
		}
		self::$rdo->requiring()->where('alias', $value);
        return self::single($app);
    }

	public static function id($value, $app = Preset::ALL){
		self::init();
		$storage = self::$storage;
		if($data = $storage->take($value)){
            if(self::checkApp($data['appid'], $app)){
                $obj = new static();
			    return  $obj->build($data, true);
            }
            return false;
		}
        self::$rdo->requiring()->where('id', $value);
        return self::single($app);
    }

    private static function checkApp($appid, $app = Preset::ALL){
        switch($app){
            case Preset::OPEN:
            if($appid == 0){
                return false;
            }
            break;

            case Preset::CURR:
            if( == AI_CURR){
                return false;
            }
            break;
        }
        return true;
    }

    private static function single($app){
        switch($app){
            case Preset::OPEN:
            $result = self::$rdo->where('appid', 0);
            break;

            case Preset::CURR:
            $result = self::$rdo->where('appid', AI_CURR)->select();
            break;

            default:
            $result = self::$rdo->select();;
        }
		
		if($result&&$data = $result->getRow()){
			$storage->store($data['id'], $data);
			$storage->store($data['alias'], $data);
			$obj = new static();
			return  $obj->build($data, true);
		}
        return false;
    }

    protected function __construct(){
        self::init();
    }

    protected function build($data, $posted = false){
		$this->data = array_merge(self::$defaults, array_intersect_key($data, self::$defaults));
        if(isset($this->data['alias'])){
            $this->_hash = $this->data['alias'];
        }
        if($posted){
            $this->posted = $this->data;
        }else{
            $this->posted = NULL;
        }
        
        $this->xml = NULL;
        $this->readonly = false;
		return $this;
    }

    public function save(){
        $rdo = self::$rdo;
        if($this->posted){
            if(empty($this->_hash)){
                return false;
            }
            $diff = $this->diff($this->data, $this->posted, DataObject::DIFF_SIMPLE);
            $data = $diff['__M__'];
			unset($this->data['id']);
            if(count($data)==0){
                return false;
            }
            if($rdo->requiring()->where('alias', $this->_hash)->update($data)){
                foreach($data as $key => $value) {
                    $this->posted[$key] = $value;
                }
				# 清空预设列表的缓存，及其所辖内容的缓存
            }else{
                return false;
            }
        }else{
            unset($this->data['id']);
            if(!$rdo->insert($this->data)){
                return false;
            }
            if(isset($this->data['alias'])){
                $this->_hash = $this->data['alias'];
            }else{
                $this->_hash = self::$rdo->lastInsertId('alias');
            }
            if($result = self::$rdo->requiring()->where('alias', $this->_hash)->select()){
                $data = $result->getRow();
                $this->posted = $this->data = $data;
            }else{
                return false;
            }
        }
        $this->cache($this->data);
        return true;
    }

    public function on(){
        $this->cache();
        if($this->posted['KEY_STATE']!=1){
            if(self::$rdo->requiring()->where('alias', $this->_hash)->update(['KEY_STATE' => 1])){
                $this->data['KEY_STATE'] = 1;
                $this->posted['KEY_STATE'] = 1;
				# 清空预设列表的缓存，及其所辖内容的缓存
                return true;
            }
            $this->data['KEY_STATE'] = 0;
            return false;
        }
        $this->data['KEY_STATE'] = 1;
        $this->posted['KEY_STATE'] = 1;
        return NULL;
    }

    public function off(){
        $this->cache();
        if($this->posted['KEY_STATE']!=0){
            if(self::$rdo->requiring()->where('alias', $this->_hash)->update(['KEY_STATE' => 0])){
                $this->data['KEY_STATE'] = 0;
                $this->posted['KEY_STATE'] = 0;
                self::$storage->store($this->data['id'], $this->data);
                self::$storage->store($this->data['alias'], $this->data);
				# 清空预设列表的缓存，及其所辖内容的缓存
                return true;
            }
            $this->data['KEY_STATE'] = 1;
            return false;
        }
        $this->data['KEY_STATE'] = 0;
        $this->posted['KEY_STATE'] = 0;
        return NULL;
    }

    public function addCategory(array $attrs, $parent = 0){
        $cat = new Category;
        foreach($attrs as $attr=>$value){
            $cat->$attr = $value;
        }
        $cat->parent = $parent;
        $cat->set_id = $this->data['id'];
        if($cat->save()){
            return $cat;
        }
        return false;
    }

    public function delCategory($id){
        if(is_numeric($id)){
            $cat = Category::identity($id);
            if($cat->set_id == $this->data['id']){
               return $cat->destroy();
            }
        }elseif(is_string($id)){
            return Category::delete(['set_id'=>$this->data['id'], 'name' => $id]);
        }
        return false;
    }

    public function addField($name, array $attrs){
        $field = new Field;
        foreach($attrs as $attr=>$value){
            $field->$attr = $value;
        }
        $field->set_id = $this->data['id'];
        $field->name = 'dev_'.$name;
        if(isset($attrs['field_type'])){
            $field->field_type = $attrs['field_type'];
        }else{
            $field->field_type = self::fieldtype($field->input_type, $field->default_value);
        }
        self::$rdo->begin();
        if($field->save()){
            $otherset = self::fieldvalue($field->input_type, $field->default_value);
            if(self::$rdo->using(DB_CNT.'of_'.$this->_hash)->addField($field->name, $field->field_type, $otherset)){
                self::$rdo->commit();
                return $field;
            };
        }
        self::$rdo->rollBack();
        return false;
    }

    public function modField($name, array $attrs){
        # 这个函数体语句较多，且逻辑相对负责，有必要逐行备注
        # 查询相关的字段，并构建对象
        $field = Field::query(['name'=>'dev_'.$name, 'set_id' => $this->data['id']]);
        # 如果字段存在则修改字段，否则操作失败
        if(count($fields)){
            # 指定要修改的字段对象
            $field = $fields[0];

            # 备份当前字段类型和控件类型
            $type = $field->field_type;
            $input = $field->input_type;
            $value = $field->default_value;

            # 为对象的各属性赋予新值
            foreach($attrs as $attr=>$value){
                $field->$attr = $value;
            }

            # 强制修正预设标识
            $field->set_id = $this->data['id'];

            # 判断是否需要修改字段名
            if(isset($attrs['name'])&&$attrs['name']!=$name){
                $field->name = 'dev_'.$attrs['name'];
                $rename = true;
            }else{
                $rename = false;
            }

            # 判断是否需要修改字段类型
            if(isset($attrs['field_type'])){
                if($type != $attrs['field_type']){
                    # 如果指定了新的字段类型，且新的字段类型不同于当前字段类型，则认为需要修改字段类型
                    $field->field_type = $attrs['field_type'];
                    $repros = true;
                }else{
                    # 如果指定了新的字段类型，但新的字段类型和当前字段类型一样，则认为不需要修改字段类型
                    if(isset($attrs['default_value'])&&$value != $attrs['default_value']){
                        # 如果指定了新的默认值，且只有变更
                        $repros = true;
                    }else{
                        # 不需要修改字段属性
                        $repros = false;
                    }
                }
            }else{
                if(isset($attrs['input_type'])&&$input != $attrs['input_type']){
                    # 如果没有指定了新的字段类型，但指定新的控件类型，则重新计算相应的字段类型
                    $field->field_type = self::fieldtype($field->input_type, $field->default_value);
                    if($type != $attrs['field_type']){
                        # 如果计算所得的字段类型与当前字段类型不同，则认为需要修改字段类型
                        $repros = true;
                    }else{
                        if(isset($attrs['default_value'])&&$value != $attrs['default_value']){
                            # 如果指定了新的默认值，且只有变更
                            $repros = true;
                        }else{
                            # 不需要修改字段属性
                            $repros = false;
                        }
                    }
                }else{
                    if(isset($attrs['default_value'])&&$value != $attrs['default_value']){
                        # 如果指定了新的默认值，且只有变更
                        $repros = true;
                    }else{
                        # 不需要修改字段属性
                        $repros = false;
                    }
                }
            }

            # 开启事务
            self::$rdo->begin();
            if($field->save()){
                # 如果字段注册信息修改成功，则继续修改相应的字段实体
                if($rename){
                    # 如果需要更改字段名
                    if($repros){
                        # 如果需要更改字段属性，则只用RDOAdvanced::changeField
                        $otherset = self::fieldvalue($field->input_type, $field->default_value);
                        $fieldset = $field->field_type . ' ' . $otherset;
                        if(self::$rdo->using(DB_CNT.'of_'.$this->_hash)->changeField($fieldname, $fieldset, $field->name)){
                            self::$rdo->commit();
                            return $field;
                        }
                    }else{
                        # 如果仅需要更改字段名，则只用RDOAdvanced::renameField
                        if(self::$rdo->using(DB_CNT.'of_'.$this->_hash)->renameField($name, $field->name)){
                            self::$rdo->commit();
                            return $field;
                        }
                    }
                }elseif($repros){
                    # 如果仅需要更改字段属性，则只用RDOAdvanced::modifyField
                    $otherset = self::fieldvalue($field->input_type, $field->default_value);
                    $fieldset = $field->field_type . ' ' . $otherset;
                    if(self::$rdo->using(DB_CNT.'of_'.$this->_hash)->modifyField($fieldname, $fieldset)){
                        self::$rdo->commit();
                        return $field;
                    }
                }
            }
        }
        self::$rdo->rollBack();
        return false;
    }

    public function delField($name){
        $fields = Field::query(['name'=>'dev_'.$name, 'set_id' => $this->data['id']]);
        if(count($fields)){
            $field = $fields[0];
            self::$rdo->begin();
            if(Field::delete(['name'=>'dev_'.$name, 'set_id' => $this->data['id']])){
                if(self::$rdo->using(DB_CNT.'of_'.$this->_hash)->dropField($field->name)){
                    self::$rdo->commit();
                    return true;
                }
            }
        }
        self::$rdo->rollBack();
        return false;
    }

    public function destroy(){
        #使用事务
		#开启事务
		self::$rdo->begin();
        if($this->posted&&(self::$rdo->requiring()->where('alias', $this->_hash)->delete())){
            $this->cache();
            if(self::$rdo->using(DB_SPC.'categories')->requiring()->where('set_id', $this->data['id'])->delete()){
                if(Field::delete("`set_id` = '$this->data['id']'")){
                    if(Tag::delete("`set_alias` = '$this->_hash'")){
                        $contents = SPCLite::ByUse($this->_hash);
                        $run = true;
		                foreach($contents as $content){
			                $run = $content->destroy();
		                }
                        if($run&&self::$rdo->using(DB_CNT.'of_'.$this->_hash)->drop()){
                            #提交事务
			                self::$rdo->commit();
                            return true;
                        }
                    }
                }
            }
        }
        #回滚事务
		self::$rdo->rollBack();
        return false;
	}

    private function cache($data = false){
        self::$storage->store($this->data['id'], $data);
		self::$storage->store($this->data['alias'], $data);
        return $this;
	}
}