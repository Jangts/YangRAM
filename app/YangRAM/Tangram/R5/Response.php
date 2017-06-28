<?php
namespace Tangram\R5;

use Status;
use Tangram\NIDO\DataObject;

/**
 *	Universal Responser
 *	通用响应对象
 *  单例类，其实例是一个可控响应，不限制调用者，被抢先实例化后仍可以被修改状态
 *	负责辅助应用响应客户端（包括浏览者端在内的两口一端，不包括维运人员端和测试员端）
 */
final class Response {    
    const
    OK      =   200,
    MV      =   301,
    SO      =   303,
    NM      =   304,
    FB      =   403,
    NF      =   404,
    UA      =   700,
    
    ALL     =   'application/octet-stream',
    AVI     =   'video/x-msvideo',
    CSS     =   'text/css',
    DOC     =   'application/msword',
    DOCX    =   'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    GIF     =   'image/gif',
    HTML    =   'text/html',
    JPG     =   'image/jpeg',
    JPEG    =   'image/jpeg',
    JS      =   'text/javascript',
    JSON    =   'application/json',
    MOV     =   'video/quicktime',
    MP3     =   'audio/mpeg',
    MP4     =   'video/mp4',
    MPEG    =   'audio/mpeg',
    OGG     =   'audio/ogg',
    PDF     =   'application/pdf',
    PNG     =   'image/png',
    PPT     =   'application/vnd.ms-powerpoint',
    PPTX    =   'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    PPS     =   'application/vnd.ms-powerpoint',
    PPSX    =   'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
    RAR     =   'application/x-rar-compressed',
    TXT     =   'text/plain',
    WAV     =   'audio/wav',
    WMV     =   'video/x-ms-wmv',
    XLX     =   'application/vnd.ms-excel	application/x-excel',
    XLSX    =   'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    XML     =   'text/xml',
    ZIP     =   'application/x-zip-compressed';

    private static $instance = NULL;

    public static function instance($statusCode = NULL, $type = Response::HTML){
        if(self::$instance===NULL){
            $statusCode = is_numeric($statusCode) ? $statusCode : 200;
            self::$instance = new Response($statusCode);
        }else{
            if(is_numeric($statusCode) && isset(Status::$sattuscodes[$statusCode])){
                self::$instance->STATUS = $statusCode;
            }
        }
        self::$instance->MIME = $type;
        return self::$instance;
    }

    public static function trimServerFilename($filename){
		$filename = str_replace('\\', '/', $filename);
		if(strpos($filename, PATH_TNI)===0){
			$filename = str_replace(PATH_TNI, '<%K%>', $filename);
		}
		elseif(strpos($filename, PATH_I4S)===0){
			$filename = str_replace(PATH_I4S, '<%S%>', $filename);
		}
		elseif(strpos($filename, PATH_APP)===0){
			$filename = str_replace(PATH_APP, '<%A%>', $filename);
		}
		elseif(strpos($filename, PATH_DAT)===0){
			$filename = str_replace(PATH_DATA, '<%D%>', $filename);
		}
		elseif(strpos($filename, PATH_USR)===0){
			$filename = str_replace(PATH_USR, '<%U%>', $filename);
		}
		elseif(strpos($filename, ROOT._BOOT_)===0){
			$filename = '<%BOOTSTRAP%>';
		}
		elseif(strpos($filename, ROOT)===0){
			$filename = str_replace(ROOT, '<%R%>', $filename);
		}else{
			$filename = '<%******%>';
		}
		return $filename;
	}

	public static function restoreServerFilename($filename){
		$filename = str_replace('\\', '/', $filename);
		if(strpos($filename, '<%K%>')===0){
			$filename = str_replace('<%K%>', PATH_TNI, $filename);
		}
		elseif(strpos($filename, '<%S%>')===0){
			$filename = str_replace('<%S%>', PATH_I4S, $filename);
		}
		elseif(strpos($filename, '<%A%>')===0){
			$filename = str_replace('<%A%>', PATH_APP, $filename);
		}
		elseif(strpos($filename, '<%D%>')===0){
			$filename = str_replace('<%D%>', PATH_DATA, $filename);
		}
		elseif(strpos($filename, '<%U%>')===0){
			$filename = str_replace('<%U%>', PATH_USR, $filename);
		}
		elseif(strpos($filename, '<%BOOTSTRAP%>')===0){
			$filename = str_replace('<%BOOTSTRAP%>', ROOT._BOOT_, $filename);
		}
		elseif(strpos($filename, '<%R%>')===0){
			$filename = str_replace('<%R%>', ROOT, $filename);
		}
		return $filename;
	}

    public static function moveto($url, $code = 303){
        if($code < 300||$code >= 400){
            $code = 303;
        }
        header("HTTP/1.1 303 Moved Permanently");
        header("Location:".$url);
        exit;
    }

    public static function renderStatus($title, $alias, $code, $message, $place){
        echo <<<HTML
			<!doctype html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>$title</title>
<style>
* { position: relative; margin: 0; padding: 0; border: none; }
body { color: #FFF; font-family: 'Microsoft Yahei', 'Microsoft Sans Serif', 'Hiragino Sans GB', 'sans-serif'; font-weight: lighter; }
.main { width: 80%; max-width: 800px; height: 80%; padding: 10%; cursor: default; }
.main > header.text-icon { width: 180px; height: 180px; letter-spacing: .2em; text-align: center; color: #FFF; line-height: 162px; font-size: 144px; }
.main > header.text-icon { background: url(/icon.php?o) center no-repeat; }
.main > header.text-icon { width: 150px; height: 150px; padding: 15px; letter-spacing: 0; line-height: 132px; font-size: 114px; }
.main > article { margin-top: 20px; font-size: 14px; }
.main > article > header { border-bottom: #FFF 1px solid; margin-bottom: 10px; padding: 5px 0; color: #FFF;}
.main > article > header > strong { font-family: Impact; letter-spacing: .1em; font-size: 36px; line-height: 30px; font-weight: lighter; }
.main > article > header > span { font-weight: lighter; font-size: 21px; }
.main > article > header > span:before { content: "/"; margin: 0 3px 0 2px;}
.main > article > p { line-height: 24px; text-align: justify; margin-top: 10px; }
.main > article > ol { list-style-position: inside; }
.main > article > ol > li { line-height: 18px; text-align: justify; margin-top: 5px; }
.main > footer { min-height: 30px; max-height: 60px; overflow: hidden; margin-top: 10px; }
.main > footer { text-align: left; font-size: 12px; line-height: 30px; border-bottom: #FFF 1px solid; }
.main > footer > .alias { float: right; font-size: 16px; text-align: right; }

body { background: #33A5DD;	background: rgba(51,165,221,1); }
body > div > article { ccolor: #CEF; }
body > div > footer { border-top: #3DF 1px dashed; color: #3DF; }
</style>
</head>
<body>
<div class="main">
    <header class="text-icon">:&nbsp;(</header>
    <article><header><strong>$code</strong><span>$title</span></header>$message</article>
    <footer><span class="place">$place</span><span class="alias">$alias</span></footer>
</div>
</body>
</html>
HTML;
    }

    private
    $statusCode = 200,
    $headers = [
        'NI-Response-Code' => 200
    ],
    $irreplaceable = [],
    $data = [
        'STATUS'    =>  200,
        'MIME'      =>  'text/html',
        'CHARSET'   =>  'utf-8'
    ];

    private function __construct($statusCode){
        $this->statusCode = (string)$statusCode;
        $this->headers['NI-Response-Code'] = (string)$statusCode;
        $statusCode = intval($statusCode);
        if(isset(Status::$sattuscodes[$statusCode])){
            if(($statusCode>=200&&$statusCode<300)||$statusCode==304||$statusCode==403||$statusCode==404){
                $this->data['STATUS'] = $statusCode;
                $this->data['MESSAGE'] = Status::$sattuscodes[$statusCode];
            }else{
                $this->data['STATUS'] = 200;
                $this->data['MESSAGE'] = Status::$sattuscodes[200];
            }
        }else{
            $this->data['STATUS'] = 404;
            $this->data['MESSAGE'] = Status::$sattuscodes[404];
        }
        
    }

    public function __get($property){
        if(isset($this->data[$property])){
            return $this->data[$property];
        }
        return NULL;
    }

	public function __set($property, $value){
        if($property==='STATUS'){
            $this->statusCode = (string)$value;
            $this->headers['NI-Response-Code'] = (string)$value;
            $statusCode = intval($value);
            if(isset(Status::$sattuscodes[$value])){
                if(($statusCode>=200&&$statusCode<300)||$statusCode==304||$statusCode==404){
                    $this->data['STATUS'] = $statusCode;
                    $this->data['MESSAGE'] = Status::$sattuscodes[$statusCode];
                }else{
                    $this->data['STATUS'] = 200;
                    $this->data['MESSAGE'] = Status::$sattuscodes[200];
                }
            }
        }
        if(in_array($property, ['MIME', 'CHARSET'])){
            $this->data[$property] = $value;
        }
    }

    public function setHeaders($headers){
        if(is_array($headers)){
            $this->headers = $headers;
            $this->headers['NI-Response-Code'] = $this->statusCode;
        }
        return $this;
    }

    public function setHeader($name, $value, $replace = false){
        if(!!$replace){
            $this->headers[(string)$name] = (string)$value;
        }else{
            $this->irreplaceable[] = sprintf('%s: %s', $name, $value);
        }
        return $this;
    }

    public function setResourceCache($expires = 3153600000,  $cactrl = 'public'){
        $this->setHeader('Cache-Control', $cactrl)
            ->setHeader('Cache-Control', 'max-age='.$expires)
            ->setHeader('Expires', preg_replace('/.{5}$/', 'GMT', gmdate('r', intval(time() + $expires))))
            ->setHeader('Last-Modified', gmdate("D, d M Y H:i:s", time()).' GMT');
        return $this;
    }

    public function getHeaders(){
        $headers = [];
        foreach ($this->headers as $name => $value) {
            $headers[] = sprintf('%s: %s', $name, $value);
        }
        $str = HTTP."\s".$this->data['STATUS']."\s".$this->data['MESSAGE'];
        $str .= join("\r\n", $headers);
        $str .= join("\r\n", $this->irreplaceable);
        return $str;
    }

    public function sendHeaders(){
        $body = ob_get_clean();
        header(HTTP." ".$this->data['STATUS']." ".$this->data['MESSAGE']);
        header(sprintf("Content-Type: %s;charset=%s", $this->data['MIME'], $this->data['CHARSET']));
        foreach ($this->headers as $name => $value) {
            header(sprintf('%s: %s', $name, $value));
        }
        foreach ($this->irreplaceable as $value) {
            header($value);
        }
        return $body;
    }

    public function send($body = NULL, $usePrevious = false){
        $cache = $this->sendHeaders();
        if(is_string($body)===false){
            $body = '';
        }
        if($usePrevious){
            echo $cache . $body;
        }else{
            echo $body;
        }
        die;
    }

    public function render($template, array $data = [], $usePrevious = false){
        if(is_string($template)&&is_file($template)){
            if(!is_array($data)){
                $data = [];
            }
            $cache = $this->sendHeaders();
            if($usePrevious){
                echo $cache;
            }
            extract($data, EXTR_PREFIX_SAME, 'CSTM');
            include $template;
        }else{
            if(isset($_SERVER['HTTP_ACCEPT'])&&(strpos($_SERVER['HTTP_ACCEPT'], 'html')||strpos($_SERVER['HTTP_ACCEPT'], 'xml'))){
				$this->data['MIME'] = Response::XML;
				$this->sendHeaders();
				echo DataObject::arrayToXml($data);
			}else{
                $this->data['MIME'] = Response::JSON;
                $this->sendHeaders();
                echo json_encode($data);
            }
            die;
        }
    }
}