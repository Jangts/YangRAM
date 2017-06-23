<?php
namespace CM\SPC;
use Status;
use Tangram\NIDO\DataObject;
use AF\Models\BaseR3Model;
use CM\SPC;

/**
 *	Special Use Content Category Model
 *	专用内容分类模型
 *	用来创建、修改、删除专用内容分类的模型
 */
final class Category extends BaseR3Model  {
	const
	ID_DESC = [['id', true, DataObject::SORT_REGULAR]],
	ID_ASC = [['id', false, DataObject::SORT_REGULAR]],
	NAME_DESC = [['name', true, DataObject::SORT_REGULAR]],
	NAME_ASC = [['name', false, DataObject::SORT_REGULAR]],
	NAME_DESC_GBK = [['name', true, DataObject::SORT_CONVERT_GBK]],
	NAME_ASC_GBK = [['name', false, DataObject::SORT_CONVERT_GBK]];

	protected static
	$conn_type = 1,
	$ca_path = PATH_DAT_CNT.'categories/',
    $table = DB_SPC.'categories',
    $indexes = ['id'],
    $aikey = 'id',
    $lifetime = 0,
	$defaults = [
		'id'				=>	0,
		'set_id'			=>	'',
        'parent'		    =>	0,
        'name'				=>	'New Category',
        'title'			    =>	'',
        'keywords'			=>	'',
        'description'		=>	'',
		'top_display_num'	=>	1,
    ];

    public static function byType($SET_ID, array $orderby = Category::ID_ASC){
		if(!is_numeric($SET_ID)){
            if($SET = Preset::id($SET_ID)){
                return $SET_ID = $SET->alias;
            }else{
				return [];
			}
		}
		return self::query("`set_id` = $SET_ID" , $orderby);
	}

    public static function roots($SET_ID, array $orderby = Category::ID_ASC){
		if(!is_numeric($SET_ID)){
            if($SET = Preset::alias($SET_ID)){
                $SET_ID = $SET->id;
            }else{
				return [];
			}
		}
		return self::query("`set_id` = $SET_ID AND `parent` = 0" , $orderby);
	}

    public static function defaults($SET_ID){
		if(!is_numeric($SET_ID)){
            if($SET = Preset::alias($SET_ID)){
                $SET_ID = $SET->id;
            }else{
				return NULL;
			}
		}
		$array = self::query("`set_id` = $SET_ID AND `parent` = 0" , [['id', false, DataObject::SORT_REGULAR]], 1);
		if($array&&isset($array[0])){
			return $array[0];
		}
		return NULL;
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

	public function preset(){
		$set_id = $this->data['set_id'];
		return Preset::byId($set_id);
	}

	public function destroy(){
		if($this->posted){
			$rdo = $this->rdo;
			#使用事务
			$rdo->begin();
			if(SPC::moveto("`CAT_ID` = '$this->_hash'", 0)){
				if($rdo->requiring("`id` = '$this->_hash'")->delete()){
					$rdo->commit();
            		return true;
        		}
			}
			$rdo->rollBack();
		}
		return false;
	}
}