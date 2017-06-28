<?php
namespace Tangram\NIDO;

use RDO;

/**
 *	Universal Form Submission Data
 *	通用表单数据对象
 *  一个通过安全检查和专用通道重写$_POST拓展对象
 */
final class FormData extends DataObject {
    public function __construct($args, $readonly = true){
        $this->data = [];
        if(_TEST_MODE_===true){
            if(isset($args->testdata)&&preg_match('/^[0-9a-z]{4}$/', $args->testdata)){
                $key = $args->testdata;
                $this->data = json_decode(file_get_contents(sT.$key.'.json'));
            }
        }else{
            $this->checkPost($args);
        }
    }

    private function checkPost(){
        if(empty($args->_local_post_)){
            $this->data = $_POST;
        }else{
            $addr = $this->REQUEST->ADDR;
            if($addr['FROM']===$addr['TO']&&preg_match('/^[0-9a-z]{16}$/', $args->_local_post_)){
                $key = $args->_local_post_;
                if(is_file(PATH_CACA.AI_CURR.'message/puts/'.$key)){
                    if($array = json_decode(file_get_contents(PATH_CACA.AI_CURR.'/message/puts/'.$key), true)){
                        $this->data = array_map("addslashes", $array);
                        unlink(PATH_CACA.AI_CURR.'message/puts/'.$key);
                    }
                }
            }else{
                $status = new Status(403);
                return $status->cast(Status::CAST_JLOG);
            }
        }
    }

    public function getPostArray(array $defaults){
        return array_merge($defaults, array_intersect_key($this->data, $defaults));
    }

    public function getUpdateArray(array $defaults){
        $diff = $this->diff($this->data, $defaults, DataObject::DIFF_SIMPLE);
        return $diff['__M__'];
    }

    public function stopAttack($delete=false){
        if($delete){
            foreach ($this->data as $key => $val) {
                if(RDO::checkSqlWords($val, QS_SCAN_POST)==false){
                    unset($this->data[$key]);
                }
            }
            return $this;
        }
        foreach ($this->data as $key => $val) {
            $this->data[$key] = RDO::filterSqlWords($val);
        }
        return $this;
    }
}