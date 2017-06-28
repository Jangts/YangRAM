<?php
namespace CMF\Models\SRC;
use Status;
use Tangram\NIDO\DataObject;
use RDO;
use CMF\Models\SRC;
use CMF\Models\SRCLite;

/**
 *	Special Use Content Category Model
 *	专用内容分类模型
 *	用来创建、修改、删除专用内容分类的模型
 */
final class Folder extends \AF\Models\R3Model_BC {
	const
	ID_DESC = [['id', true, DataObject::SORT_REGULAR]],
	ID_ASC = [['id', false, DataObject::SORT_REGULAR]],
	MTIME_DESC = [['KEY_MTIME', true, DataObject::SORT_REGULAR]],
	MTIME_ASC = [['KEY_MTIME', false, DataObject::SORT_REGULAR]],
	NAME_DESC = [['name', true, DataObject::SORT_REGULAR]],
	NAME_ASC = [['name', false, DataObject::SORT_REGULAR]],
	NAME_DESC_GBK = [['name', true, DataObject::SORT_CONVERT_GBK]],
	NAME_ASC_GBK = [['name', false, DataObject::SORT_CONVERT_GBK]];

	protected static
	$ca_path = PATH_DAT_CNT.'folders/',
    $table = DB_SRC.'folders',
    $indexes = ['id'],
    $aikey = 'id',
    $lifetime = 0,
	$defaults = [
		'id'				=>	0,
        'parent'		    =>	0,
        'name'				=>	'New Folder',
        'KEY_MTIME'		=>	DATETIME,
        'KEY_IS_READONLY'		=>	0,
		'KEY_IS_RECYCLED'	    =>	0,
        'usr_id'			=>	0,
    ];

	private static function checkFolderExist($FLD_ID){
		$rdo = new RDO;
		$result = $rdo->using(self::$table)->requiring([
			'id'	=> $FLD_ID
		])->select('id');
		if($result&&($row = $result->getRow())){
			return true;
		}
		return false;
	}

	private static function checkFolderName($parent, $FLD_ID, $name = NULL){
		if(empty($name)){
			$name = 'New Folder';
		}
		$rdo = new RDO;
		$result = $rdo->using(self::$table)->requiring([
			'parent'	=> $parent,
			'name'		=> $name,
			'KEY_IS_RECYCLED'	=> 0
		])->select('id, name');
		if($result&&($row = $result->getRow())&&($row["id"]!=$FLD_ID)){
			$i = 1;
			while($i > 0){
				$result = $rdo->requiring([
					'parent'	=> $parent,
					'name'		=> $name.'('.$i.')',
					'KEY_IS_RECYCLED'	=> 0
				])->select('id, name');
				if(!$result||!($row = $result->getRow())||($row["id"]==$FLD_ID)){
					return $name.'('.$i.')';
				}
				$i++;
			}
		}
		return $name;
	}

    public static function roots(array $orderby = Folder::ID_ASC){
		return parent::query("`parent` = 0 AND `KEY_IS_RECYCLED` = 0" , $orderby);
	}

    public static function children($id, array $orderby = Folder::ID_ASC){
		return self::query(['parent' => $id, 'KEY_IS_RECYCLED' => 0], $orderby);
	}

	public static function create($parent, $name = NULL){
		$obj = new self;
		$obj->parent = $parent;
		$obj->name = $name;
		if($obj->save()){
			return $obj;
		}
		return false;
	}

	public static function modifyName($FLD_ID, $name){
		$obj = self::identity($FLD_ID);
		//var_dump($obj->id, $FLD_ID);
		$obj->name = $name;
		if($obj->save()){
			return $obj;
		}
		return false;
	}

	private static function remove($require, $status = SRCLite::RECYCLE){
		$objs = self::query($require);
		foreach($objs as $obj){
			$obj->recycle(SRCLite::HIDE);
		}
	}

	public static function removeById($FLD_ID, $status = SRCLite::RECYCLE){
		$obj = self::identity($FLD_ID);
		if($obj->recycle($status)){
			return $obj;
		}
		return false;
	}

	public function recycle($status = SRCLite::RECYCLE){
		$status = in_array($status, [0, 1, 2]) ? $status : 1;
		if($status){
			self::remove(['parent' => $this->_hash], SRCLite::HIDE);
			SRCLite::remove(['FLD_ID' => $this->_hash], SRCLite::HIDE, 'all');
		}else{
			self::remove(['parent' => $this->_hash], SRCLite::UNRECYCLE);
			SRCLite::remove(['FLD_ID' => $this->_hash], SRCLite::UNRECYCLE, 'all');
		}
		$this->data['KEY_IS_RECYCLED'] = $status;
		return $this->save();
	}

	public function destroy(){
		if($this->posted){
			self::delete(['parent' => $this->_hash]);
			SRC::delete(['FLD_ID' => $this->_hash], 'all');
			$this->rdo->requiring()->where('id', $this->_hash)->delete();
			if($this->storage) $this->storage->store($this->_hash);
			return true;
		}
        return false;
	}

	public function save (){
        $rdo = $this->rdo;
        if($this->posted){
			//die(var_dump($this->_hash, $this->data));
            if($this->readonly||empty($this->_hash)){
                return false;
            }
			if(!$this->checkParent()){
                return 0;
            }
			$this->data['name'] = self::checkFolderName($this->data['parent'], $this->_hash, $this->data['name']);
            $diff = $this->diff($this->data, $this->posted, DataObject::DIFF_SIMPLE);
            $data = $diff['__M__'];
			if(isset($data['parent'])&&!self::checkFolderExist($data['parent'])){
				return NULL;
			}
            if(count($data)==0){
                return $this;
            }
            if($rdo->requiring()->where('id', $this->_hash)->update($data)){
                foreach ($data as $key => $val) {
                    $this->posted[$key] = $val;
                }
            }else{
                return false;
            }
        }else{
			if(!$this->checkParent()){
                return 0;
            }
			if(!self::checkFolderExist($this->data['parent'])){
				return NULL;
			}
            unset($this->data['id']);
			$this->data['name'] = self::checkFolderName($this->data['parent'], 0, $this->data['name']);
            if(!$rdo->insert($this->data)){
                return false;
            }
            if(isset($this->_hash)){
                $this->_hash = $this->_hash;
            }else{
                $this->_hash = $rdo->lastInsertId('id');
            }
            $result = $rdo->requiring()->where('id', $this->_hash)->select();
            $data = $result->getRow();
            $this->posted = $this->data = $data;
        }
        if($this->storage&&$this->posted){
            $this->storage->store($this->_hash);
        }
        return $this;
    }

	public function checkParent(){
		if($this->data['parent']===$this->_hash){
            return false;
        }
		$parent = self::identity($this->data['parent']);
		$ancestors = $parent->getAncestors();
		foreach($ancestors as $ancestor){
			if($ancestor->id==$this->_hash){
				return false;
			}
		}
		return true;
	}

    public function getAncestors(){
		$path = [$this];
		$parent_id = $this->parent;
		while($parent_id){
			if($parent = self::identity($parent_id)){
				$path[] = $parent;
				$parent_id = $parent->parent;
			}else{
				new Status(703.4, 'Using Module Error', 'Parent Category ['.$parent_id.'] Not Found.', true);
			}
		}
		return array_reverse($path);
	}
}