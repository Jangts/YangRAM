<?php
namespace CM\SRC;
use Status;

/**
 *	Video Resourece Object Model
 *	视频文件信息模型
 */
class VOD extends DOC {
	protected static
    $type = 'vod',
    $ca_path = PATH_DAT_CNT.'sources/videos/',
    $table = DB_SRC.'vod',
	$defaults = [
        'SID'               =>  0,
        'HASH'              =>  '',
        'LOCATION'          =>  '',
        'MIME'              =>  '',
        'DURATION'          =>  0,
        'WIDTH'             =>  0,
		'HEIGHT'            =>  0,
        'KEY_CTIME'       =>  DATETIME
    ];
}
