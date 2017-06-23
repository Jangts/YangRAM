<?php
namespace Tangram\R5;

use Status;
use Tangram\NIDO\DataObject;

/**
 *	URL Remote Data Reader
 *	URL远程数据读取器，用来
 *  读取远程服务器上的数据，并自动封装为Tangram\NIDO\Commom对象以供使用
 */
final class RemoteDataReader {
    const
    MSG = 0,
    SER = 1,
    JSN = 2,
    XML = 3,
    
    UA_MOZ_WIN = 'Mozilla/5.0 (Windows NT 5.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1',
    UA_MOZ_MAC = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.202 Safari/535.1',
    UA_MOZ_ADR = 'Mozilla/5.0 (Linux; U; Android 2.3.7; Nexus One Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1';

    public static function buildQueryString($params) {
        $querystring = '';
        if (is_array($params)) {
    		foreach ($params as $key => $val) {
    			if (is_array($val)) {
    				foreach ($val as $val2) {
    					$querystring .= urlencode($key).'='.urlencode($val2).'&';
    				}
    			} else {
    				$querystring .= urlencode($key).'='.urlencode($val).'&';
    			}
    		}
    		$querystring = substr($querystring, 0, -1); // Eliminate unnecessary &
    	} elseif(is_string($params)) {
    	    $querystring = $params;
    	}
    	return $querystring;
    }

    private static function buildUrl($url, $params = NULL) {
        $request = parse_url($url);
        $scheme     = empty($request['scheme'])     ?   (_USE_HTTPS_ ? 'https://' : 'http://')  :   $request['scheme'] . '://';
        $host       = empty($request['host'])       ?   HOST  :   $request['host'];
        $port       = empty($request['port'])       ?   ''  :   ':' . $request['port'];
        $user       = empty($request['user'])       ?   ''  :   $request['user'];
        $pass       = empty($request['pass'])       ?   ''  :   ':' . $request['pass'] ;
        $pass       = ($user || $pass)              ?   "$pass@"                        :   '';
        $path       = empty($request['path'])       ?   ''  :   $request['path'];
        $query      = empty($request['query'])      ?   self::buildQueryString($params)   :   $request['query'] . '&' . self::buildQueryString($params);
        $query      = empty($query)                 ?   ''  :   '?' . $query;
        $fragment   = empty($request['fragment'])   ?   ''  :   '#' . $request['fragment'];
        $request['url'] = trim("$scheme$user$pass$host$port$path$query$fragment");
        return $request;
    }

    public static function gets(array $urls, $format = RemoteDataReader::JSN){
        $objs = [];
        foreach($urls as $url){
            $objs[] = (new self($url, NULL, $format))->read();
        }
        return $objs;
    }
    
    protected
    $request,
    $format,
    $headers,
    $data = [
        'type'  =>  'EmptyRemoteReader',
        'value' =>  NULL
    ],
    $timeout = 30;

    public function __construct($url, $params = NULL, $format = RemoteDataReader::JSN, array $headers = []) {
        $this->request = self::buildUrl($url, $params);
        $this->format = $format;
        $this->readonly = true;
        $this->headers = $headers;
        $this->headers['User-Agent'] = RemoteDataReader::UA_MOZ_WIN;
    }

    public function setAgent($agent){
        $this->headers['User-Agent'] = $agent;
        return $this;
    }

    public function setTimeout($timeout){
        $this->timeout = $timeout;
        return $this;
    }

    private function buildHeaders(){
        $headers = [];
        foreach ($this->headers as $name => $value) {
            $headers[] = sprintf('%s: %s', $name, $value);
        }
        return $headers;
    }

    final protected function connByClientUrl(){
        $ch = curl_init();
        $header = $this->buildHeaders();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $this->request['url']);
        if($response = @curl_exec($ch)){
            curl_close($ch);
            return $response;
        }else{
            $errno = curl_errno($ch);
            $errstr = curl_error($ch);
            curl_close($ch);
            return $this->connError('CURL', $errno, $errstr);
        }
    }

    private function connError($type, $errno, $errstr){
        switch($errno) {
			case -3:
			$errormsg = 'Socket creation failed (-3)';
            break;
			case -4:
			$errormsg = 'DNS lookup failure (-4)';
            break;
			case -5:
			$errormsg = 'Connection refused or timed out (-5)';
            break;
			default:
			$errormsg = 'Connection failed ('.$errno.')';
            break;
		}
        return [
            'type'  =>  'ErrorRemoteData',
            'conn'  =>  $type,
            'emsg'  =>  $errormsg.' '.$errstr
        ];
    }

    private function connByFileContents(){
        $headers = $this->buildHeaders();
        $opts = [
            'http' => [
                'method'=>"GET",
                'header' => join("\r\n", $headers),
                'timeout' => $this->timeout
            ]
        ];
        $context = stream_context_create($opts);
        if($contents = @file_get_contents($this->request['url'], false, $context)){
            return $contents;
        }
        return [
            'type'  =>  'ErrorRemoteData',
            'conn'  =>  'FOPEN',
            'hdrs'  =>  @get_headers($this->request['url'], 1)
        ];
    }

    private function checkData($response){
        if(is_string($response)){
            switch ($this->format) {
                case 1:
                $data = unserialize($response);
                break;
                case 2:
                $data = json_decode($response, true);
                break;
                case 3:
                $this->xml = $response;
                $data = DataObject::xmlToArray($response);
                break;
                default:
                $data = $response;
                break;
            }
            if(!$data){
                $data = $response;
            }
        }else{
            $data = $response;
        }
        return $data;
    }

    public function read(){
        if(extension_loaded('CURL')){
            $response = $this->connByClientUrl();
        }else{
            @ini_set('allow_url_fopen', '1');
            if(function_exists('get_headers')){
                $response = $this->connByFileContents();
            }else{
                $response = [
                    'type'  =>  'ErrorRemoteData',
                    'emsg'  =>  'Your I4s Not Support RemoteDataReader.'
                ];
            }
        }
        return DataObject::enclose($this->checkData($response));
    }
}