<?php
namespace CM\SRC;

use Status;
use Tangram\NIDO\DataObject;
use Model;
use RDO;
use Storage;
use CM\SRCLite;

/**
 *	General Resourece Object Model
 *	一般文档信息模型
 *  此模型的是其他资源模型的基类
 */
class DOC extends Model {
	protected static
    $type = 'doc',
    $ca_path = PATH_DAT_CNT.'sources/documents/',
    $table = DB_SRC.'doc',
	$defaults = [
        'SID'               =>  0,
        'HASH'              =>  '',
        'LOCATION'          =>  '',
        'MIME'              =>  '',
        'KEY_CTIME'       =>  DATETIME
    ];

    protected $rdo, $storage, $posted;

    public static function checkHash($hash){
        $rdo = new RDO;
        $result = $rdo->using(static::$table.'_physical')->where('HASH', $hash)->take(1)->select('SID, LOCATION');
        if($result&&$data = $result->getRow()){
            $infos = $rdo->using(static::$table)->where('SRC_ID', $data['SID'])->select('ID');
            if($infos&&$infos->getCount()){
                return $data['SID'];
            }else{
                if(is_file(PATH_PUB.$data['LOCATION'])){
                    \unlink(PATH_PUB.$data['LOCATION']);
                }
                $rdo->using(static::$table.'_physical')->where('HASH', $hash)->delete();
            }
		}
		return false;
    }

    public static function checkQuote($SID){
        $rdo = new RDO;
        $result = $rdo->using(static::$table.'_physical')->where('SID', $SID)->take(1)->select('SID, LOCATION');
        if($result&&$data = $result->getRow()){
            $infos = $rdo->using(static::$table)->where('SRC_ID', $SID)->select('ID');
            if($infos&&$infos->getCount()){
                return $SID;
            }else{
                unlink(PATH_PUB.$data['LOCATION']);
                $rdo->using(static::$table.'_physical')->where('SID', $SID)->delete();
            }
		}
		return false;
    }

    public static function create(array $data){
        return new static($data);
    }

    public function __construct(array $data = []){
		$this->rdo = new RDO;
        $this->rdo->using(static::$table.'_physical');
        $this->storage = new Storage(static::$ca_path, Storage::JSN, true);
        if(empty($data['SID'])){
            if(empty($data['HASH'])){
                $this->build($data);
            }else{
                $this->buildByHASH($data['HASH'], $data);
            }
        }else{
            $this->buildById($data['SID'], $data);
        }
	}

	protected function buildById($SRC_ID, $data){
        if($cache = $this->storage->take($SRC_ID)){
            $this->data = $cache;
            $this->posted = $cache;
        }else{
            $result = $this->rdo->requiring()->where('SID', $SRC_ID)->take(1)->select();
            if($result&&$row = $result->getRow()){
			    $this->data = $row;
                $this->posted = $row;
                $this->storage->store($row['SID'], $row);
    		}else{
		    	$this->build($data);
    		}
        }
		
	}

    protected function buildByHASH($HASH, $data){
		$result = $this->rdo->requiring()->where('HASH', $HASH)->take(1)->select();
		if($result&&$row = $result->getRow()){
			$this->data = $row;
            $this->posted = $row;
            $this->storage->store($row['SID'], $row);
		}else{
			$this->build($data);
		}
	}

    protected function build($data){
        $this->data = [];
        unset($data['SID']);
        unset($data['KEY_CTIME']);
        foreach(static::$defaults as $key=>$val){
            if(isset($data[$key])){
                $this->data[$key] = $data[$key];
            }else{
                $this->data[$key] = $val;
            }
            $this->posted = NULL;
        }
        return $this;
	}

	public function save(){
		$rdo = $this->rdo;
        if($this->posted){
            if(empty($this->data['SID'])){
                return false;
            }
            $diff = $this->diff($this->data, $this->posted, DataObject::DIFF_SIMPLE);
            $data = $diff['__M__'];
            if(count($data)==0){
                return $this;
            }
            if($rdo->requiring()->where('SID', $this->data['SID'])->update($data)){
                foreach ($data as $key => $val) {
                    $this->posted[$key] = $val;
                }
            }else{
                return false;
            }
        }else{
            unset($this->data['SID']);
            $this->data['KEY_CTIME']   =	DATETIME;
            if(!$rdo->insert($this->data)){
                return false;
            }
            /*
            $result = $rdo->requiring()->where('SID', $rdo->lastInsertId('SID'))->select();
            $data = $result->getRow();
            $this->posted = $this->data = $data;
            */
            $this->data['SID'] = $rdo->lastInsertId('SID');
            $this->posted = $this->data;
        }
        $this->storage->store($this->data['SID']);
        return $this;
	}

	public function destroy(){
        $SRC_ID = $this->data['SID'];
        $resources = SRCLite::bySrouce($SRC_ID, $type = static::$type);
        if(count($resources)){
            $this->error_msg = 'ALREADY_IN_USE';
            return false;
        }
        if($this->rdo->requiring()->where('SID', $SRC_ID)->delete()){
            \unlink(PATH_PUB.$this->data['LOCATION']);
            self::$storage->store($this->_hash);
            return true;
        }
        $this->error_msg = 'SQL_ERROR';
        return false;
	}
}