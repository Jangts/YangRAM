<?php
namespace CM\SRC;
use Status;

/**
 *	Text Resourece Object Model
 *	文本文档信息模型
 */
class TXT extends DOC {
    protected static
    $type = 'txt',
    $ca_path = PATH_DAT_CNT.'sources/texts/',
    $table = DB_SRC.'txt';
}
