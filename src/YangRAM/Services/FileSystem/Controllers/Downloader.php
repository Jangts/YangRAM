<?php
namespace Files\Controllers;
use Controller;

class Downloader extends Controller {
    private $speed = 32;
	private $filePath;
	private $fileSize;
	private $mimeType;
	private $filename;

	public function __construct($filePath, $mimeType = null, $filename = null) {
		$this->filePath = $filePath;
		$this->fileSize = filesize($filePath);
		$this->mimeType = $mimeType ? $mimeType : 'application/octet-stream';
		$this->filename = $filename;
	}

	private function getRange() {
		if(isset($_SERVER['HTTP_RANGE']) && preg_match('/^bytes=(\d*)-(\d*)$/', $_SERVER['HTTP_RANGE'], $matches)){
			list ($all, $start, $end) = $matches;
			$start = $start == '' ? $this->fileSize - $end : $start;
			$end = $this->fileSize - 1;
			return array(
				'start'	=> $start,
				'end'	=> $end
			);
		}
		return NULL;
	}

	public function send() {
		$this->checkFileModification($this->filePath);
		$fileHandler = fopen($this->filePath, 'rb');
		$ranges = $this->getRange();
		header('Content-Type: '.$this->mimeType);
		if($this->filename){
			header('Content-Disposition: attachment; filename='.$this->filename);
		}
		if ($ranges) {
			header('HTTP/1.1 206 Partial Content');
			header('Accept-Ranges: bytes');
			header(sprintf('content-length:%u', $this->fileSize-$ranges['start']));
			header(sprintf('Content-Range: bytes %s-%s/%s', $ranges['start'], $ranges['end'], $this->fileSize));
			fseek($fileHandler, sprintf('%u', $ranges['start']));
		}else {
			header('HTTP/1.1 200 OK');
			header('Content-Length: '.$this->fileSize);
		}
		while(!feof($fileHandler) && !connection_aborted()) {
			echo fread($fileHandler, round($this->speed*1024, 0));
			flush();
			ob_flush();
		}
		if ($fileHandler != null) {
			fclose($fileHandler);
		}
	}

	public function setSpeed($speed){
        if(is_numeric($speed) && $speed>16 && $speed<4096){
            $this->speed = $speed;
        }
    }
}
