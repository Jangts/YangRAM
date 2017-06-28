<?php
namespace CMF\Models;

use RDO;
use Tangram\NIDO\DataObject;
use AF\Models\R3Model_BC;

/**
 *	Model Of Embedded Light Content
 *	无格式易用型轻内容模型
 *  轻便内容，便利贴
 *  提供针对便利贴元素进行增删改查的接口
 *  Key-Value形式的内容，一般用来作为其他完整内容中的一个标签使用
 */
final class EMC extends R3Model_BC  {
    const
    ALL = 0,
	RECYCLED = 1,
	UNRECYCLED = 2,

	ID_DESC = [['id', true, DataObject::SORT_REGULAR]],
	ID_ASC = [['id', false, DataObject::SORT_REGULAR]],
	MTIME_DESC = [['KEY_MTIME', true, DataObject::SORT_REGULAR]],
	MTIME_ASC = [['KEY_MTIME', false, DataObject::SORT_REGULAR]],
	NAME_DESC = [['name', true, DataObject::SORT_REGULAR]],
	NAME_ASC = [['name', false, DataObject::SORT_REGULAR]],
	NAME_DESC_GBK = [['name', true, DataObject::SORT_CONVERT_GBK]],
	NAME_ASC_GBK = [['name', false, DataObject::SORT_CONVERT_GBK]],
	GROUP_DESC = [['groupname', true, DataObject::SORT_REGULAR]],
	GROUP_ASC = [['groupname', false, DataObject::SORT_REGULAR]],
    GROUP_DESC_GBK = [['groupname', true, DataObject::SORT_CONVERT_GBK]],
	GROUP_ASC_GBK = [['groupname', false, DataObject::SORT_CONVERT_GBK]];

	protected static
	$ca_path = PATH_DAT_CNT.'notes/',
    $table = DB_CNT.'in_embedded_use',
    $indexes = ['label', 'id'],
    $aikey = 'id',
    $lifetime = 0,
    $defaults = [
        'id'				=>	0,
        'type'              =>  1,
        'groupname'         =>  '',
        'name'              =>  '',
        'label'             =>  'ad_',
        'code'              =>  '',
        'KEY_MTIME'       =>  DATETIME,
        'KEY_IS_RECYCLED'     =>  0
        
    ];

    public static function byId($id){
        return self::id($id);
	}

    public static function byLabel($label){
        return self::identity($label);
	}

    public static function byType($type){
	}

    public static function byGroup($groupname){
        
	}

    public static function groups($status = self::UNRECYCLED, $orderby= self::GROUP_ASC){
        switch($status){
            case self::RECYCLED:
            $require = "KEY_IS_RECYCLED = 1 AND groupname <> ''";
            break;
            
            case self::UNRECYCLED:
            $require = "KEY_IS_RECYCLED = 0 AND groupname <> ''";
            break;
            default:
            $require = "groupname <> ''";
        }

        switch($orderby){
            case self::GROUP_DESC_GBK:
            $order = ['CONVERT(groupname USING gbk)', true];
            break;

            case self::GROUP_ASC_GBK:
            $order = ['CONVERT(groupname USING gbk)', false];
            break;

            case self::GROUP_DESC:
            $order = ['groupname', false];
            break;
            
            break;
            default:
            $order = ['groupname', false];
        }

        $rdo = self::getRDO();
        $result = $rdo->requiring($require)->orderby($order[0], $order[1])->distinct('groupname');
        if($result){
            return $result->toArray();
        }
        return [];
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

    protected function build($data, $posted = false){
        parent::build($data, $posted);
        //$this->readonly = true;
    }

    public function put($data){
        if(is_array($data)){
            $this->data = array_merge($this->data, array_intersect_key($data, static::$defaults));
        }
        return $this;
    }

    public function save (){
        $rdo = $this->rdo;
        if($this->posted){
            if(empty($this->_hash)){
                return false;
            }
            $diff = $this->diff($this->data, $this->posted, DataObject::DIFF_SIMPLE);
            $data = $diff['__M__'];
            unset($data['id']);
            if(count($data)==0){
                return $this;
            }
            if(isset($data['label'])){
				if($data['label']==''){
					$data['label'] = 'emc_'.time();
				}
				while($anotheremlement = RDO::one(self::$table, "label = '".$data['label']."' AND id <> ".$this->data['id'])){
					$data['label'] = 'emc_'.time().rand(100, 999);
				}
			}
            if($rdo->requiring()->where('label', $this->_hash)->update($data)){
                foreach ($data as $key => $val) {
                    $this->posted[$key] = $val;
                }
                if(isset($data['label'])){
                    $this->_hash = $data['label'];
                }
            }else{
                return false;
            }
        }else{
            unset($this->data['id']);
			if($this->data['label']==''){
				$this->data['label'] = 'emc_'.time();
			}elseif(is_numeric($this->data['label'])){
                $this->data['label'] = 'emc_'.$this->data['label'];
            }
			while($anotheremlement = RDO::one(self::$table, "label = '".$this->data['label']."'")){
				$this->data['label'] = 'emc_'.time().rand(100, 999);
			}
            if(!$rdo->insert($this->data)){
                return false;
            }
            $this->_hash = $this->data['label'];
            $result = $rdo->requiring()->where('label', $this->_hash)->select();
            $data = $result->getRow();
            $this->posted = $this->data = $data;
        }
        if($this->storage&&$this->posted){
            $this->storage->store($this->_hash);
        }
        return $this;
    }

    public function recycle($status = 1){
		$status = intval(!!$status);
		$this->data['KEY_MTIME'] = DATETIME;
        $this->data['KEY_IS_RECYCLED'] = $status;
		return $this->save();
	}
}
