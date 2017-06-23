<?php
namespace Files\Controllers;
use Status;
use Response;
use Controller;
use CM\SRC;

class Document extends Controller {
	private $suffix, $filename;

	public function doc($id){
		$this->main($id, 'doc');
	}

	public function txt($id){
		$this->main($id, 'txt');
	}
	
	public function vod($id){
		$this->main($id, 'vod');
	}
	
	public function wav($id){
		$this->main($id, 'wav');
	}

	private function main($id, $type){
		$basename = explode(".", $id);
		if(isset($basename[1])){
			$id = $basename[0];
			$src = SRC::byId($id, $type);
			if($src&&$row = $src->toArray()){
				$this->filename = trim(PATH_PUB.$row["LOCATION"]);
				$this->suffix = $basename[1];
				if(is_file(PATH_PUB.$row["LOCATION"])){
					$this->filetype = $row["MIME"];
					$this->basename = $row["FILE_NAME"];
					return $this->write($type);
				}
				Files::removeDocument(strtolower($this->appid), $id);
			}
		}
		new Status(404, true);//Status::notFound();
	}

	private function write($type){
		switch($type){
			case "txt":
			case "wav":
			case "vod":
			$this->cacheResource($this->filename);
			$this->writeMediaAndText();
			break;
			case "doc":
			$this->writeDocument();
			break;
		}
	}

	private function writeMediaAndText(){
		if ($this->filename&&file_exists($this->filename)){
			set_time_limit(0);
			if(isset($this->request->PARAMS->download)){
				$transfer = new Downloader($this->filename, $this->filetype, $this->basename);
			}else{
				$transfer = new Downloader($this->filename, $this->filetype);
			}
			$transfer->send();
			exit;
		}
		new Status(404, true);//Status::notFound();
	}

	private function writeDocument(){
		if ($this->filename&&file_exists($this->filename)){
			set_time_limit(0);
			if(isset($this->request->PARAMS->readonly)){
				$transfer = new Downloader($this->filename, $this->filetype);
			}else{
				$transfer = new Downloader($this->filename, $this->filetype, $this->basename);
			}
			$transfer->send();
			exit;
		}
		new Status(404, true);//Status::notFound();
	}
}
