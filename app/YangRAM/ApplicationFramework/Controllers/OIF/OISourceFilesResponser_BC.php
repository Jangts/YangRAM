<?php
namespace OIC;

use Status;
use Response;
use Storage;
use Tangram\DBAL\Counter;
use Library\compilers\OperationScript;
use Library\compilers\JSMin;
use Library\compilers\OIStyleSheets;
use Library\compilers\Scssc;
use AF\CacheHandlers\AppCache;

abstract class OISourceFilesResponser_BC extends OICtrller_BC {
    protected
	$posterimage = 'poster.jpg',
	$main_os_file = 'Sources/main',
    $os_outdir = '.tmp/os/',
	$main_oiss_file = 'common';

	private function send($code, $type = Response::TXT) {
		if($type === Response::JS){
			if(isset($this->request->PARAMS->check)){
				Response::instance(200, 'text/yangram.project-file')->send('');
			}
        	$counter = new Counter(DB_SYS.'apps', 0);
			$counter->setFields('app_count', 'app_id')->point($this->app->APPID)->add();
		}

        if($code){
            $response = Response::instance(200, $type);
        }else{
            $response = Response::instance(304, $type);
        }
		
		$response->setResourceCache()->send($code);
	}

	final public function returnSplashScreen(){
		$splashScreen = $this->getSplashScreen();
		if($this->checkResourceModification($splashScreen[0])){
			return $this->send($splashScreen[1], Response::TXT);
		}else{
			return $this->send('', Response::TXT);
		}
	}

	private function getSplashScreen(){
		$appid = AI_CURR;
		$storge = new AppCache($appid, false, Storage::STR, '.xml');
		if($body = $storge->take('SplashScreen')){
			return [$storge->time('SplashScreen'), $body];
		}else{
			$body = '<info>';
			$body .= '<applang>'.$this->app->Lang.'</applang>';
			$body .= '<atitle>'.$this->app->Name.'</atitle>';
			$body .= '</info>';
			$body .= '<view loading>';
			if($appid!='SETTINGS'&&$this->posterimage&&file_exists(AP_CURR.'Sources/'.$this->posterimage)){
				$body .= '<weclome left><appname>'.$this->app->Name.'</appname><appvrsn>'.$this->app->Version.'</appvrsn><spinner class="app-launching-spinner"></spinner></weclome>';
				$body .= '<poster right><img width="640" height="360" src="'.HTTP_PID.AD_CURR.'Sources/'.$this->posterimage.'" /></poster>';
			}else{
				if($appid=='SETTINGS'){
					$body .= '<weclome><appname>'.$this->app->Name.'</appname><appvrsn>'.$this->app->Version.'</appvrsn>';
					$body .= '<spinner class="cp-launching-spinner"> <el class="cls-bounce1"></el> <el class="cls-bounce2"></el><el class="cls-bounce3"></el></spinner></weclome>';
				}else{
					$body .= '<weclome center><appname>'.$this->app->Name.'</appname><appvrsn>'.$this->app->Version.'</appvrsn><spinner class="app-launching-spinner"></spinner></weclome>';
				}
			}
			$body .= '</view>';
			$storge->store('SplashScreen', $body);
			return [time(), $body];
		}
	}

	final public function returnMainOS(){
        global $NEWIDEA;
        $osfile = AP_CURR.$this->main_os_file.'.os';
        $outfile = AP_CURR.$this->os_outdir.$NEWIDEA->LANGUAGE.'.js';
        $minfile = AP_CURR.$this->os_outdir.$NEWIDEA->LANGUAGE.'.min.js';
        if(is_file($minfile)){
            if($this->checkFileModification($minfile)){
                if(_USE_DEBUG_MODE_){
                    return $this->send(file_get_contents($outfile), Response::JS);
                }else{
                    return $this->send(file_get_contents($minfile), Response::JS);
		        }
            }else{
                return $this->send('', Response::JS);
            }
        }elseif(is_file($osfile)){
			$complier = new OperationScript(AP_CURR);
			$code = $complier->complie($osfile, $outfile, $minfile, $this->checklang($NEWIDEA->LANGUAGE));
            return $this->send($code, Response::JS);
        }
        new Status(404, true);
	}

	protected function checklang($lang){
		$lang_check_result = $GLOBALS['NEWIDEA']->check_lang($this->app->Path.'Locales/{{lang}}.json', false, $lang);
		if($lang_check_result){
			return file_get_contents($lang_check_result[1]);
		}
        return false;
    }

    final public function returnStyleSheets($basename = ''){
		if($basename){
			$filename = AP_CURR.'Sources/'.$basename.'.css';
			return $this->getStyleSheets($filename, false);
		}
		$filename = AP_CURR.'Sources/'.$this->main_oiss_file.'.css';
		return $this->getStyleSheets($filename, true);	
    }

	private function getStyleSheets($filename, $is_main){
        if(is_file($filename)){
            if($this->checkFileModification($filename)){
				$complier = new OIStyleSheets(AI_CURR, HTTP_PID.AD_CURR,
					HTTP_HOST.APP_PID.$this->app->Author.'/'.$this->app->Props['Suitspace'].'/');
				$code = $complier->cssFilter(file_get_contents($filename), $is_main);
                return $this->send($code, Response::CSS);
            }else{
                return $this->send('', Response::CSS);
            }
		}elseif(is_file($filename.'x')){
			$source = file_get_contents($filename.'x');
			$scssc = new Scssc();
			file_put_contents($filename, $scssc->compile($source));
			return $this->getStyleSheets($filename, $is_main);
		}else{
            return $this->send('/* yangram css document */', Response::CSS);
        }
    }

	final public function clear(){
        if(is_dir(AP_CURR.$this->os_outdir)){
            Storage::clearPath(AP_CURR.$this->os_outdir);
        }
        exit('{"msg":"cleared"}');
    }
}
