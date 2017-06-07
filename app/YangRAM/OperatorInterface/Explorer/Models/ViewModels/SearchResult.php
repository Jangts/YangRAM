<?php
namespace Explorer\Models\ViewModels;

use System\NIDO\DataObject;
use CM\SPC\Preset;
use Library\ect\SearchEngine;

class SearchResult extends DataObject {
    use traits\src_writer;
	use traits\spc_writer;

    private $kw;
	private $rules;

	public function __construct($localdict, $kw){
        $this->kw = $kw;
		if(file_exists(PATH_PUB.'SmartianSearchRules/'.AI_CURR.'.json')){
			if($rules=json_decode(file_get_contents(PATH_PUB.'SmartianSearchRules/'.AI_CURR.'.json'), true)){
				$this->rules = $rules;
			}else{
                # 生成规范
            }
		}else{
            # 生成规范
        }
        foreach($this->rules as $type=>$rule){
			$array = $this->searchRule($rule);			
			if($array){
                $this->collectResults($localdict->toArray(), $type, array(
					'Item'	=>	$rule["Item"],
					'Rslt'	=>	$array
				));
			}
		}
	}

    private function searchRule($rule){
		$search = new SearchEngine($rule);
		$search->search($this->kw);
		return $search->getRS();
	}
	
	private function collectResults($localdict, $type, $item){	
		switch($type){
			case 'src_folders':
			return $this->getFolders($localdict, $item["Rslt"]);
			break;
			case 'src_img':
			return $this->getPictures($localdict, $item["Rslt"]);
			break;
			case 'src_txt':
			return $this->getMediaAndTexts($localdict, $item["Rslt"], 'txt');
			break;
			case 'src_wav':
			return $this->getMediaAndTexts($localdict, $item["Rslt"], 'wav');
			break;
			case 'src_vod':
			return $this->getMediaAndTexts($localdict, $item["Rslt"], 'vod');
			break;
			case 'src_doc':
			return $this->getDocuments($localdict, $item["Rslt"]);
			break;
			default:
			return $this->getSPC($localdict, $item["Rslt"]);
		}
	}
	
	private function getFolders($localdict, $array){
		foreach($array as $row){
			$href = 'src/all/'.$row["id"].'/';
            $this->writeFolders($localdict, $row, $href, 'readonly');
		}
	}
	
	private function getPictures($localdict, $array){
		foreach($array as $row){
            $this->writePictures($localdict, $row, 'readonly');
		}
	}
	
	private function getMediaAndTexts($localdict, $array, $type){
		foreach($array as $row){
			$this->writeMediaAndTexts($localdict, $row, $type, 'readonly');
		}
	}
	
	private function getDocuments($localdict, $array){
		foreach($array as $row){
			$this->writeDocuments($localdict, $row, 'readonly');
		}
	}
	
	private function getSPC($localdict, $array){
		foreach($array as $row){
			$preset = $row['SET_ALIAS'];
			$presetinfo = Preset::alias($preset);
            $this->writeContents($localdict, $preset, $presetinfo, $row);
        }
	}

	public function render($localdict){
        if(count($this->data)){
			return join('', $this->data);
		}else{
			return '<el>'.$localdict->noresult.'</el>';
		}
	}
}
