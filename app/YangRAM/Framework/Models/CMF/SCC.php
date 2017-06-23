<?php
namespace CM;
use Tangram\NIDO\DataObject;

/**
 *	Model Of Scalable Specific Use Content
 *	可扩展式专有用途内容模型
 *  自定表格内容，松式专用内容
 *  针对如下五种类型的非关系型数据单元模型进行封包的单一标准模型：
 *  **  Base          			基本模型
 *  **  Item                    项目数据模型
 *  **  Project                 活动项目数据模型
 *  **  Questionaire            问卷记录模型
 *  **  OrderForm               定单记录模型
 *  与SRC的强制封包不同，SCC使用的是魔术封包，并允许提取出原始模型，因为
 *  **  SRC的不同类型只是数据结构不一样，但是操作方法相同，而
 *  **  SCC的不同类型的数据除了结构不一样，还各自带有不同的方法，因而无法使用单一模型操作，且操作时应当提取基本模型实例
 *  另外，SRC静态方法获取的实例集合都是SRC自身的实例，而SCC不论静态方法还是实例方法，返回的实例都是基本模型实例
 *  数据细节正在设计之中……
 */
final class SCC extends Base {
	const
	ID_DESC = [['ID', true, DataObject::SORT_REGULAR]],
	ID_ASC = [['ID', false, DataObject::SORT_REGULAR]],
	CTIME_DESC = [['KEY_CTIME', true, DataObject::SORT_REGULAR]],
	CTIME_ASC = [['KEY_CTIME', false, DataObject::SORT_REGULAR]],
	MTIME_DESC = [['KEY_MTIME', true, DataObject::SORT_REGULAR]],
	MTIME_ASC = [['KEY_MTIME', false, DataObject::SORT_REGULAR]],
	TITLE_DESC = [['TITLE', true, DataObject::SORT_REGULAR]],
	TITLE_ASC = [['TITLE', false, DataObject::SORT_REGULAR]],
	TITLE_DESC_GBK = [['TITLE', true, DataObject::SORT_CONVERT_GBK]],
	TITLE_ASC_GBK = [['TITLE', false, DataObject::SORT_CONVERT_GBK]],

	LIST_AS_STATEMENT = 477;

	protected static
	$ca_path = PATH_DAT_CNT.'',
	$defaults = [
		'ID'				=>	NULL,
		'TYPE'				=>	'base',
		'SET_HASH'			=>	'',
		'TITLE'				=>	'',
		'DESCRIPTION'		=>	'',
		'KEY_CTIME'		=>	DATETIME,
		'KEY_MTIME'		=>	DATETIME,
		'KEY_STATE'		=>	1,
		'SYS_SCCYCLING'		=>	0,
		'USR_ID'			=>	0,
		'DATA'				=>	NULL
    ];

	public static function byId($hash, $schema_hash = 'sample'){
		if($schema_hash){
			$schema = Schema::hash($schema_hash);
		}
		return false;
	}

	public static function count($schema_hash = 'sample') {
		
	}

	public static function getList($schema_hash = 'sample', $status = 0, array $orderby = SPCLite::ID_DESC, $start = 0, $num = 18, $format = Model::LIST_AS_OBJ){
        
		return $objs;
    }

	public static function query ($require = "0" , array $orderby = SPCLite::ID_ASC, $range = 0, $format = Model::LIST_AS_OBJ){
        self::init();
        $objs = [];
		$result = self::querySelect(self::$rdo, $require, $orderby, $range);
        if($result){
			if($format===Model::LIST_AS_ARR){
                return $result->toArray();
            }
            $pdos = $result->getPDOStatement();
            while($pdos&&$data = $pdos->fetch(PDO::FETCH_ASSOC)){
                self::$memory[$data['ID']] = $data;
                $objs[] = new self($data['ID']);
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
        if(self::$rdo->using(DB_CNT.'in_nosql_sets')->insert($inserts[0])){
            $inserts[1]["CNT_ID"] = self::$rdo->lastInsertId('ID');
            if(self::$rdo->using(DB_CNT.'of_'.$post['schema_ALIAS'])->insert($inserts[1])){
                if($inserts[0]["TAGS"] !== ''){
                    $tags = explode(',', $inserts[0]["TAGS"]);
                    $intersect_base["TAGS"] = join(",", $tags);
                    SPC\Tag::posttags($tags, $inserts[1]["CNT_ID"], $post['schema_ALIAS']);
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

    public static function remove($require, $status = 1){
        
	}

	public static function delete($require){
		
	}

	private $SCCstatement;

	private function __construct($data){
        //self::init();
    }

	public function getStatement(){
		if($this->$SCCstatement){
			return $this->$SCCstatement;
		}
		$this->$SCCstatement = '';
		return $this->$SCCstatement;
	}

	public function destroy() {

	}
}

