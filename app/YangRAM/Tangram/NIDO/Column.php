<?php
namespace Tangram\NIDO;

use Storage;
use RDO;

/**
 *	Uniform Column
 *	统一栏目对象
 *  分析并返回当前栏目信息以及包括频道信息
 */
final class Column extends DataObject {
    private static
    $columns = NULL;

    private
    $COL_ALIAS = '_FREE_PAGE_',
    $col_id = 0;

    protected static $defaults = [
        '__COL_ALIAS'       =>  '_FREE_PAGE_',
        '__COL_TREE'        =>  ['_FREE_PAGE_', '_FREE_PAGE_'],
        '__CHL_NAME'        =>	'',
        '__CHL_LANG'        =>	'',
        '__CHL_LOGO'        =>	'',
        '__CHL_DESC'        =>	'',
        '__CHL_HEAD'        =>	'',
        '__CHL_FOOT'        =>	'',
        '__COL_NAME'        =>	'Unclassified Pages',
        '__COL_TITLE'       =>	'',
        '__COL_KW'    =>	'',
        '__COL_DESC'        =>	'',
        '__COL_HEAD'      =>	'',
        '__COL_FOOT'      =>	''
    ];

    public static function initialize(){
        self::$columns = new Storage(PATH_DAT_COL, Storage::SER, true);
        self::$columns->setBefore('col_');
    }

    public static function emptyCache(){
        self::$columns->cleanOut();
    }

    public static function all(){
        $rdo = new RDO;
        $objs = [new self(NULL)];
        if($result = $rdo->using(DB_REG.'columns')->select()){
            $storage = self::$columns;
            $pdos = $result->getPDOStatement();
            while($row = $pdos->fetch(PDO::FETCH_ASSOC)){
                $storage->store($row['col_id'], $row);
                $obj = new self($row['col_id']);
                $objs[] = $obj;
            }
        }
        return $objs;
    }

    public function __construct($COL_ALIAS = NULL){
        $this->data = self::$defaults;
        $this->reset($COL_ALIAS);
    }

    public function reset($COL_ALIAS){
        if($COL_ALIAS&&is_string($COL_ALIAS)){
            $this->COL_ALIAS = $COL_ALIAS;
            $info = self::$columns->take($COL_ALIAS);
            if($info){
                $this->data = $info;
            }else{
                $rdo = new RDO;
                if($result = $rdo->using(DB_REG.'columns')->where('ALIAS', $COL_ALIAS)->select()){
                    $info = $result->getRow();
                    if($info){
                        $this->col_id = $info["ID"];
                        $this->data['__COL_TREE'] = $this->getColumnsPath($info["PARENT"], $info["ALIAS"]);
                        $this->getColumnInfo($info);
                        self::$columns->write($COL_ALIAS, $this->data);
                    }
                }else{
                    $status = new Status(500);
                    return $status->cast(Status::CAST_PAGE);
                }
            }
        }
    }

    public function id(){
        return $this->col_id;
    }

    private function getColumnInfo(array $info) {
        $this->data['__COL_ALIAS']  =   $info["ALIAS"];
        $this->data['__COL_COMMON'] =   $info["ALIAS"];
        $this->data['__COL_DESC']   =   $info["DESCRIPTION"];
        $this->data['__COL_FOOT']   =   $info["FOOTER"];
        $this->data['__COL_HEAD']   =	$info["HEADER"];
        $this->data['__COL_KW']     =	$info["KEYWORDS"];
        $this->data['__COL_NAME']   =	$info["NAME"];
        $this->getChannelInfo($info["CHANNEL"]);
	}

    private function getColumnsPath($PARENT, $ALIAS){
		if($PARENT){
			$in_path = [$ALIAS];
			while($PARENT){
				if($parent_col = RDO::id(DB_REG.'columns', $PARENT)){
					$in_path[] = $parent_col["ALIAS"];
					$PARENT = $parent_col["PARENT"];
				}else{
					$PARENT = 0;
				}
			}
			$in_path[] = $ALIAS;
			return array_reverse($in_path);
		}else{
			return [$ALIAS, $ALIAS];
		}
	}

    private function getChannelInfo($chl_id) {
        $info = RDO::id(DB_REG.'channels', $chl_id);
        if($info){
            $this->data['__CHL_NAME'] =	$info["NAME"];
            $this->data['__CHL_LANG'] =	$info["LANGUAGE"];
            $this->data['__CHL_LOGO'] =	$info["LOGO"];
            $this->data['__CHL_DESC'] =	$info["DESCRIPTION"];
            $this->data['__CHL_HEAD'] =	$info["HEADER"];
            $this->data['__CHL_FOOT'] =	$info["FOOTER"];
        }
	}

    public function push($COL_ALIAS, $reset = false){
        if($reset || ($this->data['__COL_ALIAS']==='_FREE_PAGE_')){
            $this->data['__COL_TREE'] = [];
        }
        $this->data['__COL_TREE'][] = $this->data['__COL_TREE'][0] = $this->data['__COL_ALIAS'] = $COL_ALIAS;
    }
}