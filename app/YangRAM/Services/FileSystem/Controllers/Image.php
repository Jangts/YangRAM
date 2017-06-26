<?php
namespace Files\Controllers;
use Status;
use Response;
use Controller;
use CM\SRC;
use Library\graphics\ImagePrinter;

class Image extends Controller {
	private $suffix, $filename;

	public function main($id){
		$requests = explode("_", $id);
		$basename = explode(".", $requests[0]);
		if(isset($basename[1])){
			$id = $basename[0];
			$src = SRC::byId($id, 'img');
			if($src&&$row = $src->toArray()){
				$this->filename = trim(PATH_PUB.$row["LOCATION"]);
				$this->suffix = $basename[1];
				if(is_file(PATH_PUB.$row["LOCATION"])){
					$this->filetype = $row["MIME"];
					$this->basename = $row["FILE_NAME"];
					if(_PIC_SECURITY_CHAIN_&&isset($_SERVER['HTTP_REFERER'])){
						$whiteList = array(HOST);
						$preg = "/^(http:)?\/\/(".join('|',$whiteList).")?(\/.*)?$/";
						if(!preg_match($preg, $_SERVER['HTTP_REFERER'])){
							$this->filetype = 'image/jpeg';
							Status::forbidden();
							$this->filename = PATH_PUB.'Images/Forbiddance/'.SYS::$user->lang.'/forbiddance.jpg';
							return self::write();
						}
					}
					$this->cacheResource($this->filename, _CACHE_PIC_CLIENT_EXPIRY_);
					if(!empty($requests[1])){
						$this->orgWidth = $row["WIDTH"];
						$this->orgHeight = $row["HEIGHT"];
						$this->resize = explode(".", $requests[1]);
						return $this->reSizePicture();
					}
					return $this->writePicture();
				}
				SRC::delete("`ID` = '$id'");
			}
		}
		new Status(404, true);//Status::notFound();
	}

	private function writePicture(){
		if ($this->filename&&file_exists($this->filename)){
			switch($this->filetype){
				case "image/jpeg":
				case "image/pjpeg":
				return ImagePrinter::JPG($this->filename);
				break;
				case "image/png":
				return ImagePrinter::PNG($this->filename);
				break;
				case "image/gif":
				return ImagePrinter::GIF($this->filename);
				break;
			}
		}
		new Status(404, true);//Status::notFound();
	}

	private function reSizePicture(){
		if ($this->filename&&file_exists($this->filename)){
			if(empty($this->resize[1])||$this->resize[1]==$this->suffix){
				$resize = explode("x", $this->resize[0]);
				if(is_numeric($resize[0])&&is_file($this->filename)){
					$imgWidth = $resize[0];
					if(isset($resize[1])&&is_numeric($resize[1])){
						$imgHeight = $resize[1];
					}elseif(isset($resize[1])&&!is_numeric($resize[1])){
						new Status(404, true);//Status::notFound();
					}else{
						$imgHeight = $imgWidth / $this->orgWidth * $this->orgHeight;
					}
					if(isset($resize[2])){
						new Status(404, true);//Status::notFound();
					}
					switch($this->filetype){
						case "image/jpeg":
						case "image/pjpeg":
						return ImagePrinter::JPG($this->filename, $imgWidth, $imgHeight, $this->orgWidth, $this->orgHeight);
						break;
						case "image/png":
						return ImagePrinter::PNG($this->filename, $imgWidth, $imgHeight, $this->orgWidth, $this->orgHeight);
						break;
						case "image/gif":
						return ImagePrinter::GIF($this->filename, $imgWidth, $imgHeight, $this->orgWidth, $this->orgHeight);
						break;
					}
				}
			}
		}
		new Status(404, true);//Status::notFound();
	}
}
