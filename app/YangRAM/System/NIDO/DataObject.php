<?php
namespace System\NIDO;

/**
 *	General Data Enclosure
 *	一般数据封装包
 *  NI提供的数据封包工具，主要用来
 *  为数据提供格式化、读写保护、对比排差
 *  是NI中十多种数据对象的基类
 */
class DataObject {
    use traits\formatting;
    use traits\conversion;

    const
    DIFF_SIMPLE         =   0,
    DIFF_STRICT         =   1,
    DIFF_DEEP           =   2,

    SORT_REGULAR        =   0,        // 正常比较单元（不改变类型）
    SORT_NUMERIC        =   1,        // 单元被作为数字来比较
    SORT_STRING         =   2,        // 单元被作为字符串来比较
    SORT_LOCALE_STRING  =   3,        // 根据当前的区域（locale）设置来把单元当作字符串比较，可以用 setlocale() 来改变
    SORT_NATURAL        =   4,        // 和 natsort() 类似对每个单元以“自然的顺序”对字符串进行排序。 PHP 5.4.0 中新增的
    SORT_FLAG_CASE      =   5,        // 能够与 SORT_STRING 或 SORT_NATURAL 合并（OR 位运算），不区分大小写排序字符串
    SORT_CONVERT_GBK    =   700;      // 将单元转换为GBK编码格式，并按找GBK编码顺序排序


    final public static function enclose($data, $readonly = true, $type = 'mix'){
        switch ($type) {
            case 'json':
                $array = json_decode($data, true);
                break;

            case 'xml':
                $array = self::getArrayByXml($data);
                break;

            case 'serialized':
                $array = self::getArray(unserialize($data));
                break;

            case 'qs':
                parse_str($data, $array);
                break;

            default:
                $array = self::getArray($data);
                break;
        }
        return new self($array, $readonly);
    }

    public $error_msg;

    protected
    $readonly = false,
    $data,
    $xml;

    private function __construct(array $data, $readonly){
        $this->data = $data;
        $this->readonly = $readonly;
    }
    
    final public function diff(array $array, $data = false, $mode = self::DIFF_STRICT){
        $data = is_array($data) ? $data : (is_array($this->data) ? $this->data : []);
        $ints = array_intersect_key($data, $array);
        $diff = [
            '__A__' => array_diff_key($array, $data),
            '__D__' => array_diff_key($data, $array),
            '__M__' => [],
            '?????' => []
        ];
        foreach($ints as $key => $val){
            if($mode){
                if($array[$key]===$val) continue;
                if(gettype($array[$key])===gettype($val)){
                    if(is_array($val)){
                        if($mode===self::DIFF_DEEP){
                            $result = $this->diff($array[$key], $val);
                            if(count($result['__A__'])) $diff['__A__'][$key] = $result['__A__'];
                            if(count($result['__D__'])) $diff['__D__'][$key] = $result['__D__'];
                            if(count($result['__M__'])) $diff['__M__'][$key] = $result['__M__'];
                            if(count($result['?????'])) $diff['?????'][$key] = $result['?????'];
                            continue;
                        }
                        if($array[$key]==$val) continue;
                    }
                    if($mode===self::DIFF_DEEP&&$array[$key]==$val) continue;
                }
                if($mode===self::DIFF_DEEP&&$array[$key]==$val){
                    $diff['?????'][$key] = $array[$key];
                    continue;
                }
            }else{
                if($array[$key]==$val) continue;
            }
            $diff['__M__'][$key] = $array[$key];
        }
        return $diff;
    }

    final public function get($name){
        if(is_array($this->data)&&isset($this->data[$name])){
            return $this->data[$name];
        }
        return NULL;
    }

    final public function set($name, $value){
        if(($this->readonly===false)&&is_array($this->data)&&array_key_exists($name, $this->data)){
            $this->data[$name] = $value;
            $this->xml = '';
        }
        return $value;
    }

    public function add($property, $value){
        if(($this->readonly===false)&&is_array($this->data)){
            $this->xml = NULL;
            $this->data[$property] = $value;
        }
        return $this;
    }

    public function uns($property){
        if(is_array($this->data)){
            unset($this->data[$property]);
        }
        return $this;
    }
    
    public function toJson($trim = true, $indent = '   '){
        if($trim){
            return self::getJson($this->data);
        }
        return self::jsonToJson(self::getJson($this->data), false, $indent);
    }

    public function toXml($version = '1.0', $encoding = 'UTF-8'){
        if(empty($this->xml)){
            $this->xml = self::arrayToXml($this->data, $version, $encoding);
        }
        return $this->xml;
    }

    public function toString(){
        return serialize($this->data);
    }

    public function toQueryString($numericPrefix = 'arg_', $encodeType = false){
        return self::arrayToQueryString($this->data, $numericPrefix = 'arg_', $encodeType = false);
    }

    final public function toArray(){
        if(is_array($this->data)){
            return $this->data;
        }
        return [
            'type'  =>  'Unknown Type',
            'value' =>  $this->data
        ];
    }

    final public function __get($name){
        return $this->get($name);
    }

    final public function __set($name, $value){
        return $this->set($name, $value);
    }

    final public function __invoke($name){
         return $this->get($name);
    }

    final public function __isset($name){
        if(is_array($this->data)&&array_key_exists($name, $this->data)){
            return true;
        }
        return false;
    }

    final public function __unset($name){
        if(($this->readonly===false)&&is_array($this->data)&&array_key_exists($name, $this->data)){
            unset($this->data[$name]);
        }
    }

    final public function __toString(){
        return $this->toString();
    }
}
