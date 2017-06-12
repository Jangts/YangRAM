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
echo '<section class="ic section bg-light"><div class="ic blank-30"></div><div class="ic w1200 pa-20 bg-white grid-21" style=" box-shadow: 0px 0px 2px #DDD"><div class="nm-5"><div class="side-list-title">栏目导航</div><ul class="side-list"><li class="';
if( isset($___CATEGORY_ID) && $___CATEGORY_ID == '6' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/';
echo $___PRESET_ALIAS;
echo '/category/6/">科研成果</a></li><li class="';
if( isset($___CATEGORY_ID) && $___CATEGORY_ID == '7' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/';
echo $___PRESET_ALIAS;
echo '/category/7/">学科竞赛</a></li><li class="';
if( isset($___CATEGORY_ID) && $___CATEGORY_ID == '8' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/';
echo $___PRESET_ALIAS;
echo '/category/8/">教师培训</a></li><li class="';
if( isset($___CATEGORY_ID) && $___CATEGORY_ID == '9' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/';
echo $___PRESET_ALIAS;
echo '/category/9/">教学改革</a></li><li class="';
if( isset($___CATEGORY_ID) && $___CATEGORY_ID == '10' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/';
echo $___PRESET_ALIAS;
echo '/category/10/">校企合作</a></li></ul></div><div class="nm-1">&nbsp;</div><div class="nm-15 main-container"><div class="ic blank-10"></div><header><span class="dir"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/';
echo $___PRESET_ALIAS;
echo '/">转型发展</a><i>/</i><a href=" ';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/';
echo $___PRESET_ALIAS;
echo '/category/';
echo $___CATEGORY_ID;
echo '/">';
switch($___CATEGORY_ID){
	case '6':
	echo '科研成果';
	break;
	case '7':
	echo '学科竞赛';
	break;
	case '8':
	echo '教师培训';
	break;
	case '9':
	echo '教学改革';
	break;
	case '10':
	echo '校企合作';
	break;
}
echo '</a></span></header><div class="ic blank-10"></div><ul id="mylist" class="ic listview media-list" data-ic-auto="true">';
foreach($___LIST as $index => $item){
	echo '<li class="list-item content underline"><figure class="list-figure h117"><a href="';
	echo $REQUEST->REST_HANDLER_DIR;
	echo 's/';
	echo $___PRESET_ALIAS;
	echo '/article/';
	echo base64_encode($item->ID);
	echo '" target=" _blank">';
	if( isset($item->dev_figure) && $item->dev_figure != false ){
		echo '<img src="';
		echo $item->dev_figure;
		echo '">';
	}
	else{
		echo '<img src="';
		echo $__SRCDIR;
		echo 'images/nopic.jpg">';
	}
	echo '</a></figure><div class="list-body" style="width: 664px;"><h4 class="list-title"><a href="';
	echo $REQUEST->REST_HANDLER_DIR;
	echo 's/';
	echo $___PRESET_ALIAS;
	echo '/article/';
	echo base64_encode($item->ID);
	echo '" target=" _blank">';
	echo mb_substr($item->TITLE,0,23);
	echo ''.PHP_EOL.'</a><span class="pl-right">';
	echo date('Y-m-d');
	echo '</span></h4><p class="list-abstract" data-row="3">';
	echo $item->dev_abstract;
	echo '...</p><p class="list-meta pl-right bottom"><a href="';
	echo $REQUEST->REST_HANDLER_DIR;
	echo 's/';
	echo $___PRESET_ALIAS;
	echo '/article/';
	echo base64_encode($item->ID);
	echo '" target=" _blank">更多&gt;&gt;</a></p></div></li>';
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
