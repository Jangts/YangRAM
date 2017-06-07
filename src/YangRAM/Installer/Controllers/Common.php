<?php
namespace Installer\Controllers;
use Status;
use Request;
use Application;
use Controller;

final class Common extends Controller {
    public function __construct(Application $app, Request $request){
        /**
		 * YangRAM服务支持中心用以为在籍YangRAM I4s及其在用子应用提供验证、更新和升级的应用
		 * 此应用为YangRAM服务支持中心所使用的I4s独有，其他任何版本的YangRAM I4s都不会预装此应用
		 * 正常情况下，此应用仅对请求主机为support.yangram.com的请求做出有效回应
		 * 当前为开发阶段，此应用亦会对support.yangram.ni和nidn.yangram.ni做出有效回应
		 */
        if((AI_CURR==='INSTALLER')&&($app->APPID===AI_CURR)){
            $this->request = $request;
            $this->app = $app;
        }else{
            new Status(706.1, 'Private Interface', true);
        }
	}
}