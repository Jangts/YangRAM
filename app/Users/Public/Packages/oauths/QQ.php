<?php
namespace Packages\oAuths;

// +-----------------------+
//        QQ登陆API 
// +-----------------------+

class logInByQQ {
	function __construct($client_id, $client_secret, $access_token=NULL){
        $this->client_id=$client_id;
        $this->client_secret=$client_secret;
        $this->access_token=$access_token;
    }
	
	function logUrl($url, $scope=""){
        $params=array(
            "client_id"=>$this->client_id,
            "redirect_uri"=>$url,
			"response_type"=>"code",
            "scope"=>$scope,
        );
        return "https://graph.qq.com/oauth2.0/authorize?".http_build_query($params);
    }
	
	function token($url, $code){
		$params=array(
            "grant_type"=>"authorization_code",
            "client_id"=>$this->client_id,
            "client_secret"=>$this->client_secret,
            "code"=>$code,
            "state"=>"",
            "redirect_uri"=>$url
        );
        $_url="https://graph.qq.com/oauth2.0/token?".http_build_query($params);
        $result_str=$this->http($_url);
        $json_r=[];
        if($result_str!="")parse_str($result_str, $json_r);
        return $json_r;
    }
	
	/**
    function refresh($url, $refresh){
        $params=array(
            "grant_type"=>"refresh_token",
            "refresh_token"=>$refresh,
            "client_id"=>$this->client_id,
            "client_secret"=>$this->client_secret,
            "redirect_uri"=>$url
        );
        $url="https://www.douban.com/service/auth2/token";
        return $this->http($url, http_build_query($params), "POST");
    }
    **/
 
    function getOpenid(){
        $params=array(
            "access_token"=>$this->access_token
        );
        $url="https://graph.qq.com/oauth2.0/me?".http_build_query($params);
        $result_str=$this->http($url);
        $json_r=[];
        if($result_str!=""){
            preg_match("/callback\(\s+(.*?)\s+\)/i", $result_str, $result_a);
            $json_r=json_decode($result_a[1], true);
        }
        return $json_r;
    }
 
    function getUserInfoByOid($openid){
        $params=array(
            "openid"=>$openid
        );
        $url="https://graph.qq.com/user/get_user_info";
        return $this->api($url, $params);
    }
 
    function share($openid, $title, $url, $site, $fromurl, $images="", $summary=""){
        $params=array(
            "openid"=>$openid,
            "title"=>$title,
            "url"=>$url,
            "site"=>$site,
            "fromurl"=>$fromurl,
            "images"=>$images,
            "summary"=>$summary
        );
        $url="https://graph.qq.com/share/add_share";
        return $this->api($url, $params, "POST");
    }
 
    function api($url, $params, $method="GET"){
        $params["access_token"]=$this->access_token;
        $params["oauth_consumer_key"]=$this->client_id;
        $params["format"]="json";
        if($method=="GET"){
            $result_str=$this->http($url."?".http_build_query($params));
        }else{
            $result_str=$this->http($url, http_build_query($params), "POST");
        }
        $result=[];
        if($result_str!="")$result=json_decode($result_str, true);
        return $result;
    }
 
    function http($url, $postfields="", $method="GET", $headers=[]){
        $ci=curl_init();
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_TIMEOUT, 30);
        if($method=="POST"){
            curl_setopt($ci, CURLOPT_POST, TRUE);
            if($postfields!="")curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
        }
        $headers[]="User-Agent: logInByQQ(Yangram.com/api)";
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLOPT_URL, $url);
        $response=curl_exec($ci);
        curl_close($ci);
        return $response;
	}
}