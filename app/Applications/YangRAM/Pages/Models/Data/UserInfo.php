<?php
namespace Pages\Models\Data;

use Status;
use Model;
use AF\Models\Certificates\Passport;
use Library;

class UserInfo extends Model {
	protected $data;

	public $passport;

	public function __construct(){
		$passport = Passport::instance();
		$this->passport = $passport;
		$this->data = [
			'isLogOn'		=>	$passport->uid > 0,
			'Uid'			=>	$passport->uid,
			'UserName'		=>	$passport->username,
			'NickName'		=>	$passport->nickname,
			'Avatar'		=>	$passport->avatar
		];
	}
}
