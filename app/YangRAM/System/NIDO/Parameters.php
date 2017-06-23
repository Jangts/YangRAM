<?php
namespace Tangram\NIDO;

use RDO;

/**
 *	Universal Requesting Parameters
 *	通用请求参数对象
 *  一个通过安全检查和专用通道重写$_GET拓展对象
 */
final class Parameters extends DataObject {
    public function __construct(array $paths, $item, $matches, $REST_PARAMS = []){
        $this->data['column'] = NULL;
        if($item&&isset($item['DIR_ALIASES'])&&isset($item['DEFAULTS'])&&isset($item['DOMAINS'])&&isset($item['PRM_NAMES'])){
            $this->getDefaults($item['DEFAULTS']);
            $this->getHostInfo($item['DOMAINS'], $paths);
            $this->getDataByDir($item['DIR_ALIASES'], $paths);
            $this->getParams($item['PRM_NAMES'], $matches);
        }
        foreach($REST_PARAMS as $key => $val){
            $this->data[$key] = $val;
        }
        foreach ($_GET as $key => $val) {
            $this->data[$key] = addslashes($val);
        }
    }

    private function getDefaults(array $defaults){
        foreach($defaults as $key=>$val){
            $this->data[$key] = $val;
        }
    }

    private function getHostInfo(array $host, array $paths){
        $domains = array_reverse(explode('.', $paths[0]));
        foreach($host as $grade=>$key){
            if(isset($domains[$grade])){
                $this->data[trim($key)] = $domains[$grade];
            }
        }
    }

    private function getDataByDir(array $folders, array $paths){
        $limit = min(count($folders), $this->length) - 1;
        if($limit>=0){
            $range = range(0, $limit - 1);
            foreach($range as $i){
                if(isset($paths[$i])){
                    $this->data[trim($folders[$i])] = $paths[$i];
                }
            }
        }        
    }

    private function getParams($paramnames, $matches){
        if(empty($paramnames)) $paramnames = [];
        if(empty($matches)) $matches = [];
        $limit = min(count($paramnames), count($matches)) - 1;
        $range = range(0, $limit);
        foreach($range as $i){
            if(isset($paramnames[$i])&&isset($matches[$i+1])){
                $this->data[trim($paramnames[$i])] = preg_replace('/^\/+/', '', $matches[$i+1]);
            }
        }
    }

    public function stopAttack($delete=false){
        if($delete){
            foreach ($this->data as $key => $val) {
                if(RDO::checkSqlWords($val, QS_SCAN_GET)==false){
                    unset($this->data[$key]);
                }
            }
            return $this;
        }
        foreach ($this->data as $key => $val) {
            $this->data[$key] = RDO::filterSqlWords($val);
        }
    }
}