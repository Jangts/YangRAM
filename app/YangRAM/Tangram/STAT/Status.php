<?php
namespace Tangram\STAT;
use Exception;
use Tangram\CTRL\Response;
use Tangram\CTRL\Request;

/**
 *	Status Code Processor
 *	状态码处理器
 *	处理包括HTTP Status和YangRAM Status在内的各种状态的抛出和记录
 */
final class Status extends Exception {
	const
	CAST		= 0,
	CAST_XML	= 1,
    CAST_JSON	= 2,
	CAST_TXT	= 3,
	CAST_PAGE	= 4,
	CAST_XLOG	= 5,
    CAST_JLOG	= 6,
	CAST_TLOG	= 7,
	CAST_PLOG	= 8,
	CAST_LOG	= 9,

	TEMP_GREEN_CHANNEL 	= PATH_TNI.'STAT/Templates/200.php',
	TEMP_400 			= PATH_TNI.'STAT/Templates/400.php',
	TEMP_YELLOW 		= PATH_TNI.'STAT/Templates/400.php',
	TEMP_404 			= PATH_TNI.'STAT/Templates/404.php',
	TEMP_ALPHA_CHANNEL 	= PATH_TNI.'STAT/Templates/404.php',
	TEMP_700 			= PATH_TNI.'STAT/Templates/700.php',
	TEMP_NACARAT 		= PATH_TNI.'STAT/Templates/700.php',
	TEMP_701 			= PATH_TNI.'STAT/Templates/701.php',
	TEMP_RED 			= PATH_TNI.'STAT/Templates/701.php',
	TEMP_707 			= PATH_TNI.'STAT/Templates/707.php',
	TEMP_AZURE			= PATH_TNI.'STAT/Templates/707.php',
	TEMP				= PATH_TNI.'STAT/Templates/707.php',
	TEMP_708 			= PATH_TNI.'STAT/Templates/708.php',
	TEMP_MAGENTA		= PATH_TNI.'STAT/Templates/708.php',
	TEMP_SERVER_ERRORS 	= PATH_TNI.'STAT/Templates/797.php';

	public static $sattuscodes = null;

	public static function init(){
		if(self::$sattuscodes===null){
			if(is_file(PATH_TNI.'STAT/codes.json')){
				self::$sattuscodes = json_decode(file_get_contents(PATH_TNI.'STAT/codes.json'), true);
			}else{
				exit('Cannot Found Status Code Map');
			}
		}
	}

	public static function send($code){
		new self($code, '', '', true);
	}

	public static function forbidden(){
		new self(403, true);
	}

	public static function notFound(){
		new self(404, true);
	}

	public function __construct($code, $message = '', $content = '', $cast = false, $log = false){
		$this->code = '707';
		$this->intc = 707;
		$this->alias = 'UNKNOW_STATUS';
		$this->content = '(none)';
		if(is_numeric($code)){
			$this->code = (string)$code;
			$this->intc = intval($code);
			$this->alias = 'UNDEFINED_STATUS_'.$code;
			if(is_string($message)){
				if(empty($message)){
					$this->message = isset(self::$sattuscodes[$this->intc])
											? self::$sattuscodes[$this->intc]
											: self::$sattuscodes[707];
				}else{
					$this->message = $message;
				}
				if(is_string($content)){
					empty($content) or $this->content = $content;
				}elseif(is_bool($content)){
					$log = $cast;
					$cast = $content;
					
				}
			}else{
				$this->message = isset(self::$sattuscodes[$this->intc])
										? self::$sattuscodes[$this->intc]
										: self::$sattuscodes[707];
				if(is_bool($message)){
					$log = $content;
					$cast = $message;
				}
			}
		}elseif(is_string($code)){
			$this->message = $code;
			if(is_string($message)){
				empty($message) or $this->content = $message;
			}elseif(is_bool($message)){
				$log = $content;
				$cast = $message;
			}
		}elseif(is_bool($code)){
			$log = $message;
			$cast = $code;
		}
		if($cast===true){
			if($log===true){
				$this->log();
			}
			return $this->cast();
		}
	}

	public function write($log){
		if($this->content==='(none)'){
			$this->content = $log;
		}else{
			$this->content .= "<br/>".$log;
		}
	}

	public function console(){
		$array = [
			'tag' => 'console',
			'attr' => [],
			'value' => []
		];
		if(defined('_USE_DEBUG_MODE_')&&_USE_DEBUG_MODE_){
			$array['attr']['status'] = 'log';
			$array['value'] = [
				[
					'tag' => 'message',
					'attr' => ['code' => $this->code],
					'value' => $this->message
				],
				[
					'tag' => 'position',
					'attr' => ['line' => $this->line],
					'value' => Response::trimServerFilename($this->file)
				],
				[
					'tag' => 'trace',
					'value' => []
				]
			];
			$tracedata = $this->getTrace();
			foreach($tracedata as $n=>$p){
				$array['value'][2]['value'][] = [
					'tag' => 'method',
					'attr' => ['filename' => Response::trimServerFilename($p['file'])],
					'value' => $p['class'].'::'.$p['function'].'()'
				];
			}
		}else {
			$array['attr']['status'] = 'err';
			$array['value'][] = [
				'tag' => 'message',
				'value' => 'Please use this method in debug mode!'
			];
		}
		if(defined('_TEST_MODE_')&&_TEST_MODE_){
			return $array;
		}
		$xml = DataObject::arrayToXml($array);
		$response = Response::instance($this->code, Response::XML)->send($xml);
	}

	public function getData($getTrace = false){
		if($this->code==707){
			$data = [
				'message'	=>	$this->message
			];
		}else{
			$data = [
				'code'	=>	$this->code,
				'message'	=>	$this->message
			];
		}
		if(defined('_USE_DEBUG_MODE_')&&_USE_DEBUG_MODE_){
			$data['detail'] = $this->content;
			$data['position'] = $this->file." on line ".$this->line;
			if($getTrace){
				$data['trace'] = $this->getTrace();
			}
		}
		return $data;
	}

	public function log(){
		if($this->code>=300){
			$path = PATH_CAC_LOG.'errors/'.date('Ym').'/';
			$text = $this->lotxt();
		}else{
			$path = PATH_CAC_LOG.'notes/status/'.date('Ym').'/';
			$text = $this->notxt();
		}
		$filename = $path.date('Ymd');
		if (!file_exists($path)){
			mkdir($path, 0777, true);
		}
		$file = @fopen($filename, 'a') or new Status(706.3, 'Permission Denied', 'Unable to write run log! The current log file may be read-only.', true);
		fwrite($file, $text);
		fclose($file);
	}

	private function lotxt(){
		$text  = ">>>>>>\t$this->alias\t@\t" . date('Y-m-d H:i:s') . "\r\n";
		$text .= "\tMSG\t\t$this->message\r\n";
		$text .= "\tDESC\t\t$this->content\r\n";
		$text .= "\tURL\t\t" . HOST . $_SERVER["REQUEST_URI"] . "\r\n";
		$text .= "\tFILE\t\t$this->file in line $this->line\r\n";
		$text .= "\tTRACE";
		$tracedata = $this->getTrace();
		foreach($tracedata as $n=>$p){
			if(empty($p['file'])){
				$p['file'] = $this->file;
			}
			if($n){
				$text .= "\t\t#";
			}else{
				$text .= "\t#";
			}
			if(isset($p['class'])){
				$text .= $n . "\t" . $p['class'] . '::' . $p['function'] . '() on ' . $p['file'] ."\r\n";
			}elseif(isset($p['function'])){
				$text .= $n . "\t" . $p['function'] . '() on ' . $p['file'] ."\r\n";
			}else{
				$text .= $n . "\t" . $p['file'] ."\r\n";
			}
		}
		$ip = Request::instance()->IP;
		$text .= "\tUSER\t\t\tfrom $ip\r\n";
		$text .= "\r\n";
		return $text;
	}

	private function notxt(){
		$text  = date('Y-m-d H:i:s');
		$text .= "\t MSG\t" . $this->message;
		$text .= "\tDESC\t" . $this->content;
		$text .= "\t URL\t" . HTTP . $_SERVER["REQUEST_URI"];
		$text .= "\tFILE\t" . $this->file;
		$text .= "\tLINE\t" . $this->line;
		$text .= "\t IP \t" . Request::instance()->IP;
		return $text.PHP_EOL;
	}

	public function cast($type = self::CAST, $template = NULL){
		if($type>4){
			$this->log();
		}
		$response = Response::instance($this->code);
		$response->setHeader('NI-Response-Text', $this->message, true);
		switch ($type) {
			case 1:
			case 5:
			$response->MIME = Response::XML;
			$data = $this->getData();
			return $response->send($this->xml_encode($data));			

			case 3:
			case 7:
			$response->MIME = Response::TXT;
			return $response->send('#'.$this->code.' YangRAM Status "' . $this->message . '" in ' . $this->file . ' on line ' . $this->line);

			case 4:
			case 8:
			$response->MIME = Response::HTML;
			$response->sendHeaders();
			return $this->render($template);

			case 2:
			case 6:
			$response->MIME = Response::JSON;
			$data = $this->getData();
			return $response->send(json_encode($data));
			default:
			if(isset($_SERVER['HTTP_ACCEPT'])){
				if(strpos($_SERVER['HTTP_ACCEPT'], 'html')){
					$response->MIME = Response::HTML;
					$response->sendHeaders();
					return $this->render($template);
				}
				if(strpos($_SERVER['HTTP_ACCEPT'], 'xml')){
					$response->MIME = Response::XML;
					$data = $this->getData();
					return $response->send($this->xml_encode($data));
				}
			}
			$response->MIME = Response::JSON;
			$data = $this->getData();
			return $response->send(json_encode($data));
		}
	}

	private function xml_encode($data){
        $xml = new \XmlWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('status');
		foreach($data as $key => $value){
			$xml->writeElement($key, $value);
		}
        $xml->endElement();
        return $xml->outputMemory(true);
    }

	public static function langExists($lang, $pattern, $default = _LANG_){
		$file = str_replace('{{L}}', $lang, $pattern);
		if(is_file($file)){
			return $file;
		}
		$la = substr($lang, 0, 2);
		$files = glob(str_replace('{{L}}', $la.'-*', $pattern));
		if(isset($files[0])){
			return $files[0];
		}
		$file = str_replace('{{L}}', _LANG_, $pattern);
		if(is_file($file)){
			return $file;
		}
		return false;
	}

	private function render($template){
		$lang 		= 	$GLOBALS['NEWIDEA']->LANGUAGE;
		$code		=	$this->code;
		$intc		=	$this->intc;
		$alias 		= 	$this->alias;
		$content	=	$this->message;
		if($file = self::langExists($lang, PATH_LANGS.'{{L}}/status/'.$code.'.php')){
			include $file;
		}elseif($file = self::langExists($lang, PATH_LANGS.'{{L}}/status/'.$intc.'.php')){
			include $file;
		}else{
			if(isset(self::$sattuscodes[$intc])){
				$title = self::$sattuscodes[$intc];
			}else{
				$file = self::langExists($lang, PATH_LANGS.'{{L}}/status/707.php');
				if($file){
					include $file;
				}else{
					$title = 'Undefined Status Message';
				}
			}
		}

		if(defined('_USE_DEBUG_MODE_')&&_USE_DEBUG_MODE_){
			if($content===$title){
				$content = '';
			}else{
				$content .= '<br />';
			}
			if($this->content!=='(none)'){
				$content .= $this->content;
			}
			$place = "Casted in $this->file on line $this->line";
		}else{
			$place = "";
		}

		if(!is_string($template)){
			$template = $this->getTemplate();
		}

		if(is_file($template)){
			include $template;
		}else{
			Response::renderStatus($title, $alias, $code, $content, $place);
		}
		exit;
	}

	private function getTemplate(){
		$code = $this->intc;
		switch($code){
			case '200':case '777':
			return self::TEMP_GREEN_CHANNEL;

			case '404':
			return self::TEMP_404;

			case '700':
			return self::TEMP_700;

			case '707':
			return self::TEMP_707;

			case '787':case '797':
			return self::TEMP_SERVER_ERRORS;

			default:
			if($code<300) return self::TEMP_GREEN_CHANNEL;

			if($code<400) return self::TEMP_700;

			if($code<500) return self::TEMP_400;

			if($code<700) return self::TEMP_797;

			if($code<707) return self::TEMP_701;

			return self::TEMP_708;
		}
	}
}