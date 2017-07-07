<?php
namespace UOI\Controllers;
use Status;
use Response;
use Controller;
use Library\compliers\JSMin;

class Runtime extends Controller {
	private function renderer($filenames){
		$response = $this->setHeaders(200);
		$content = '';
		foreach($filenames as $filename){
			$content .= "\r\n\r\n".file_get_contents($filename);
		}
		if(_USE_DEBUG_MODE_){
			$response->send($content);
		}else{
			$response->send(JSMin::minify($content));
		}		
	}

	private function setHeaders($code) {
		$response = Response::instance($code);
		$response->MIME = 'application/javascript';
		$response->setResourceCache();
		return $response;
	}

	public function evn($lang){
		$filenames = $this->getFileForEvn($lang);
		if($filenames){
			$this->renderer($filenames);
		}else{
			$this->setHeaders(304)->send();
		}
	}	

	public function log($lang){
		$filenames = $this->getFileForLog($lang);
		if($filenames){
			$this->renderer($filenames);
		}else{
			$this->setHeaders(304)->send();
		}
	}

	public function sys($lang){
		$filenames = $this->getFileForSys($lang);
		if($filenames){
			$this->renderer($filenames);
		}else{
			$this->setHeaders(304)->send();
		}
	}

	private function getFileForEvn($lang) {
		//Status::langExists($lang, PATH_LANGS.'{{L}}/runtime.js')
		$array = array(
			Status::langExists($lang, PATH_LANGS.'{{L}}/runtime.js'),
			PATH_UOI.'Sources/scripts/OIMLElement.js',
			PATH_UOI.'Sources/scripts/Browser.js',
			PATH_UOI.'Sources/scripts/TitleAndMenu.js',
			PATH_UOI.'Sources/scripts/ContextMenu.js',
			PATH_UOI.'Sources/scripts/HiBar.js',
			PATH_UOI.'Sources/scripts/ProcessBus.js',
			PATH_UOI.'Sources/scripts/Workspace.js',
			PATH_UOI.'Sources/scripts/MagicCube.js',
			PATH_UOI.'Sources/scripts/Dialog.js',
			PATH_UOI.'Sources/scripts/Logger.js',
			PATH_UOI.'Sources/scripts/Locker.js',
			PATH_UOI.'Sources/scripts/Uploader.js',
			PATH_UOI.'Sources/scripts/Explorer.js',
			PATH_UOI.'Sources/scripts/TimePicker.js',
			PATH_UOI.'Sources/scripts/TimePickerBuilders.js',
			PATH_UOI.'Sources/scripts/Kalendar.js',
			PATH_UOI.'Sources/scripts/KalendarEvent.js',
			PATH_UOI.'Sources/scripts/Smartian.js',
			PATH_UOI.'Sources/scripts/Launcher.js',
			PATH_UOI.'Sources/scripts/RankingList.js',
			PATH_UOI.'Sources/scripts/ARLCxtMenus.js',
			PATH_UOI.'Sources/scripts/Memowall.js',
			PATH_UOI.'Sources/scripts/BookmarkModel.js',
			PATH_UOI.'Sources/scripts/BookmarkGroup.js',
			PATH_UOI.'Sources/scripts/Notifier.js',
			PATH_UOI.'Sources/scripts/Message.js',
			PATH_UOI.'Sources/scripts/Runtime.js'
		);
		foreach($array as $filename){
			if($this->checkFileModification($filename)){
				return $array;
			}
		}
		return NULL;
	}

	private function getFileForLog($lang) {
		$array = array(
			Status::langExists($lang, PATH_LANGS.'{{L}}/runtime.js'),
			PATH_UOI.'Sources/scripts/Logger.js',
			PATH_UOI.'Sources/scripts/Runtime.js'
		);
		foreach($array as $filename){
			if($this->checkFileModification($filename)){
				return $array;
			}
		}
		return NULL;
	}

	private function getFileForSys($lang) {
		$array = array(
			PATH_UOI.'Sources/scripts/System.js',
			PATH_UOI.'Sources/scripts/Application.js',
			PATH_UOI.'Sources/scripts/ApplicationAPIs.js',
			PATH_UOI.'Sources/scripts/YangRAM.js',
			PATH_UOI.'Sources/scripts/YangRAMAPIs.js',
			PATH_UOI.'Sources/scripts/CrossPlatform.js'
		);
		foreach($array as $filename){
			if($this->checkFileModification($filename)){
				return $array;
			}
		}
		return NULL;
	}
}
