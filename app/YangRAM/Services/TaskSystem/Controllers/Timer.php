<?php
namespace Workers\Controllers;
use Status;
use System\AsyncTask;

/**
 *  
 */
class Timer extends Process {
    protected $id, $team, $recordfile, $taskernote, $count = 0;

    protected function init(){
        $this->id = 'timer_'.substr(uniqid(), 7, 4);
        $this->team = 'TIMER';
        if(date('i')>=59){
            $this->hour = date('H', time() + 5);
        }else{
            $this->hour = date('H');
        }
        $this->record();
        $this->working();
    }

    protected function working(){
        $count = 0;
        do{
            if($count){
                $this->check();
            }
            $this->scan();
            sleep($this->timefix());
            $count++;
        }while(true);
    }

    private function timefix(){
        return 1;
    }

    protected function check(){
        $dist = date('H') - $this->hour;
        if(($dist > 0)&&($dist < 24)){
            $this->log('Completed The Mission');
            unlink($this->recordfile);
        }else{
            if(realpath($this->recordfile)){
                return true;
            }
            $this->log("Be Fired\tHas Been On Duty $this->count Times");
        }
        die;
    }

    protected function afterworking($log){
        if(is_string($log)){
            $log = $this->arrayElemTrim($log);
        }elseif(is_array($log)){
            $log = array_walk($log, array($this, 'str_replace'));
            $log = join(PHP_EOL."\t\t\t", $log);
        }
        $this->log("Record Works\t".$log);
        if(realpath($this->recordfile)){
            return true;
        }
        $this->log("Be Fired\tHas Been On Duty $this->count Times");
        die;
    }

    protected function scan(){
        $int = mt_rand(0, 10);
        if($int==1){
            $this->afterworking('Send Message To Appliction [ #APP_ID ].');
        }
    }
}
