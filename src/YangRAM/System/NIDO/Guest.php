<?php
namespace System\NIDO;

use RDO;
use Request;
use System\ORM\NI_PDOExtended_BaseClass;
use AF\Models\Certificates\Passport;

/**
 *	Guest
 *	作客对象
 *  协约单例类（非技术单例类）
 *	注册用户与普通访客来访登记和访问信息读取所用的全局对象
 */
final class Guest extends DataObject {
    private
    $uid = 0,
    $gid = 0;
	
    public function __construct(){
        $this->uid = Passport::whose();
		$this->data = [
			'IP'		=>	Request::instance()->IP,
			'referer'	=>	isset($_SERVER['HTTP_REFERER']) ? urlencode($_SERVER['HTTP_REFERER']) : NULL,
			'is_new'	=>	$this->isNew()
		];
		$this->isMobileRequest();
    }

    private function isMobileRequest(){
		$mobile_browser = '0';
		if(isset($_SERVER['HTTP_VIA'])&&stristr($_SERVER['HTTP_VIA'],"wap")){
			$mobile_browser++;
		}
		
		// 检查浏览器是否接受 WML.
		if(isset($_SERVER['HTTP_ACCEPT'])&&strpos(strtoupper($_SERVER['HTTP_ACCEPT']),"VND.WAP.WML") > 0){
			$mobile_browser++;
		}
		if(isset($_SERVER['HTTP_USER_AGENT'])&&preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', strtolower($_SERVER['HTTP_USER_AGENT']))){
			$mobile_browser++;
		}
		if(isset($_SERVER['HTTP_ACCEPT'])&&(strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false)){
			$mobile_browser++;
		}
		if(isset($_SERVER['HTTP_X_WAP_PROFILE'])){
			$mobile_browser++;
		}
		if(isset($_SERVER['HTTP_PROFILE'])){
			$mobile_browser++;
		}
		$_SERVER['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$mobile_ua = $_SERVER['HTTP_USER_AGENT'];
		$mobile_agents = ['240x320',
		'abacho', 'acer', 'acoon', 'acs-', 'ahong', 'airness', 'alav', 'alcatel', 'amoi', 'android', 'anywhereyougo.com', 'applewebkit/525', 'applewebkit/532', 'asus', 'audi', 'au-mic', 'avantogo',
		'becker', 'benq', 'bilbo', 'bird', 'blackberry', 'blazer', 'bleu', 'brew',
		'cdm-', 'cell', 'cldc', 'cmd-', 'compal', 'coolpad',
		'danger', 'dbtel', 'doco', 'dopod',
		'elaine', 'eric', 'etouch',
		'fly ', 'fly_', 'fly-',
		'go.web', 'goodaccess', 'gradiente', 'grundig',
		'haier', 'hedy', 'hipt', 'hitachi', 'htc', 'huawei', 'hutchison',
		'inno', 'ipad', 'ipaq', 'iphone', 'ipod',
		'java', 'jbrowser', 'jigs',
		'kddi', 'keji', 'kgt', 'kwc',
		'lenovo', 'lg ', 'lg2', 'lg3', 'lg4', 'lg5', 'lg7', 'lg8', 'lg9', 'lg-c', 'lg-d', 'lg-g', 'lge-', 'lge9', 'longcos',
		'maemo', 'maui', 'maxo', 'mercator', 'meridian', 'micromax', 'midp', 'mini', 'mitsu', 'mmef', 'mmm', 'mmp', 'mobi', 'mot-', 'moto', 'mwbp',
		'nec-', 'netfront', 'newgen', 'newt', 'nexian', 'nf-browser', 'nintendo', 'nitro', 'nokia', 'nook', 'novarra',
		'obigo', 'oper',
		'palm','panasonic','pantech','philips','phone','pg-', 'playstation','pocket', 'port', 'prox', 'pt-',
		'qc-', 'qtek', 'qwap',
		'rover',
		'sagem', 'sama', 'samu', 'sanyo', 'samsung', 'sch-', 'scooter', 'sec-', 'sendo', 'seri', 'sgh-', 'sharp', 'siemens', 'sie-', 'smal', 'smar', 'softbank', 'sony', 'sph-', 'spice', 'sprint', 'spv', 'symbian',
		't-mo', 'memowall', 'talkabout', 'tcl-', 'teleca', 'telit', 'tianyu', 'tim-', 'toshiba', 'tsm',
		'up.browser', 'upg1', 'upsi', 'utec', 'utstar',
		'verykool', 'virgin', 'vk-', 'voda', 'voxtel', 'vx',
		'w3c', 'wap-', 'wapa', 'wapi', 'wapp', 'wapr', 'webc', 'wellco', 'wig browser', 'wii', 'windows ce', 'winw', 'wireless',
		'xda','xde','zte'];
		foreach ($mobile_agents as $device) {
			if (stristr($mobile_ua, $device)) {
				$mobile_browser++;
				break;
			}
		}
		
		$_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
		if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false){
			$mobile_browser++;
		}
		// Pre-final check to reset everything if the user is on Windows
		if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false){
			$mobile_browser=0;
		}
		// But WP7 is also Windows, with a slightly different characteristic
		if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false){
			$mobile_browser++;
		}
		if($mobile_browser>0){
			$this->data['is_mobile'] = 1;
		} else {
			$this->data['is_mobile'] = 0;
		}
	}

    private function isNew(){
        if($this->uid>0){
			$is_new = false;
			$this->gid = 'usr_'.$this->uid;
		}else if(isset($_COOKIE['guest_id'])&&preg_match('/^gst_\d{1,9}/', $_COOKIE['guest_id'])){
			$is_new = false;
			$this->gid = $_COOKIE['guest_id'];
		}else{
			$is_new = true;
			$this->gid = 0;
		}
		return $is_new;
    }

    public function record($col_id){
		$rdo = new RDO;
		$rdo->using(DB_AST.'users_guests');
		$isnew = intval($this->data['is_new']);
		$result = $rdo->insert([
			'usr_id' => $this->uid,
			'gst_id' => $this->gid,
			'col_id' => $col_id,
			'app_id' => AI_CURR,
			'uri' => urlencode('//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),
			'accesstime' => DATETIME,
			'ip' => $this->data['IP'],
			'is_mobile' => $this->data['is_mobile'],
			'source' => $this->data['referer'],
			'is_new' => $isnew,
		]);
		if($result&&$isnew){
			$lid = $rdo->lastInsertId();
			$gid = 'gst_'.$lid;
			$rdo->requiring()->where('id', $lid)->where('gst_id', 0)->update(['gst_id'=>$gid]);
			$this->gid = $gid;
			setcookie("guest_id", $this->gid, 0, '/', HOST, _USE_HTTPS_, true);
		}
    }
}
