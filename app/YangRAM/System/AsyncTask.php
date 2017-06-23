<?php
namespace Tangram;

use Application;

/**
 *	Process Bus
 *	任务处理单元，对于核心模块而言，其主要
 *	用来检查当前是否存在一个活动的YangRAM AsyncTask进程，如果不存在则创建一个，对于一般子应用而言，其主要
 *	负责提交任务需求单
 */
final class AsyncTask {
    const
    ONCE = 0,
    DAILY = 1,
    DAY = 2,
    DATE = 3;

    public static
    $recordpath = PATH_CACA.'WORKERS/actived-process/',
    $notepath = PATH_CAC_LOG.'tasks/',
    $queuepaths = [
        'QUEUE'     =>  [
            'ONETIME'   =>  PATH_CAC_TASK.'ONETIME/', //文件名為以任務執行時間精確到小時的10位數字加“.todo”，如2017010109.todo
            'DAILY'   =>  PATH_CAC_TASK.'DAILY/',     //文件名為以任務執行時間所在小時的2位數字加“.todo”，如06.todo
            'WEEKLY'   =>  PATH_CAC_TASK.'WEEKLY/',   //文件名為以任務執行時間所在日的星期名加“.todo”，SUNDAY.todo
            'YEARLY'   =>  PATH_CAC_TASK.'YEARLY/'    //文件名為以任務執行時間所在日的日期號的月日4位數字加“.todo”，0214.todo
        ],
        /*
         * 文件内容为JSON格式的文本
         * 被讀取的任務文件的後綴名為“.lock”
         * 活動周期内完成的文件的後綴名為“.done”
         * 活動周期外恢復為“.todo”
         */

        'TEMPORARY' =>  PATH_CAC_TASK.'TEMPORARY/'    //文件名為以任務創建時間精確到秒的14位數字加“.todo”，如201701010093008.todo
        //被讀取的任務文件的後綴名為“.lock”，完成的任務將被刪除
    ];

    public static function checkMasker(){
        if(_TASKER_ENABLE_&&AI_CURR!=='WORKERS'){
            $masterfile = self::$recordpath.'master.worker';
            if(is_file($masterfile)){
                $lasttime = filemtime($masterfile);
                if(time() - $lasttime < 60){
                    return true;
                }
            }
            return self::buildMaster();
        }
    }

    private static function buildMaster(){
        $app = new Application('WORKERS');
        return $app->get([
            'instr'     =>  'master',
            'timeout'   =>  _WORKER_BUILD_TIMEOUT_
        ]);
    }

    public static function addTask($events = [], $type = self::ONCE){
        switch($type){
            case self::ONCE:
            array_map(['Tangram\AsyncTask', 'addOnetimeEvent'], $events);
            return true;

            case self::DAILY:
            return self::addDailyEvent($events);

            case self::DAYOFWEEK:
            return self::addWeeklyEvent($events);

            case self::DATE:
            return self::addYearlyEvent($events);
        }
        return false;
    }

    private static function write($path, $time, $event){
        $filename = $path . $time . '.lock';
        if(!is_file($filename)){
            $filename = $path . $time . '.todo';
            if(isset($event['target'])){
                @file_put_contents($filename, json_encode($event), FILE_APPEND);
            }
        }
    }

    private static function addOnetimeEvent($event){
        $path = self::$queuepaths['QUEUE']['DAILY'];
        if (!file_exists($path)){
			mkdir($path, 0777, true);
		}
        if(is_array($event)&&isset($event['time'])&&preg_match('/^[1-9]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\s+(20|21|22|23|[0-1]\d):[0-5]\d:[0-5]\d$/', $event['time'])){
            $strtotime = strtotime($event['time']);
            $time = substr(preg_replace('/[\s\-\:]+/', '', $time), 0, 10);
            if($strtotime>time()){
                self::write($path, $time, $event);
            }
        }
    }

    private static function addDailyEvent($event){
        $path = self::$queuepaths['QUEUE']['ONETIME'];
        if (!file_exists($path)){
			mkdir($path, 0777, true);
		}
        if(is_array($event)&&isset($event['time'])&&preg_match('/^(20|21|22|23|[0-1]\d):[0-5]\d:[0-5]\d$/', $event['time'], $matches)){
            $time = $matches[1];
            self::write($path, $time, $event);
        }
    }

    private static function addWeeklyEvent($event){
        $path = self::$queuepaths['QUEUE']['ONETIME'];
        if (!file_exists($path)){
			mkdir($path, 0777, true);
		}
        if(is_array($event)&&isset($event['time'])&&preg_match('/^(Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday)\s(20|21|22|23|[0-1]\d):[0-5]\d:[0-5]\d$/i', $event['time'], $matches)){
            $time = strtoupper($matches[1]);
            self::write($path, $time, $event);
        }
    }

    private static function addYearlyEvent($event){
        $path = self::$queuepaths['QUEUE']['YEARLY'];
        if (!file_exists($path)){
			mkdir($path, 0777, true);
		}
        if(is_array($event)&&isset($event['time'])&&preg_match('/^(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\s+(20|21|22|23|[0-1]\d):[0-5]\d:[0-5]\d$/', $event['time'], $matches)){
            $time = $matches[1].$matches[2];
            self::write($path, $time, $event);
        }
    }
}