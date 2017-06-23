<?php
namespace Workers\Controllers;
use Status;
use Tangram\AsyncTask;

/**
 *  
 */
final class Master extends Process {
    private $begintime, $workerscount = 0;

    protected function init(){
        $this->id = 'master';
        $this->team = 'MASTER';
        $this->workerscount = count(glob(AsyncTask::$recordpath.'*er_*'));
        $this->record();
        $this->working();
    }

    protected function record(){
        if (!is_dir(AsyncTask::$recordpath)){
			mkdir(AsyncTask::$recordpath, 0777, true);
        }
        $filename = AsyncTask::$recordpath.$this->id.'.worker';
        $begintime = time();
        if(file_put_contents($filename, $begintime)){
            $this->recordfile = $filename;
            $this->begintime = $begintime;
            $this->log('[New Master]');
            if(date('i')>=59){
                if(date('H')>=23){
                    $this->taskOfDay($begintime + 2);
                }else{
                    $this->taskOfDay($begintime);
                }
                $this->taskOfHour($begintime + 2);
            }else{
                $this->taskOfDay($begintime);
                $this->taskOfHour($begintime);
            }
            return true;
        }
        exit;
    }

    protected function working(){
        $count = 0;
        do{
            if($count>=_MASTER_SLEEP_COUNT_){
                $this->suicide(true);
            }
            if($count){
                $this->check();
            }
            $this->scan();
            sleep($this->timefix());
            $count++;
        }while(true);
    }

    private function timefix(){
        $now = strtotime(date("Y-m-d H:i"));
        return $now + 60 - time();
    }

    protected function check(){
        $this->checkHour();
        $this->checkMaster();
        $this->checkWorkers();
    }

    private function checkHour(){
        $currminute = date('i');
        $currhour = date('H');
        if($currminute=='00'){
            $currdate = date('Y-m-d');
            if($currhour=='00'){
                $currDayOfWeek = date('l');
                $this->checkDay($currdate, $currDayOfWeek);
            }else{
                $this->log("Chiming\tNow Time Is $currhour o'clock, On $currdate");
            }
        }else{
            if($currminute=='59'){
                $nexttime = time() + 2;
                if($currhour=='23'){
                    $this->taskOfDay($nexttime);
                }
                $this->taskOfHour($nexttime);

            }
            return true;
        }
    }

    private function taskOfHour($time){
        # codes
        $hour = date('H', $time);
        $date = date('Ymd', $time);
        $this->log("Check Task\tDuring $hour:00:00 To $hour:59:59 On $date");
        $int = mt_rand(0, 2);
        if($int==1){
            $this->hireWorker('timer');
        }
    }

    private function taskOfDay($time){
        $date = date('Y-m-d', $time);
        $dayOfWeek = date('l', $time);
        $this->log("Check Task\tOn $date, $dayOfWeek");
        # codes
        
    }

    private function checkDay($currdate, $currDayOfWeek){
        $currday = date('d');
        $this->log("Chiming\tNow Time Is Midnight, $currdate, $currDayOfWeek");
        if($currday=='01'){
            $this->checkMonth();
        }
    }

    private function checkMonth(){
        $currmonth = date('m');
        if($currday=='01'){
            $this->log("Chiming\tHappy New Year ".date('Y'));
        }else{
            $this->log('[New '.date('M') . ']');
        }
    }

    private function checkMaster(){
        if(realpath($this->recordfile)){
            $begintime = file_get_contents($this->recordfile);
            if($begintime==$this->begintime){
                file_put_contents($this->recordfile, $begintime);
                return true;
            }
            $this->log('[Relieve Guard]');
            die;
        }
        $this->suicide();
    }

    private function checkWorkers(){
        $workers = glob(AsyncTask::$recordpath.'*er_*');
        $count = count($workers);
        foreach($workers as $worker){
            if($pos = strpos($worker, '/worker_')){
                $time = filemtime($worker);
                if(time() - $time >= _WORKER_REST_TIMEOUT_){
                    unlink($worker);
                    $worker_id = substr($worker, $pos + 1);
                    $this->log("Fired A Worker\tId Is [$worker_id], Current Number Of Workers Is " . --$count);
                }
            }
        }
        $this->workerscount = $count;
    }

    private function hireWorker($type = 'worker'){
        $this->app->get([
            'instr'     =>  $type,
            'timeout'   =>  _WORKER_BUILD_TIMEOUT_
        ]);
        sleep(2);
        $count = count(glob(AsyncTask::$recordpath.'*er_*'));
        if($count>$this->workerscount){
            $this->log("Hired A Worker\tCurrent Number Of Workers Is $count");
        }else{
            $this->log("Hiring Workers Failed\tCurrent Number Of Workers Is $count");
        }
        $this->workerscount = $count;
    }

    protected function scan(){
        


        /* 以下为测试代码 */
        $int1 = mt_rand(0, 1000);
        $int2 = mt_rand(0, 7);
        if($int1==77){
            $this->suicide("Be Killed\tReceived A Request To Die");
        }elseif($int2==3&&$this->workerscount<_MAX_WORKERS_COUNT_){
            $this->hireWorker();
        }
    }

    private function suicide($restart = false, $log = 'Be Killed'){
        unlink($this->recordfile);
        if($restart===true){
            $this->log('[Rrestart]');
            $this->app->get([
                'instr'     =>  'master',
                'timeout'   =>  _WORKER_BUILD_TIMEOUT_
            ]);
        }else{
            if(is_string($restart)){
                $log = $restart;
            }
            $this->log($log);
            $workers = glob(AsyncTask::$recordpath.'*er_*');
            array_map('unlink', $workers);
        }
        exit;
    }
}
