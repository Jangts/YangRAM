<?php
namespace Library\graphics;

class ImagePrinter {
	public static function JPG($imgsrc, $imgWidth=Null, $imgHeight=Null, $orgWidth=Null, $orgHeight=Null) {
		if($imgWidth==Null){
			self::showJPG($imgsrc);
		}else{
			self::reSizeJPG($imgsrc, $imgWidth, $imgHeight, $orgWidth, $orgHeight);
		}
		die;
	}

    private static function showJPG($imgsrc) {
        header("Content-type: image/jpeg");
        $im = imagecreatefromjpeg($imgsrc);
        imagejpeg($im, NULL, 100);
        imagedestroy($im);
    }

	private static function reSizeJPG($imgsrc, $imgWidth, $imgHeight, $orgWidth, $orgHeight) {
        header("Content-type: image/jpeg");
        $orgim = imagecreatefromjpeg($imgsrc);
        $im = imagecreatetruecolor($imgWidth, $imgHeight);
        if (!$im) {
            //
        } else {
            imagecopyresampled($im, $orgim, 0, 0, 0, 0, $imgWidth, $imgHeight, $orgWidth, $orgHeight);
            imagejpeg($im);
            imagedestroy($im);
        }
    }

	public static function PNG($imgsrc, $imgWidth=Null, $imgHeight=Null, $orgWidth=Null, $orgHeight=Null) {
		if($imgWidth==Null){
			self::showPNG($imgsrc);
		}else{
			self::reSizePNG($imgsrc, $imgWidth, $imgHeight, $orgWidth, $orgHeight);
		}
		die;
	}

    private static function showPNG($imgsrc) {
		header("Content-type: image/png");
        $im = @imagecreatefrompng($imgsrc);
        $background = @imagecolorallocate($im, 0, 0, 0);
        @imagecolortransparent($im, $background);
        @imagealphablending($im, false);
        @imagesavealpha($im, true);
        @imagepng($im);
        @imagedestroy($im);
    }

	private static function reSizePNG($imgsrc, $imgWidth, $imgHeight, $orgWidth, $orgHeight) {
        header("Content-type: image/png");
        $orgim = imagecreatefrompng($imgsrc);
        $im = imagecreatetruecolor($imgWidth, $imgHeight);
        $background = imagecolorallocate($im, 0, 0, 0);
        imagecolortransparent($im, $background);
        imagealphablending($im, false);
        imagesavealpha($im, true);
        if (!$im) {
            //

        } else {
            imagecopyresampled($im, $orgim, 0, 0, 0, 0, $imgWidth, $imgHeight, $orgWidth, $orgHeight);
            imagepng($im);
            imagedestroy($im);
        }
    }

	public static function GIF($imgsrc, $imgWidth=Null, $imgHeight=Null, $orgWidth=Null, $orgHeight=Null) {
		if($imgWidth==Null){
			self::showGIP($imgsrc);
		}else{
			self::reSizeGIP($imgsrc, $imgWidth, $imgHeight, $orgWidth, $orgHeight);
		}
		die;
	}

    private static function showGIF($imgsrc) {
        header("Content-type: image/gif");
        $im = imagecreatefromgif($imgsrc);
        $background = imagecolorallocate($im, 0, 0, 0);
        imagesavealpha($im, true);
        imagecolortransparent($im, $background);
        imagegif($im);
        imagedestroy($im);
    }

    private static function reSizeGIF($imgsrc, $imgWidth, $imgHeight, $orgWidth, $orgHeight) {
        header("Content-type: image/gif");
        $orgim = imagecreatefromgif($imgsrc);
        $im = imagecreatetruecolor($imgWidth, $imgHeight);
        $background = imagecolorallocate($im, 0, 0, 0);
        imagecolortransparent($im, $background);
        imagealphablending($im, false);
        imagesavealpha($im, true);
        if (!$im) {
            //

        } else {
            imagecopyresampled($im, $orgim, 0, 0, 0, 0, $imgWidth, $imgHeight, $orgWidth, $orgHeight);
            imagegif($im);
            imagedestroy($im);
        }
    }
}