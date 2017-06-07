<?php
namespace System\SESS;

/**
 *	Custom Session Interface
 *	自定义SESSION存储方案接口规范
 *	自定义的SESSION存储方案必需尊崇此规范
 */
interface NI_Session_interface {    
    function open($savePath, $sessionName);

    function close();

    function read($id);

    function write($id, $data);

    function destroy($id);

    function gc($maxlifetime);
}
