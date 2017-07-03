<?php
namespace Tangram\DBAL;

use PDO;
use PDOStatement;

/**
 *	RDO Select Result
 *	Tangram\DBAL\RDO与Tangram\DBAL\RDOAdvanced执行查询成功后反对的结果对象
 *	可以更方便的提取到自己想要的格式
 */
final class RDOSelectResult {
    private
    $PDOStatement = false,
    $queryString = false,
    $array = false,
    $json = false;

    public function __construct(PDOStatement $PDOStatement){
        if(is_a($PDOStatement, 'PDOStatement')){
            $this->PDOStatement = $PDOStatement;
            $this->queryString = $PDOStatement->queryString;
        }
    }

    public function getPDOStatement(){
        return $this->PDOStatement;
    }

    public function getCount(){
        return count($this->toArray());
    }

    public function getRow($row = 0){
        $this->toArray();
        if($this->array&&is_numeric($row)){
            return isset($this->array[$row]) ? $this->array[$row] : false;
        }
        return false;
    }

    public function toArray($indexField = false){
        if($this->array===false){
            $array = [];
            $pdos = $this->PDOStatement;
            if($pdos){
                if($indexField){
                    while($row = $pdos->fetch(PDO::FETCH_ASSOC)){
                        if(isset($row[$indexField])){
                            $array[$row[$indexField]] = $row;
                        }
                    }
                }else{
                    $array = $pdos->fetchAll(PDO::FETCH_ASSOC);
                }
            }
            $this->array = $array;
        }
		return $this->array;
	}

    public function toJson(){
        if($this->json===false&&($pdos = $this->PDOStatement)){
            $pdos = $this->PDOStatement;
            $json = '[';
            $row = $pdos->fetch(PDO::FETCH_ASSOC);
            while($row){
                $json .= json_encode($row);
                if($row = $pdos->fetch(PDO::FETCH_ASSOC)){
                    $json .= ',';
                }
            }
            $json .= ']';
            $this->json = $json;
        }
        return $this->json;
    }

    public function toXml($root = 'result', $row = 'row', $version = '1.0', $encoding = 'UTF-8'){
        if($this->xml===false&&($pdos = $this->PDOStatement)){
            $pdos = $this->PDOStatement;
            $dom = new DomDocument($version,  $encoding);
    		$xml = '<'.$root.'>';
    		while($row = $pdos->fetch(PDO::FETCH_ASSOC)){
    			$xml .= '<'.$row.'>';
    			foreach($row as $tag=>$txt){
    				$xml .= '<'.$tag.'>'.$txt.'</'.$tag.'>';
    			}
    			$xml .= '</'.$row.'>';
    		}
    		$xml .= '</'.$root.'>';
    		$dom->loadXml($xml);
            $this->xml = $dom;
        }
		return $this->xml;
	}
}
