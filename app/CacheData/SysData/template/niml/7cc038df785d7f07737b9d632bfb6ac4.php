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
echo '<section class="ic section bg-light"><div class="ic blank-30"></div><div class="ic w1200 pa-20 bg-white grid-21" style=" box-shadow: 0px 0px 2px #DDD"><div class="nm-5"><div class="side-list-title">栏目导航</div><ul class="side-list"><li class=""><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/news/category/15/">学院要闻</a></li><li class="actived"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/announcements/">通知公告</a></li><li class=""><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/news/category/16/">校园时讯</a></li><li class=""><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/news/category/20/">媒体建筑</a></li></ul></div><div class="nm-1">&nbsp;</div><div class="nm-15 main-container"><div class="ic blank-10"></div><header><span class="dir">共查找到';
echo $___TOTAL;
echo '条关于 “<span style="color:#f00;">';
echo $___KEYWORD;
echo '</span>” 的结果：</span></header><div class="ic blank-10"></div><ul id="mylist" class="ic listview text-list" data-ic-auto="true" data-list-cols="1">';
foreach($___LIST as $index => $item){
	echo '<li class="list-item pane"><a href="';
	echo $REQUEST->DIR;
	echo 's/';
	echo $item["SET_ALIAS"];
	echo '/article/';
	echo base64_encode($item["ID"]);
	echo '/" target="_blank">';
	echo mb_substr($item["TITLE"],0,23);
	echo ''.PHP_EOL.'</a><span class="pl-right">';
	echo date('Y-m-d',strtotime($item["PUBTIME"]));
	echo '</span></li>';
}
echo '</ul><ul class="ic listview page-list spills red">';
$pages =\Library\ect\Pager::getPageListDataByCount(9);
echo '<li class="list-item" onclick="window.location.href=\'?page=';
echo $pages["f"];
echo '\'">&lt;&lt;</li>';
if( isset($pages["f"]) && $pages["f"] < $___Cpage ){
	echo '<li class="list-item" onclick="window.location.href=\'?page=';
	echo $pages["p"];
	echo '\'">&lt;</li>';
}
$length=$pages["length"];
for($index=0;$index<$length;$index++){
	if( isset($pages[$index]) && $pages[$index] == $___Cpage ){
		echo '<li class="list-item actived" onclick="window.location.href=\'?page=';
		echo $pages[$index];
		echo '\'">';
		echo $pages[$index];
		echo '</li>';
	}
	else{
		echo '<li class="list-item" onclick="window.location.href=\'?page=';
		echo $pages[$index];
		echo '\'">';
		echo $pages[$index];
		echo '</li>';
	}
}
if( isset($pages["l"]) && $pages["l"] > $___Cpage ){
	echo '<li class="list-item" onclick="window.location.href=\'?page=';
	echo $pages["n"];
	echo '\'">&gt;</li>';
}
echo '<li class="list-item" onclick="window.location.href=\'?page=';
echo $pages["l"];
echo '\'">&gt;&gt;</li></ul></div></div><div class="ic blank-50"></div></section>';
$this->including('includes/footer.niml');
echo '<script></script></body></html>';
/*
 * CODE END
 */
