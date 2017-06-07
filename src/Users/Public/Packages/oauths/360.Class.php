<?php
namespace Packages\oAuths;

// +-----------------------+
//        360登陆API 
// +-----------------------+

class logInByQh {
	function __construct($client_id, $client_secret, $access_token=NULL){
        $this->client_id=$client_id;
        $this->client_secret=$client_secret;
        $this->access_token=$access_token;
    }
	
	function logUrl($url){
        $params = array(
			"response_type" => "code",
			"client_id" => $this->client_id,
			"redirect_uri" => $url
        );
        return "https://openapi.360.cn/oauth2/authorize?".http_build_query($params);
    }
 
    function token($url, $code){
        $params=array(
            "grant_type"=>"authorization_code",
            "code"=>$code,
            "client_id"=>$this->client_id,
            "client_secret"=>$this->client_secret,
            "redirect_uri"=>$url
        );
        $_url="https://openapi.360.cn/oauth2/access_token";
        return $this->http($_url, http_build_query($params), "POST");
    }
 
    function refresh($refresh_token){
        $params=array(
            "grant_type"=>"refresh_token",
            "refresh_token"=>$refresh_token,
            "client_id"=>$this->client_id,
            "client_secret"=>$this->client_secret
        );
        $_url="https://openapi.360.cn/oauth2/access_token";
        return $this->http($_url, http_build_query($params), "POST");
    }
 
    function getInfo(){
        $params=[];
        $url="https://openapi.360.cn/user/me.json";
        return $this->api($url, $params);
    }
 
    function api($url, $params, $method="GET"){
        $params["access_token"]=$this->access_token;
        if($method=="GET"){
            $result=$this->http($url."?".http_build_query($params));
        }else{
            $result=$this->http($url, http_build_query($params), "POST");
        }
        return $result;
    }
 
    function http($url, $postfields="", $method="GET", $headers=[]){
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_TIMEOUT, 30);
        if($method=="POST"){
            curl_setopt($ci, CURLOPT_POST, TRUE);
            if($postfields!="")curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
        }
        $headers[]="User-Agent: logInBy360(Yangram.com/api)";
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLOPT_URL, $url);
        $response=curl_exec($ci);
        curl_close($ci);
        $json_r=[];
        if($response!="")$json_r=json_decode($response, true);
        return $json_r;
    }
}