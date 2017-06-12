<?php
/*
 * PHP CODE COMPILED FROM NIML
 * CODE START
 */
echo '<!DOCTYPE html><html lang="zh-CN"><head>';
$this->including('includes/head.niml');
echo '<link type="text/css" rel="stylesheet" href="';
echo $__AF_SRCDIR;
echo 'Interblocks/see/ListView/style.css"><link href="';
echo $__SRCDIR;
echo 'stylesheets/detail.css" type="text/css" rel="stylesheet"></head><body>';
$this->including('includes/header.niml');
echo '<section class="ic section bg-light"><div class="ic blank-30"></div><div class="ic w1200 pa-20 bg-white grid-21" style=" box-shadow: 0px 0px 2px #DDD"><div class="nm-15 main-container"><div class="ic blank-10"></div><header><span class="dir"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 'g/about/article/index.html">学院概况</a><i>/</i><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/photos/">建筑印象</a></span></header><article class="news-content"><header>';
echo $___TITLE;
echo '</header><div class="news-desc"><span>';
echo $___PUBTIME;
echo '</span><span>编辑：';
echo $___AUTHOR;
echo '</span><span>来源：';
if( isset($___SOURCE) && $___SOURCE != false ){
	echo $___SOURCE;
}
else{
	echo '本站';
}
echo '</span><span>查看：';
echo $___KEY_COUNT;
echo '<span></div>';
echo htmlspecialchars_decode($___CONTENT);
echo '<ul class="ic listview page-list train">';
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
echo '\'">&gt;&gt;</li></ul></article><div class="ic h50 al-center title-50 ft-999 ft-12 grid-12"><div class="nm-6">上一篇：'.PHP_EOL.'';
if( isset($___Earlier_InSameCategory) && $___Earlier_InSameCategory != false ){
	echo '<a href="';
	echo $REQUEST->REST_HANDLER_DIR;
	echo 's/photos/article/';
	echo base64_encode($___Earlier_InSameCategory->ID);
	echo '/">';
	echo mb_substr($___Earlier_InSameCategory->TITLE,0,19);
	echo ''.PHP_EOL.'</a>';
}
else{
	echo '没有了 ';
}
echo '</div><div class="nm-6">下一篇：'.PHP_EOL.'';
if( isset($___Later_InSameCategory) && $___Later_InSameCategory != false ){
	echo '<a href="';
	echo $REQUEST->REST_HANDLER_DIR;
	echo 's/photos/article/';
	echo base64_encode($___Later_InSameCategory->ID);
	echo '/">';
	echo mb_substr($___Later_InSameCategory->TITLE,0,19);
	echo ''.PHP_EOL.'</a>';
}
else{
	echo '没有了 ';
}
echo '</div></div></div><div class="nm-1">&nbsp;</div><div class="nm-5"><div class="side-list-title">栏目导航</div><ul class="side-list"><li class=""><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 'g/about/article/index.html">学院简介</a></li><li class=""><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/leadership/">现任领导</a></li><li class=""><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/teachers/">教师风采</a></li><li class="actived"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/photos/">建筑印象</a></li></ul></div></div><div class="ic blank-50"></div></section>';
$this->including('includes/footer.niml');
echo '</body></html>';
/*
 * CODE END
 */
