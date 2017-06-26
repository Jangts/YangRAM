<?php
namespace Workers\Controllers;
use Status;
use Tangram\AsyncTask;
use Storage;

/**
 *  
 */
class Process extends \AF\Controllers\IPC\CommunicationController_BC {
    protected $id, $team, $recordfile, $taskernote, $count = 0, $cache = [];

    public function main(){
        if(self::checkRequestToken($this->request, $this->tokenname)){
            $this->closeConnection('SUCCESSED');
            $this->init();
        }else{
            $status = new Status(403);
            return $status->cast(Status::CAST_LOG);
        }
    }

    protected function init(){
        $this->id = 'worker_'.substr(uniqid(), 7, 4);
        $this->team = 'TEAM_'.strtoupper(substr($this->id, 7, 1));
        $this->record();
        $this->working();
    }

    protected function record(){
        if (is_dir(AsyncTask::$recordpath)){
            $filename = AsyncTask::$recordpath.$this->id.'.worker';
            if(realpath($filename)){
                die;
            }
            if(file_put_contents($filename, time())){
                $this->recordfile = $filename;
                $this->log('[New Worker]');
                return true;
            }
        }
        die;
    }

    protected function log($text){
        $path = AsyncTask::$notepath.date('Y-m').'/';
        if (!is_dir($path)){
			mkdir($path, 0777, true);
		}
        $note = $path.$this->team;
        if($file = @fopen($note, 'a')){
            if(count($this->cache)){
                $cache = join(PHP_EOL, $this->cache).PHP_EOL;
                $this->cache = [];
            }else{
                $cache = '';
            }
            fwrite($file, date('Ymd Hi')."\t".$this->id."\t".$text.PHP_EOL);
            fclose($file);
        }else{
            $this->cache[] = date('Ymd Hi')."\t".$this->id."\t".$text;
        }
    }

    protected function working(){
        $count = 0;
        do{
            if($count){
                $this->check();
            }
            $this->scan();
            sleep(_WORKER_SLEEP_TIME_);
            $count++;
        }while(true);
    }

    protected function check(){
        if(realpath(AsyncTask::$recordpath.'master')){
            $workers = glob(AsyncTask::$recordpath.'*er_*');
            if(count($workers)>_MAX_WORKERS_COUNT_){
                $this->log("Be Replaced By Other Worker\tHas Been On Duty $this->count Times");
            }elseif(realpath($this->recordfile)){
                return true;
            }else{
                $this->log("Be Fired\tHas Been On Duty $this->count Times");
            }
        }else{
            $this->log("Gained Freedom\tHas Been On Duty $this->count Times");
        }
        unlink($this->recordfile);
        die;
    }

    protected function arrayElemTrim($el){
        return str_replace(PHP_EOL, '<BR>', trim($el));
    }

    protected function afterworking($log){
        if(is_string($log)){
            $log = $this->arrayElemTrim($log);
        }elseif(is_array($log)){
            $log = array_walk($log, array($this, 'str_replace'));
            $log = join(PHP_EOL."\t\t\t", $log);
        }
        $this->log("Record Works\t".$log);
        $this->count++;
        if(realpath($this->recordfile)){
            file_put_contents($this->recordfile, time());
        }else{
            $this->log("Be Fired\tHas Been On Duty $this->count Times");
            die;
        }
    }

    protected function scan(){
        $int = mt_rand(0, 1);
        if($int==1){
            $this->afterworking('Do Something For Appliction [ #APP_ID ].');
        }
    }
}
