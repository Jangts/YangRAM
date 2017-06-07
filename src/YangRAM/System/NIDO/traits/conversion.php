<?php
namespace System\NIDO\traits;

/**
 *	Format Conversion Trait
 *	格式转换特性
 */
trait conversion {
    private static $roman = [
        'M' => 1000,
        'D' => 500,
        'C' => 100,
        'L' => 50,
        'X' => 10,
        'V' => 5,
        'I' => 1
    ];

    final public static function decToRoman($num){
        if(!is_numeric($num) || $num > 3999 || $num <= 0){
            return false;
        }
        foreach(self::$roman as $k => $v){
            if(($amount[$k] = floor($num / $v)) > 0){
                $num -= $amount[$k] * $v;
            }
        }
        $return = '';
        foreach($amount as $k => $v){
            $return .= $v <= 3 ? str_repeat($k, $v) : $k . $old_k;
            $old_k = $k;
        }
        return str_replace(['VIV','LXL','DCD'],['IX','XC','CM'],$return);
    }

    final public static function romanToDec($str = ''){
        if(is_numeric($str)){
            return false;
        }
        $range = str_split($str);
        foreach($range as $s){
            if(isset(self::$roman[strtoupper($s)])){
                $values[] = self::$roman[strtoupper($s)];
            }
        }
        $sum = 0;
        while($current = current($values)){
            $next = next($values);
            $next > $current ? $sum += $next - $current + 0 * next($values) : $sum += $current;
        }
        return $sum;
    }

    final public static function arrayToXml(array $array, $version = '1.0', $encoding = 'UTF-8'){
        $dom = new \DomDocument($version, $encoding);
        self::fillXMLElement($array, $dom, $dom);
		return $dom->saveXML();
    }

    final protected static function fillXMLElement(array $arr, $dom, $xml){
        if(isset($arr['tag'])){
            if(isset($arr['value'])&&is_scalar($arr['value'])){
                $ele = $dom->createElement($arr['tag'], $arr['value']);;
            }else{
                $ele = $dom->createElement($arr['tag']);
                if(isset($arr['value'])&&is_array($arr['value'])){
                    foreach ($arr['value'] as $child) {
                        self::fillXMLElement($child, $dom, $ele);
                    }
                }
            }
            if(isset($arr['attr'])&&is_array($arr['attr'])){
                foreach ($arr['attr'] as $attr => $value) {
                    $ele->setAttribute($attr, $value);
                }
            }
            $xml->appendchild($ele);
        }
    }

    final public static function xmlToArray($source){
        if(is_string($source)&&$source!=''){
            $dom = new \DOMDocument();
            $dom->loadXML($source);
            $array = self::readXMLElement($dom);
            if(($array['tag'] === '#document')&&is_array($array['value'])&&isset($array['value'][0])){
                return $array['value'][0];
            }
        }
        return [];
    }

    final protected static function readXMLElement($node){
        $array = [
            'tag' => $node->nodeName
        ];
        if ($node->hasAttributes()) {
            $array['attr'] = [];
            foreach ($node->attributes as $attr) {
                $array['attr'][$attr->nodeName] = $attr->nodeValue;
            }
        }

        if ($node->hasChildNodes()) {
            $arr = [];
            $txt = '';
            foreach ($node->childNodes as $childNode) {
                if ($childNode->nodeType != XML_TEXT_NODE) {
                    $arr[] = self::readXMLElement($childNode);
                }else{
                    $txt .= $childNode->nodeValue;
                }
            }
            if(count($arr)){
                $array['value'] = $arr;
            }else{
                $array['value'] = $txt;
            }
        } else {
            $array['value'] = $node->nodeValue;
        }
        return $array;
    }

    final public static function arrayToQueryString($data, $numericPrefix = 'arg_', $encodeType = false){
        switch($encodeType){
            case PHP_QUERY_RFC1738:
            case PHP_QUERY_RFC3986:
            return http_build_query($data, $numericPrefix, '&', $encodeType);

            default:
            $array = [];
            foreach($data as $key=>$val){
                if($val&&is_string($val)){
                    $val = '='.$val;
                }else{
                    $val = '';
                }
                if(is_numeric($key)){
                    $array[] = $numericPrefix.$key.$val;
                }else{
                    $array[] = $key.$val;
                }
            }
            return join('&', $array);
        }
    }

    final public static function jsonToJson($data, $trim = true, $indent = '   '){
        if($trim){
            return json_encode(json_decode($data));
        }
        $data = urldecode($data);

        $ret = '';
        $pos = 0;
        $length = strlen($data);
        $newline = "\n";
        $prevchar = '';
        $outofquotes = true;

        for($i=0; $i<=$length; $i++){
            $char = substr($data, $i, 1);
            if($char=='"' && $prevchar!='\\'){
                $outofquotes = !$outofquotes;
            }elseif(($char=='}' || $char==']') && $outofquotes){
                $ret .= $newline;
                $pos --;
                for($j=0; $j<$pos; $j++){
                    $ret .= $indent;
                }
            }
            $ret .= $char;
            if(($char==',' || $char=='{' || $char=='[') && $outofquotes){
                $ret .= $newline;
                if($char=='{' || $char=='['){
                    $pos ++;
                }
                for($j=0; $j<$pos; $j++){
                    $ret .= $indent;
                }
            }
            $prevchar = $char;
        }
        return $ret;
    }

    public static function arraySort($array, array $orderBy = [false, false, self::SORT_REGULAR]){
    }
}