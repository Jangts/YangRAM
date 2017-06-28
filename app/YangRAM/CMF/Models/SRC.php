<?php
namespace CMF\Models;

use PDO;
use Status;
use Tangram\NIDO\DataObject;
use Storage;
use RDO;
use Tangram\ORM\RDOAdvanced;

/**
 *	Model Of Attached Resource Infomation
 *	附件资源信息模型
 *  针对如下五种类型的文件资源进行封包的单一标准模型：
 *  **  DOC-General Document Data
 *  **  IMG-Picture File Data
 *  **  TXT-Text Document Data
 *  **  VOD-Video File Data
 *  **  WAV-Waveform File Data
 *  其中DOC是其他资源模型的基类
 */
final class SRC extends DataObject {
	private static
	$memory = [],
	$extends = [],
	$storage,
	$tables = [],
	$capaths = [],
	$rdo;

	private static function init(){
		if(!self::$rdo){
			self::$rdo = new RDOAdvanced();
		}
	}

	private static function deleteBase($filetype, $id){
		self::init();
		if(self::$rdo->using(DB_SRC.SRCLite::type($filetype))->requiring()->where('ID', $id)->delete()){
			return true;
		}
	}

	public static function byId($id, $type = 'doc'){
		if(in_array($type, ['img', 'doc', 'txt', 'wav', 'vod'])){
			$infoobj = SRCLite::byId($id, $type);
			if($infoobj->SRC_ID){
				if($sourceobj = self::getSource($infoobj->SRC_ID, $type)){
					$obj = new static();
					return  $obj->build($infoobj, $sourceobj, $type, true);
				}
				// var_dump($infoobj->FILE_TYPE, $infoobj->ID, $infoobj->SRC_ID);
				// die;
				self::deleteBase($infoobj->FILE_TYPE, $infoobj->ID);
			}
			return false;
		}else{
			new Status(703.4, 'Using Module Error', 'No Such Resource Type [' .$type. '].', true);
		}
	}

	public static function getSource($src_id, $type){
		$classname = '\CM\SRC\\'.strtoupper($type);
		$DOC = new $classname(['SID' => $src_id]);
		if($DOC->SID==$src_id){
			return $DOC;
		}
		return false;
	}

	private static function setSource(array $data, $type){
		$classname = '\CM\SRC\\'.strtoupper($type);
		return new $classname($data);
	}

	public static function count($type = NULL, $status = true) {
		self::init();
		self::$rdo->requiring()->orderby(false)->take(0);
		if(is_string($GROUPCODE)){
			self::$rdo->where('GROUPCODE', $GROUPCODE);
		}
		if($status){
			self::$rdo->where('KEY_IS_RECYCLED', 0);
		}else{
			self::$rdo->where('KEY_IS_RECYCLED', 1);
		}
		return self::$rdo->count();
	}

	public static function create($type, array $info, array $source){
		#使用事务
		#开启事务
		self::init();
		self::$rdo->begin();
		$sourceobj = self::setSource($source, $type)->save();
		if($sourceobj){
			$info['SRC_ID'] = $sourceobj->SID;
			if(isset($info['ID'])){
				$id = $info['ID'];
				$infoobj = new SRCLite($info['ID'], $type);
			}else{
				$info['ID'] = substr(substr($sourceobj->HASH, 8, 16).(BOOTTIME * 10000).uniqid(), 0, 44);
				$infoobj = SRCLite::create($info, $type);
			}
			if($infoobj->put($info)->save()){
				#提交事务
				self::$rdo->commit();
				$obj = new static();
				return $obj->build($infoobj, $sourceobj, $type, true);
			}
		}
		#回滚事务
		self::$rdo->rollBack();
		return false;
	}

	public static function update($id, $type, array $info, array $source){
		#使用事务
		self::init();
		self::$rdo->begin();
		if($sourceobj = self::setSource($source, $type)->save()){
			$info['SRC_ID'] = $sourceobj->SID;
			$infoobj = new SRCLite($id, $type);
			//var_dump($id, $infoobj);
			if($infoobj->put($info)->save()){
				//var_dump($infoobj);
				self::$rdo->commit();
				$obj = new static();
				return $obj->build($infoobj, $sourceobj, $type, true);
			}
		}
		self::$rdo->rollBack();
		return false;
	}

	public static function updateBySource($type, array $info, array $source){
		#使用事务
		self::init();
		self::$rdo->begin();
		if($sourceobj = self::setSource($source, $type)->save()){
			$info['SRC_ID'] = $sourceobj->SID;
			$infoobj = new SRCLite($id, $type);
			if($infoobj->put($info)->save()){
				//var_dump($infoobj);
				self::$rdo->commit();
				$obj = new static();
				return $obj->build($infoobj, $source, $type, true);
			}
		}
		self::$rdo->rollBack();
		return false;
	}

	public static function delete($require, $type = 'all'){
		self::init();
        self::$rdo->begin();
		$bases = SRCLite::query($type, $require);
		foreach($bases as $base){
            if($obj = $base->extend()){
                $obj->destroy();
            }else{
                self::$rdo->rollBack();
                return false;
            }
		}
        self::$rdo->commit();
		return true;
	}

	protected $LITE, $SRC;

	private function __construct(){
        self::init();
    }

	protected function build($info, $source, $type, $posted = false){
		$this->LITE = $info;
		$this->SRC = $source;
		$this->data = array_merge($info->toArray(), $source->toArray());
		$this->data['TYPE'] = $type;
        $this->_hash = $this->data['ID'];
        if($posted){
            $this->posted = $this->data;
        }else{
            $this->posted = NULL;
        }
        
        $this->xml = NULL;
        $this->readonly = false;
		return $this;
    }

	public function info(){
		return $this->LITE;
	}

	public function source(){
		return $this->SRC;
	}

	public function view(){
		$counter = new Counter(self::$table);
		if($counter->setKey('ID')->point($this->data['ID'])->add()){
			return true;
		}
		return false;
	}

	public function recycle($status = 1){
		$base = new SRCLite($this->data['ID'], $this->data['TYPE']);
        return $base->recycle($status);
	}

	public function destroy(){
		$sourece = $classname = '\CM\SRC\\'.strtoupper($this->data['TYPE']);
		$DOC = new $classname(['SID' => $this->data['SRC_ID']]);
		$DOC->destroy();
		if($DOC->error_msg!=='SQL_ERROR'){
			self::deleteBase($this->data['FILE_TYPE'], $this->data['ID']);
		}
		return false;
	}
}
