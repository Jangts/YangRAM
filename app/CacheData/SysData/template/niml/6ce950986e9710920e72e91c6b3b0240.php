<?php
/*
 * PHP CODE COMPILED FROM NIML
 * CODE START
 */
echo '<!DOCTYPE html><html lang="zh-CN"><head>';
$this->including('includes/head.niml');
echo '<link type="text/css" rel="stylesheet" href="';
echo $__AF_SRCDIR;
echo 'Interblocks/see/Slider/style.css"><link type="text/css" rel="stylesheet" href="';
echo $__AF_SRCDIR;
echo 'Interblocks/see/ListView/style.css"><link type="text/css" rel="stylesheet" href="';
echo $__SRCDIR;
echo 'stylesheets/list.css"></head><body>';
$this->including('includes/header.niml');
echo '<section class="ic section bg-light"><div class="ic blank-30"></div><div class="ic w1200 pa-20 bg-white grid-21" style=" box-shadow: 0px 0px 2px #DDD"><div class="nm-5"><div class="side-list-title">栏目导航</div><ul class="side-list"><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'tmgcx.html' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 'g/departments/article/tmgcx.html">土木工程系</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'gcglx.html' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 'g/departments/article/gcglx.html">工程管理系</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'jzhjysbgcx.html' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 'g/departments/article/jzhjysbgcx.html">建筑环境与设备工程系</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'dyjys.html' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 'g/departments/article/dyjys.html">德育教研室</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'jzsyzx.html' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 'g/departments/article/jzsyzx.html">建筑实验中心</a></li></ul></div><div class="nm-1">&nbsp;</div><div class="nm-15 main-container"><div class="ic blank-10"></div><header><span class="dir"><a href="';
echo $REQUEST->DIR;
echo 'g/departments/article/tmgcx.htm">机构设置</a><i>/</i>';
switch($__Columns[2]){
	case 'tmgcx.html':
	echo '土木工程系';
	break;
	case 'gcglx.html':
	echo '工程管理系';
	break;
	case 'jzhjysbgcx.html':
	echo '建筑环境与设备工程系';
	break;
	case 'dyjys.html':
	echo '德育教研室';
	break;
	case 'jzsyzx.html':
	echo '建筑实验中心';
	break;
}
echo '</span></header><article class="news-content"><header>';
echo $___TITLE;
echo '</header><div class="news-desc"><span>';
echo $___PUBTIME;
echo '</span><span>编辑：';
echo $___AUTHOR;
echo '</span><span>查看：';
echo $___KEY_COUNT;
echo '<span></div>';
echo htmlspecialchars_decode($___CONTENT);
echo '</article><div class="ic blank-10"></div></div></div><div class="ic blank-50"></div></section>';
$this->including('includes/footer.niml');
echo '<script></script></body></html>';
/*
 * CODE END
 */
