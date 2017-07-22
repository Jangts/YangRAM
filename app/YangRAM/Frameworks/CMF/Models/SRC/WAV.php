<?php
namespace CMF\Models\SRC;
use Status;

/**
 *	Waveform Resourece Object Model
 *	波形文件信息模型
 */
class WAV extends DOC {
	protected static
    $type = 'wav',
    $ca_path = PATH_DAT_CNT.'sources/audios/',
    $table = DB_SRC.'wav',
	$defaults = [
        'SID'               =>  0,
        'HASH'              =>  '',
        'LOCATION'          =>  '',
        'MIME'              =>  '',
        'DURATION'          =>  0,
        'KEY_CTIME'       =>  DATETIME
    ];
}
