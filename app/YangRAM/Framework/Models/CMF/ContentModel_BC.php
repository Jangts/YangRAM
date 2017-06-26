<?php
namespace CM;

use Tangram\NIDO\DataObject;
use Library\compilers\HTMLClose;

abstract class ContentModel_BC extends DataObject {
	public static function count(){
		return 0;
	}

	public static function all(){
		return [];
	}

    public static function replaceroot($str){
		return str_replace(strtolower(__DIR), '{{@root_url}}', $str);
	}

	public static function restoreroot($str){
		return str_replace('{{@root_url}}', strtolower(__DIR), $str);
	}

	public static function checkContentPager($str){
		if (strpos($str, '{{@page_break}}') === false){
			return $str;
		}
		return implode('{{@page_break}}', array_map(['Library\compilers\HTMLClose', 'compile'], explode('{{@page_break}}', $str)));
	}

	protected static function querySelect($rdo, $require, $orderby, $range){
		if(is_numeric($require)){
            $range = $require;
            $require = "1";
        }elseif(is_string($require)||is_array($require)){
            $require = $require;
        }else{
            $require = "1";
        }
		if(is_numeric($range)){
			$rdo->requiring($require)->take($range)->orderby(false);
		}elseif(is_array($range)){
			$rdo->requiring($require)->take($range[1], $range[0])->orderby(false);
		}else{
			$rdo->requiring($require)->take(0)->orderby(false);
		}
        foreach ($orderby as $order) {
            static::querySort($order, $rdo);
        }
        return $rdo->select();
	}

	protected static function querySort($order, $rdo){
        if(isset($order[0])&&isset($order[1])){
            if(isset($order[2])){
                switch($order[2]){
                    case DataObject::SORT_CONVERT_GBK:
                    $orderFieldName = 'CONVERT('.(string)$order[0].' USING gbk)';

                    default:
                    $orderFieldName = (string)$order[0];
                }
            }else{
                $orderFieldName = (string)$order[0];
            }
            $rdo->orderby($orderFieldName, !!$order[1]);
        }
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

	public static function remove($require, $status = 1){
		return false;
	}

	public static function delete($require){
		return false;
	}

	public function put($data){
        return $this;
    }

	public function cln(){
		return NULL;
	}

	public function save(){
		return $this;
	}

	public function recycle($status = 1){
		return $this;
	}

	public function destroy(){
		return $this;
	}
}
