<?php
namespace Explorer\Models\ViewModels;
use Tangram\NIDO\DataObject;
use CM\SRC\Folder;
use CM\SRCLite;

class SRC extends DataObject {
	use traits\src_writer;

    public function __construct($localdict, $filetype, $folder){
		if($filetype==NULL){
			$content = new DefaultPage($localdict, 'src');
            return $content->render();
		}
		$localdict = $localdict->toArray();
		if($folder){
			$folder = $folder;
		}else{
			$folder = 0;
		}
		$path = 'src/'.$filetype.'/';
		if(isset($_GET["lo"])){
			switch($_GET["lo"]){
				case 'nd':
				$orderby = Folder::NAME_DESC;
				break;
				case 'ta':
				$orderby = Folder::MTIME_ASC;
				break;
				case 'td':
				$orderby = Folder::MTIME_DESC;
				break;
				default:
				$orderby = Folder::NAME_ASC;
			}
			$array = Folder::children($folder, $orderby);
		}else{
			$array = Folder::children($folder, Folder::NAME_ASC);
		}
		foreach($array as $row){
			$href = $path.$row->id.'/';
			$this->writeFolders($localdict, $row->toArray(), $href);
		}
		$this->query($localdict, $filetype, $folder);
	}
	
	private function query($localdict, $type, $folder = 0){
		switch($type){
			case 'all':
			$this->getAllFiles($localdict, $folder);
			break;
			case 'img':
			$this->getPictures($localdict, $folder);
			break;
			case 'txt':
			case 'wav':
			case 'vod':
			$this->getMediaAndTexts($localdict, $folder, $type);
			break;
			case 'doc':
			case 'zip':
			case 'ect':
			$this->getDocuments($localdict, $folder, $type);
			break;
		};
	}
	
	private function getFiles($type, $folder = 0){
		if(isset($_GET["lo"])){
			switch($_GET["lo"]){
				case 'nd':
				$orderby = SRCLite::FNAME_DESC;
				break;
				case 'ta':
				$orderby = SRCLite::MTIME_ASC;
				break;
				case 'td':
				$orderby = SRCLite::MTIME_DESC;
				break;
				case 'sa':
				$orderby = SRCLite::FSIZE_ASC;
				break;
				case 'sd':
				$orderby = SRCLite::FSIZE_DESC;
				break;
				default:
				$orderby = SRCLite::FNAME_ASC;
			}
			return SRCLite::byFolder($folder, $type, $orderby);
		}else{
			return SRCLite::byFolder($folder, $type, SRCLite::FNAME_ASC);
		}
	}
	
	private function getAllFiles($localdict, $folder = 0){
		$array = $this->getFiles('all', $folder);
		foreach($array as $row){
			switch($row->FILE_TYPE){
				case 'image':
				$this->writePictures($localdict, $row->toArray());
				break;
				case 'text':
				$this->writeMediaAndTexts($localdict, $row->toArray(), 'txt');
				break;
				case 'audio':
				$this->writeMediaAndTexts($localdict, $row->toArray(), 'wav');
				break;
				case 'video':
				$this->writeMediaAndTexts($localdict, $row->toArray(), 'vod');
				break;
				default:
				$this->writeDocuments($localdict, $row->toArray());
			}
		}
	}
	
	private function getPictures($localdict, $folder = 0){
		$array = $this->getFiles('img', $folder);
		foreach($array as $row){
			$this->writePictures($localdict, $row->toArray());
		}
	}

	private function getMediaAndTexts($localdict, $folder = 0, $type){
		$array = $this->getFiles($type, $folder);
		foreach($array as $row){
			$this->writeMediaAndTexts($localdict, $row->toArray(), $type);
		}
	}
	
	private function getDocuments($localdict, $folder = 0){
		$array = $this->getFiles('doc', $folder);
		foreach($array as $row){
			$this->writeDocuments($localdict, $row->toArray());
		}
	}

	public function render($localdict){
        if(count($this->data)){
			return join('', $this->data);
		}else{
			return '<el>'.$localdict->empty.'</el>';
		}
	}
}
