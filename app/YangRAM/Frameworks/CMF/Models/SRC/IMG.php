<?php
namespace CMF\Models\SRC;
use Status;

/**
 *	Picture Resourece Object Model
 *	图像文件信息模型
 */
class IMG extends DOC {
	protected static
    $type = 'img',
    $ca_path = PATH_DAT_CNT.'sources/images/',
    $table = DB_SRC.'img',
	$defaults = [
        'SID'               =>  0,
        'HASH'              =>  '',
        'LOCATION'          =>  '',
        'MIME'              =>  '',
        'IMAGE_SIZE'        =>  '',
		'WIDTH'             =>  0,
		'HEIGHT'            =>  0,
        'KEY_CTIME'       =>  DATETIME
    ];
}
