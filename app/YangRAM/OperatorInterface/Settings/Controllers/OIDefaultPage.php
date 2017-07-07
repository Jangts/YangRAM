<?php
namespace Settings\Controllers\OI;

class DefaultPage extends \OIC\OICtrller_BC {

	public function main(){
		exit($this->hello());
	}

	private function hello(){
    return '<view title="Publisher" lang="zh-cn"><top-vision bgcolor="silvery"><v><ico></ico><ttl>My Application</ttl></v></top-vision><v bgcolor="white" style="line-height:300px; text-align:center; font-size:48px;">Hello, World!</v></view>';
	}
}
