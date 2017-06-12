<?php
/*
 * PHP CODE COMPILED FROM NIML
 * CODE START
 */
echo '<!DOCTYPE html><html lang="zh-CN"><head>';
$this->including('includes/head.niml');
echo '<link type="text/css" rel="stylesheet" href="';
echo $__AF_SRCDIR;
echo 'Interblocks/see/ListView/style.css"><link type="text/css" rel="stylesheet" href="';
echo $__SRCDIR;
echo 'stylesheets/list.css"></head><body>';
$this->including('includes/header.niml');
echo '<section class="ic section bg-light"><div class="ic blank-30"></div><div class="ic w1200 pa-20 bg-white grid-21" style=" box-shadow: 0px 0px 2px #DDD"><div class="nm-5"><div class="side-list-title">栏目导航</div><ul class="side-list"><li class=""><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/party/category/12/">党支部风采</a></li><li class=""><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/party/category/13/">团校</a></li><li class="actived"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/students/category/30/">典型培育</a></li><li class=""><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/party/category/14/">群团组织</a></li><li class=""><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/party/category/11/">青年之声</a></li></ul></div><div class="nm-1">&nbsp;</div><div class="nm-15 main-container"><div class="ic blank-10"></div><header><span class="dir"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/party/">党团思政</a><i>/</i><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/students/category/30/">典型培育</a></span></header><div class="ic blank-10"></div><ul id="mylist" class="ic listview media-list teachers" data-ic-auto="true" data-list-cols="3">';
foreach($___LIST as $index => $item){
	echo '<li class="list-item content pane top-bottom"><figure class="list-image"><a href="';
	echo $REQUEST->REST_HANDLER_DIR;
	echo 's/';
	echo $___PRESET_ALIAS;
	echo '/article/';
	echo base64_encode($item->ID);
	echo '" target=" _blank">';
	if( isset($item->PHOTO) && $item->PHOTO != false ){
		echo '<img src="';
		echo $item->PHOTO;
		echo '">';
	}
	else{
		echo '<img src="';
		echo $__SRCDIR;
		echo 'images/nopic.jpg">';
	}
	echo '</a></figure><div class="list-body"><h4 class="list-title"><a href="';
	echo $REQUEST->REST_HANDLER_DIR;
	echo 's/';
	echo $___PRESET_ALIAS;
	echo '/article/';
	echo base64_encode($item->ID);
	echo '" target=" _blank">';
	echo mb_substr($item->TITLE,0,23);
	echo ''.PHP_EOL.'</a></h4></div></li>';
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
