<?php
namespace Files\Controllers;
use Status;
use Controller;
use CM\SRC;
use CM\SRCLite;
use CM\SRC\Folder;

class Uploader extends Controller {
	private $file = NULL;
	private $name = '';
	private $mime = 'application/octet-stream';
	private $sourcetype = 'doc';
	private $SOURCETYPE = 'DOC';
	private $type = 'archive';
	private $suffix = 'zip';
	private $size = 0;
	private $path = NULL;
	private $time = NULL;
	private $info = NULL;
	public $appid = 'UPLOADER';
	public $id = NULL;
	public $fldid = '0';
	public $dir = '';
	
	public function main($name = 'myfile'){
		ignore_user_abort(true);
		set_time_limit(0);
		if(isset($_FILES[$name])){
			$post = $this->request->FORM->toArray();
			$this->read($_FILES[$name]);
		}else{
			new Status(403, true);
		}
	}
	
	public function read($file){
		if($file&&isset($file["error"])){
			if($file["error"] > 0){
				new Status('703.6'.$file["error"], 'File Upload Error', true);
			}else{
				$post = $this->request->FORM->toArray();
				$this->file = $file;
				$this->mime = $file["type"];
				$this->name = $file["name"];
				$this->hash = md5_file($this->file["tmp_name"]);
				$this->size = filesize($this->file["tmp_name"]);
				$this->path = $this->getPath();
				if(isset($post['id'])&&strlen($post['id'])===43){
					$this->resource = $this->write($post, true, $post['id']);
				}else{
					$this->resource = $this->write($post);
				}
			}
		}else{
			new Status(703, 'File Upload Error', 'File Not Found', true);
		}
	}
	
	public function has(){
		$post = $this->request->FORM->toArray();
		$this->hash = $post['hash'];
		$this->mime = $post['type'];
		$this->getDir();
		$model = 'CM\\SRC\\'.$this->SOURCETYPE;
		$srcid = $model::checkHash($this->hash);
		if($srcid){
			echo json_encode(array(
				'code'		=>	'Y',
				'srcid'		=>	$srcid,
				'srctype'	=>	$this->sourcetype
			));
		}
		else{
			echo json_encode(array(
				'code'		=>	'N'
			));
		}
	}
	
	public function sec(){
		$post = $this->request->FORM->toArray();
		$SRC_ID = $post['srcid'];
		$bases = SRCLite::bySrouce($SRC_ID, $post['srctype']);
		if(isset($bases[0])){
			if(isset($post['id'])&&strlen($post['id'])===43&&$obj = SRCLite::byId($post['id'], $post['srctype'])){
				$_SRC_ID 		=	$obj->SRC_ID;
				$obj->SRC_ID    =	$bases[0]->SRC_ID;
				$obj->FILE_SIZE =	$bases[0]->FILE_SIZE;
			}else{
				$obj = $bases[0]->cln();
				$obj->FLD_ID = $this->getFolder($post['fldid'], $post['dir'], $post['appid']);
				$obj->FILE_NAME = $post['filename'];
				$obj->ID = substr(substr($post['hash'], 8, 16).(BOOTTIME * 10000).uniqid(), 0, 44);
				$obj->KEY_IS_RECYCLED = 0;
			}
			if($obj->save()){
				$xtd = $obj->extend();
				$this->name = $xtd->FILE_NAME;
				$this->type = $xtd->FILE_TYPE;
				$this->size = $xtd->FILE_SIZE;
				$this->suffix = $xtd->SUFFIX;
				$this->hash = $xtd->HASH;
				$this->mime = $xtd->MIME;
				$this->getDir();
				if(isset($_SRC_ID)){
					$model = 'CM\\SRC\\'.$this->SOURCETYPE;
					$model::checkQuote($_SRC_ID);
				}
				return $this->r($xtd->toArray(), true);
			}else{
				return false;
			}
		}else{
			$base = SRCLite::create(array(
				'ID' 			=>	substr(substr($post['hash'], 8, 16).(BOOTTIME * 10000).uniqid(), 0, 44),
				'FLD_ID'		=>	$this->getFolder($post['fldid'], $post['dir'], $post['appid']),
				'SRC_ID'		=>	$SRC_ID,
				'FILE_NAME'		=>	$this->name,
				'FILE_TYPE'		=>	$this->type,
				'FILE_SIZE'		=>	$this->size,
				'SUFFIX'		=>	$this->suffix
			), $this->sourcetype);
			if($base->save()){
				$obj = $base->extend();
				return $this->r($obj->toArray(), true);
			}
			new Status(403, true);
		}
	}
	
	private function getPath(){
		$name_array = explode('.', $this->name);
		$this->suffix = $name_array[count($name_array) - 1];
		$dir = $this->getDir().date("Y-m-d").'/';
		if (!is_dir(PATH_PUB.$dir)) {
			mkdir(PATH_PUB.$dir);
		}
		$path = $dir.$this->hash.'.'.$this->suffix;
		move_uploaded_file($this->file["tmp_name"], PATH_PUB.$path);
		return $path;
	}
	
	private function getDir(){
		$type_array = explode('/', $this->mime);
		$this->type = $type_array[0];
		switch($this->type){
			case 'image':
			$this->sourcetype = 'img';
			$this->SOURCETYPE = 'IMG';
			return 'Images/';
			case 'audio':
			$this->sourcetype = 'wav';
			$this->SOURCETYPE = 'WAV';
			return 'Audios/';
			case 'video':
			$this->sourcetype = 'vod';
			$this->SOURCETYPE = 'VOD';
			return 'Videos/';
			case 'text':
			$this->sourcetype = 'txt';
			$this->SOURCETYPE = 'TXT';
			return 'Docs/';
			default:
			$this->sourcetype = 'doc';
			$this->SOURCETYPE = 'DOC';
			return 'Docs/';
		}
	}

	private function write($post, $update = false, $id = 0){
		switch($this->sourcetype){
			case 'img':
			$sourceinfo = $this->imageInfo();
			break;
			case 'wav':
			case 'vod':
			case 'txt':
			$sourceinfo = $this->mediaAndTextInfo($post);
			break;
			case 'doc':
			$this->filetype();
			$sourceinfo = $this->documentInfo();
			break;
		}
		if($post['hash']){
			$sourceinfo['HASH'] = $post['hash'];
		}
		if($update){
			SRC::update($id, $this->sourcetype, array(
				'FILE_SIZE'		=>	$this->size
			), $sourceinfo);
			exit('UPDATED');
		}else{
			$obj = SRC::create($this->sourcetype, array(
				'FLD_ID'		=>	$this->getFolder($post['fldid'], $post['dir'], $post['appid']),
				'FILE_NAME'		=>	$this->name,
				'FILE_TYPE'		=>	$this->type,
				'FILE_SIZE'		=>	$this->size,
				'SUFFIX'		=>	$this->suffix
			), $sourceinfo);
			return $this->r($obj->toArray(), false);
		}
	}

	private function filetype(){
		switch($this->suffix){
			case 'doc':
			case 'docx':
			case 'xls':
			case 'xlsx':
			case 'ppt':
			case 'pptx':
			case 'pdf':
			$this->type = 'document';
			break;
			case 'zip':
			case 'rar':
			case 'tar':
			case 'cab':
			case 'uue':
			case 'jar':
			case 'iso':
			case 'z':
			case '7-zip':
			case 'ace':
			case 'lzh':
			case 'arj':
			case 'gzip':
			case 'bz2':
			$this->type = 'compressed';
			break;
			default:
			$this->type = 'archive';
		}
	}
	
	private function imageInfo(){
		$size = getimagesize(PATH_PUB.$this->path);
		return array(
			'LOCATION' =>	 $this->path,
			'MIME' =>	 $this->mime,
			'IMAGE_SIZE' =>	 $size[3],
			'WIDTH' =>	 $size[0],
			'HEIGHT' =>	 $size[1]
		);
		
	}
	
	private function mediaAndTextInfo($post){
		return array(
			'LOCATION' =>	 $this->path,
			'MIME' =>	 $this->mime,
			'DURATION' =>	 isset($post["DURATION"]) ? $post["DURATION"] : 0,
			'WIDTH' =>	 0,
			'HEIGHT' =>	 0
		);
	}
	
	private function documentInfo(){
		return array(
		 	'LOCATION' =>	 $this->path,
		 	'MIME' =>	 $this->mime
		);
	}
	
	private function getFolder($fldid, $dir, $appid){
		switch($fldid){
			case '0':
			case '1':
			case '4':
			$fldid = $this->getAppFolder($appid);
			if($dir!=''){
				$path_array = explode('/', $dir);
				foreach($path_array as $name){
					$fldid = $this->getChildFolder($fldid, $name);
				}
			}
			break;
			case '2':
			$fldid = 5;
			break;
			case '3':
			$fldid = $this->getUserFolder();
			default:
			$fldid = $fldid;
		}
		return $fldid;
	}
	
	private function getAppFolder($appid){
		$folders = Folder::query(array(
			'parent'	=>	 4,
			'name'		=>	 $appid
		));
		if(count($folders)){
			return $folders[0]->id;
		}
		$folder = Folder::post(array(
			'parent'	=>	4,
			'name'		=>	$appid
		));
		return $folder->id;
	}
	
	private function getUserFolder(){
		$db = new SQL;
		$db->table = DB_SRC.'folders';
		$db->select = 'id';
		$db->require = array(
		 'parent'		=>	 3,
		 'name'		=>	 $appid,
		);
		$query = $db->get();
		$row = mysqli_num_rows($query);
		if($row){
			return $row["id"];
		}
		else{
			$db->insert = array(
				'parent'		=>	3,
				'name'			=>	$appid,
				'KEY_MTIME'	=>	$this->time,
				'usr_id'		=>	 0,
			);
			$db->add();
			return $db->index;
		}
	}
	
	private function getChildFolder($parent, $name){
		$db = new SQL;
		$db->table = DB_SRC.'folders';
		$db->select = 'id';
		$db->require = array(
			'parent'		=>	$parent,
			'name'		=>	$name,
		);
		$query = $db->get();
		$row = mysqli_num_rows($query);
		if($row){
			return $row["id"];
		}
		else{
			$db->insert = array(
				'parent'		=>	3,
				'name'		=>	$parent,
				'KEY_MTIME'		=>	$this->time,
				'usr_id'		=>	 0,
			);
			$db->add();
			return $db->index;
		}
	}
	
	private function r($info, $secupl = false){
		if(isset($_GET['returntype'])){
			$file = array(
				'host'		=>	HTTP,
				'url'		=>	__GET_DIR.'files/'.$this->sourcetype.'/'.$info["ID"].'.'.$this->suffix,
				'name'		=>	$this->name,
				'type'		=>	$this->mime,
				'size'		=>	$this->size,
				'time'		=>	$info["KEY_MTIME"]
			);
			switch($this->sourcetype){
				case 'img':
				$file["dimen"] = $info["IMAGE_SIZE"];
				$file["width"] = $info["WIDTH"];
				$file["height"] = $info["HEIGHT"];
				break;
				case 'wav':
				case 'vod':
				$file["DURATION"] = $info["DURATION"];
				break;
			}
			$data = array(
				'code'		=>	200,
				'status'	=>	$secupl ? 'SECOND PASS' : 'UPLOADED',
				'file'		=>	$file
			);
			switch($_GET['returntype']){
				case 'xml':
				#
				break;
				case 'json':
				exit(json_encode($data));
			}
		}
		if($secupl){
			exit('SECOND PASS');
		}
		exit('UPLOADED');
	}
}