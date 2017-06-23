<?php
namespace Tangram\NIDO\traits;

/**
 *	Data Formatting Trait
 *	数据格式化特性
 */
trait formatting {
    final protected static function getArray($data){
        if(is_array($data)){
            return $data;
        }
        elseif(is_object($data)){
            if(function_exists([$data, 'toArray'])){
                return $data->toArray();
            }
            return get_object_vars($data);
        }
        elseif(is_scalar($data)){
            return [
                'type'  =>  'Scalar '.gettype($data),
                'value' =>  $data
            ];
        }
        else{
            return [
                'type'  =>  'Unknown Data',
                'value' =>  $data
            ];
        }
    }

    final protected static function getArrayByXml($source, $root = 'root') {
        $xml = new \XMLReader();
        $xml->xml($source);
        $data = self::xmlDecode($xml);
        $xml->close();
        if(isset($data[$root])){
            return $data[$root];
        }
        return [];
    }

    final private function xmlDecode($xml) {
        $ele = [];
        $txt = [];
        while(@$xml->read()){
            switch ($xml->nodeType) {
                case \XMLReader::END_ELEMENT:
                if(empty($ele)){
                    return join('', $txt);
                }else {
                    return $ele;
                }

                case \XMLReader::ELEMENT:
                $ele[$xml->name] = $xml->isEmptyElement ? '' : self::xmlDecode($xml);
                break;

                case \XMLReader::TEXT:
                $txt[] = $xml->value;
                break;
            }
        }
        if(empty($ele)){
            return join('', $txt);
        }
        return $ele;
    }

    final protected static function getJson($data){
        if($json = json_encode($data)){
            return $json;
        }
        return '{"type":"Unknown Type", "value":""}';
    }

    final protected static function getXmlbyArray($data, $root = 'data', $version = '1.0', $encoding = 'UTF-8', $style = NULL, $xml_use_numeric_key = false){
        $xml = new \XmlWriter();
        $xml->openMemory();
        $xml->startDocument($version, $encoding);
        $xml->startElement($root);
        if(is_array($data)){
            self::xmlEncode($xml, $data);
        }elseif(is_object($data)){
            self::xmlEncode($xml, get_object_vars($data));
        }
        $xml->endElement();
        return $xml->outputMemory(true);
    }

    final protected function xmlEncode($xml, array $data) {
        foreach($data as $key => $value){
            if(!is_numeric($key)||$xml_use_numeric_key){
                if(is_scalar($value)){
                    $xml->writeElement($key, $value);
                    continue;
                }
                if(is_array($value)){
        			$xml->startElement($key);
        			self::xmlEncode($xml, $value);
        			$xml->endElement();
        			continue;
        		}
                if(is_object($value)){
        			$xml->startElement($key);
        			self::xmlEncode($xml, get_object_vars($value));
        			$xml->endElement();
        			continue;
        		}
    		}
		}
	}
}