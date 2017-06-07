<?php
namespace Files\Controllers;
use Response;
use Controller;

class Document extends Controller {
	private $suffix, $filename;

	public function wav($id){
		$this->main($id, 'wav');
	}

	private function main($id, $type){
		$basename = explode(".", $id);
		if(isset($basename[1])){
			$alias = $basename[0];
			$src = SRC::byId($id, $type);
			if($src&&$row = $src->toArray()){
				$this->filename = trim(PATH_PUB.$row["LOCATION"]);
				$this->suffix = $basename[1];
				if(is_file(PATH_PUB.$row["LOCATION"])){
					$this->filetype = $row["MIME"];
					$this->basename = $row["FILE_NAME"];
					return $this->write($type);
				}
			}
		}
		new Status(404, true);//Status::notFound();
	}

	public function show(){
		if(isset($this->request[3])&&$this->request[3]!=""){
			switch($this->appid){
				case "VRC":
				return $this->writeVCode();
				break;
				case "RQC":
				return $this->writeRCode();
				break;
				default:

			}
		}
		new Status(404, true);//Status::notFound();
	}

	private function writeVCode(){
		require_once PATH_LIB."Imager/V_Code".CLS;
		$CODE = new Captchas();
		$CODE->angle = true;
		$CODE->updwon = true;
		$CODE->line = 30;
		$CODE->dot = 120;
		if(isset($this->request[3])&&$this->request[3]!=""){
			$array = explode("_", $this->request[3]);
			if(intval($array[0])!=0) $CODE->width = intval($array[0]);
			if(isset($array[1])&&intval($array[1])!=0) $CODE->height = intval($array[1]);
			if(isset($array[2])) $CODE->length = intval($array[2]);
			if(isset($array[3])) $CODE->char = intval($array[3]);
			if(isset($this->request->PARAMS->code)) $CODE->code = sprintf('%s', $this->request->PARAMS->code);
		}
		$CODE->PNG();
	}

	private function writeRCode(){
		if(isset($this->request[3])&&$this->request[3]!=""){
			$data = "http://www.yangram.com/ERROR/404/";
			$level = "H";
			$size = 10;
			$margin = 3;
			if(isset($this->request->PARAMS->data)) $data = sprintf('%s', $this->request->PARAMS->data);
			if(isset($this->request[3])&&$this->request[3]!="") $level = $this->request[3];
			if(isset($this->request[4])&&$this->request[4]!="") $size = $this->request[4];
			if(isset($this->request[5])&&$this->request[5]!="") $margin = $this->request[5];
			$filename = PATH_CACHE."RQCode/".hash('md4', $data, FALSE)."_".$level."_".$size."_".$margin.".png";
			require_once PATH_LIB."Imager/QRCode".CLS;
			QRCode::png($data, false, "QR_ECLEVEL_".$level, $size, $margin);
		}
		new Status(404, true);//Status::notFound();
	}

	private function write($type){
		switch($type){
			case "pic":
			$this->writePicture();
			break;
			case "txt":
			case "wav":
			case "vod":
			$this->writeMediaAndText();
			break;
			case "doc":
			$this->writeDocument();
			break;
		}
	}

	private function writePicture(){
		if ($this->filename&&file_exists($this->filename)){
			switch($this->filetype){
				case "image/jpeg":
				case "image/pjpeg":
				return PIC::JPG($this->filename);
				break;
				case "image/png":
				return PIC::PNG($this->filename);
				break;
				case "image/gif":
				return PIC::GIF($this->filename);
				break;
			}
		}
		new Status(404, true);//Status::notFound();
	}

	private function writeMediaAndText(){
		if ($this->filename&&file_exists($this->filename)){
			require_once PATH_LIB.'Transfer'.CLS;
			set_time_limit(0);
			if(isset($this->request->PARAMS->download)){
				$transfer = new Transfer($this->filename, $this->filetype, $this->basename);
			}else{
				$transfer = new Transfer($this->filename, $this->filetype);
			}
			$transfer->send();
			exit;
		}
		new Status(404, true);//Status::notFound();
	}

	private function writeDocument(){
		if ($this->filename&&file_exists($this->filename)){
			require_once PATH_LIB.'Transfer'.CLS;
			set_time_limit(0);
			if(isset($this->request->PARAMS->readonly)){
				$transfer = new Transfer($this->filename, $this->filetype);
			}else{
				$transfer = new Transfer($this->filename, $this->filetype, $this->basename);
			}
			$transfer->send();
			exit;
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
						return PIC::JPG($this->filename, $imgWidth, $imgHeight, $this->orgWidth, $this->orgHeight);
						break;
						case "image/png":
						return PIC::PNG($this->filename, $imgWidth, $imgHeight, $this->orgWidth, $this->orgHeight);
						break;
						case "image/gif":
						return PIC::GIF($this->filename, $imgWidth, $imgHeight, $this->orgWidth, $this->orgHeight);
						break;
					}
				}
			}
		}
		new Status(404, true);//Status::notFound();
	}
}
