<?php
namespace Library\graphics;

class Captchas {
	public $angle = false,
	$updwon = false,
	$line = 0,
	$dot = 0,
	$width = 100,
	$height = 30,
	$length = 4,
	$char = "default",
	$code = Null;
	
	public function PNG() {
		if($this->code==Null&&$this->length>0){
			$this->code = $this->getCode();
		}elseif($this->code==Null&&$this->length==0){
			$this->length = 4;
			$this->code = $this->getCode($this->length);
		}elseif($this->code!=Null&&strlen($this->code)!=$this->length){
			$this->length = strlen($this->code);
		}
		$_SESSION["verfyCode"] = $this->code;
		$this->showCodePNG();
	}
	
	private function getCode(){
		switch($this->char){
			case "":
			$pattern = "";
			break;
			default:
			$pattern = "1234567890ABCDEFGHIJKLOMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
			break;
		}
		$key = "";
		$len = strlen($pattern);
		for($i=0;$i<$this->length;$i++) {
			$key .= $pattern{mt_rand(0,$len-1)};
		}
		return $key;
	}
	
	private function showCodePNG(){
		$width = $this->width;
		$height = $this->height;
		$length = $this->length;
		$code = $this->code;
		$fontSize = floor($height*3/5);
		$fontWidth = floor($width/$length);
		$fontWidth>=floor($height*3/5)?$fontSize = floor($height*3/5):$fontSize = $fontWidth;
		header("Content-type:image/png");
		$img = imagecreate($width, $height);
		$bgcolor = imagecolorallocate($img, 240, 240, 240);
		$rectangelcolor = imagecolorallocate($img, 150, 150, 150);
		imagerectangle($img, 1, 1, $width-1, $height-1, $rectangelcolor);
		for($i=0;$i<$length;$i++){
			$codecolor = imagecolorallocate($img, mt_rand(50, 200), mt_rand(50, 128), mt_rand(50, 200));
			if($this->angle) $angle = mt_rand(-20,20);
			else $angle = 0;
			$charx = $i*($fontWidth)+$fontWidth/$length;
			if($this->updwon) $chary = ($height+$fontSize)/2+mt_rand(-1,1);
			else $chary = ($height+$fontSize)/2;
			imagettftext($img, $fontSize, $angle, $charx, $chary, $codecolor, PATH_COMN."Fonts/AVGARDM.TTF", $code[$i]);
		}
		for($i=0;$i<$this->line;$i++){
			$linecolor = imagecolorallocate($img,mt_rand(0, 250),mt_rand(0, 250),mt_rand(0, 250));
			$linex = mt_rand(1, $width-1);
			$liney = mt_rand(1, $height-1);
			imageline($img, $linex, $liney, $linex+mt_rand(0,4)-2, $liney+mt_rand(0, 4)-2, $linecolor);
		}
		for($i=0;$i<$this->dot;$i++){
			$pointcolor = imagecolorallocate($img, mt_rand(0, 250), mt_rand(0, 250), mt_rand(0, 250));
			imagesetpixel($img, mt_rand(1, $width-1), mt_rand(1, $height-1), $pointcolor);
		}
		/**ob_clean();**/
		imagepng($img);
	}
}