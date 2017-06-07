<?php
namespace System;

use Application;

/**
 *	Process Bus
 *	任务处理单元，对于核心模块而言，其主要
 *	用来检查当前是否存在一个活动的YangRAM AsyncTask进程，如果不存在则创建一个，对于一般子应用而言，其主要
 *	负责提交任务需求单
 */
final class AsyncTask {
    public static
    $recordpath = PATH_CACA.'WORKERS/actived-process/',
    $notepath = PATH_CAC_LOG.'tasks/',
    $queuepaths = [
        'QUEUE'     =>  [
            'ONCE'   =>  PATH_CAC_TASK.'ONETIME/', //文件名為以任務執行時間精確到小時的10位數字加“.todo”，如2017010109.todo
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
}