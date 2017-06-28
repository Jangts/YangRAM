<?php
namespace Tangram\R5;

/**
 *	Uniform Resource Holder
 *	统一资源分配器————又名统一资源持有者
 *  子应用各入口类的基类
 *  **  注：根据子应用的7种不同入口，将持有者分为7类：
 *  **  ***   名称	说明	向后对接	面对对象
 *  **  ***   ContentProvider	内容提供者	一般控制器	操作\方法
 *  **  ***   ResourceBrowser	浏览器视图渲染器	一般控制器	操作\方法
 *  **  ***   OISourceTransfer	维运界面视图渲染器（YangRAM特有）	OIF控制器	操作\方法
 *  **  ***   ResourceSetter	资源安置者	提交控制器	操作\方法
 *  **  ***   ResourceTransfer	表述性状态传输者，读写皆适用	REST模型	资源\数据
 *  **  ***   UnitTester	    单元测试工具，读写皆适用	模型\控制器\模板等	一切单元
 *  **  ***   IPCommunicator	跨进程通讯发起者，读写皆适用	YangRAM进程	子应用
 */
class NI_ResourceHolder_BC {
    protected
    $classalias = NULL,
	$methodoptions = NULL,
	$models = [];
}