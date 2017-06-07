<?php
namespace Packages\oAuths;

// +-----------------------+
//        豆瓣登陆API 
// +-----------------------+

class logInByDb {
	function __construct($client_id, $client_secret, $access_token=NULL){
        $this->client_id=$client_id;
        $this->client_secret=$client_secret;
        $this->access_token=$access_token;
    }
 
    function logUrl($url, $scope=""){
        $params=array(
            "response_type"=>"code",
            "client_id"=>$this->client_id,
            "redirect_uri"=>$url,
            "scope"=>$scope,
            "state"=>md5(time())
        );
        return "https://www.douban.com/service/auth2/auth?".http_build_query($params);
    }
 
    function token($url, $code){
        $params=array(
            "grant_type"=>"authorization_code",
            "code"=>$code,
            "client_id"=>$this->client_id,
            "client_secret"=>$this->client_secret,
            "redirect_uri"=>$url
        );
        $_url="https://www.douban.com/service/auth2/token";
        return $this->http($_url, http_build_query($params), "POST");
    }
 
    function refresh($url, $refresh){
        $params=array(
            "grant_type"=>"refresh_token",
            "refresh_token"=>$refresh,
            "client_id"=>$this->client_id,
            "client_secret"=>$this->client_secret,
            "redirect_uri"=>$url
        );
        $_url="https://www.douban.com/service/auth2/token";
        return $this->http($_url, http_build_query($params), "POST");
    }
 
    function getInfo(){
        $params=[];
        $url="https://api.douban.com/v2/user/~me";
        return $this->api($url, $params);
    }
	
    function share($text, $title, $url, $description="", $pic=""){
        $params=array(
            "text"=>$text,
            "rec_title"=>$title,
            "rec_url"=>$url,
            "rec_desc"=>$description,
            "rec_image"=>$pic
        );
        $url="https://api.douban.com/shuo/v2/statuses/";
        return $this->api($url, $params, "POST");
    }
 
    function api($url, $params, $method="GET"){
        $headers[]="Authorization: Bearer ".$this->access_token;
        if($method=="GET"){
            $result=$this->http($url."?".http_build_query($params), "", "GET", $headers);
        }else{
            $result=$this->http($url, http_build_query($params), "POST", $headers);
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
        $headers[]="User-Agent: logInByDouban(Yangram.com/api)";
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLOPT_URL, $url);
        $response=curl_exec($ci);
        curl_close($ci);
        $json_r=[];
        if($response!="")$json_r=json_decode($response, true);
        return $json_r;
    }
}