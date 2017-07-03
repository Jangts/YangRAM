<?php
namespace Packages\oAuths;

// +-----------------------+
//        开心登陆API 
// +-----------------------+

class logInByKx {
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
        );
        return "http://api.kaixin001.com/oauth2/authorize?".http_build_query($params);
    }
 
    function token($url, $code){
        $params=array(
            "grant_type"=>"authorization_code",
            "code"=>$code,
            "client_id"=>$this->client_id,
            "client_secret"=>$this->client_secret,
            "redirect_uri"=>$url
        );
        $_url="https://api.kaixin001.com/oauth2/access_token";
        return $this->http($_url, http_build_query($params), "POST");
    }
 
    function refresh($refresh){
        $params=array(
            "grant_type"=>"refresh_token",
            "refresh_token"=>$refresh,
            "client_id"=>$this->client_id,
            "client_secret"=>$this->client_secret,
        );
        $_url="https://api.kaixin001.com/oauth2/access_token";
        return $this->http($_url, http_build_query($params), "POST");
    }
 
    function getInfo(){
        $params=[];
        $url="https://api.kaixin001.com/users/me.json";
        return $this->api($url, $params);
    }
	
    function recordsAdd($content, $picurl=""){
        $params=array(
            "content"=>$content
        );
        if($picurl!="")$params["picurl"]=$picurl;
        $url="https://api.kaixin001.com/records/add.json";
        return $this->api($url, $params, "POST");
    }
 
    function recordsMe($num=10, $start=0){
        $params=array(
            "start"=>$start,
            "num"=>$num
        );
        $url="https://api.kaixin001.com/records/me.json";
        return $this->api($url, $params);
    }
 
    function commentList($id, $uid, $num=10, $start=0){
        $params=array(
            "objtype"=>"records",
            "objid"=>$id,
            "ouid"=>$uid,
            "start"=>$start,
            "num"=>$num
        );
        $url="https://api.kaixin001.com/comment/list.json";
        return $this->api($url, $params);
    }
 
    function forwardList($id, $uid, $num=10, $start=0){
        $params=array(
            "objtype"=>"records",
            "objid"=>$id,
            "ouid"=>$uid,
            "start"=>$start,
            "num"=>$num
        );
        $url="https://api.kaixin001.com/forward/list.json";
        return $this->api($url, $params);
    }
 
    function likeShow($id, $uid, $num=10, $start=0){
        $params=array(
            "objtype"=>"records",
            "objid"=>$id,
            "ouid"=>$uid,
            "start"=>$start,
            "num"=>$num
        );
        $url="https://api.kaixin001.com/like/show.json";
        return $this->api($url, $params);
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
        $headers[]="User-Agent: logInBykaixin(Yangram.com/api)";
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLOPT_URL, $url);
        $response=curl_exec($ci);
        curl_close($ci);
        $json_r=[];
        if($response!="")$json_r=json_decode($response, true);
        return $json_r;
    }
}