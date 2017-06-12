<?php
/*
 * PHP CODE COMPILED FROM NIML
 * CODE START
 */
echo '<!DOCTYPE html><html lang="zh-CN"><head>';
$this->including('includes/head.niml');
echo '<link href="';
echo $__SRCDIR;
echo 'stylesheets/detail.css" type="text/css" rel="stylesheet"></head><body>';
$this->including('includes/header.niml');
echo '<section class="ic section bg-light"><div class="ic blank-30"></div><div class="ic w1200 pa-20 bg-white grid-21" style=" box-shadow: 0px 0px 2px #DDD"><div class="nm-15 main-container"><div class="ic blank-10"></div><header><span class="dir"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/news/">学院动态</a><i>/</i><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/announcements/">通知公告</a></span></header><article class="news-content"><header>';
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
echo '</article><div class="ic h50 al-center title-50 ft-999 ft-12 grid-12"><div class="nm-6">上一篇：'.PHP_EOL.'';
if( isset($___Earlier_InSameCategory) && $___Earlier_InSameCategory != false ){
	echo '<a href="';
	echo $REQUEST->REST_HANDLER_DIR;
	echo 's/news/article/';
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
	echo 's/news/article/';
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
echo 's/news/category/15/">学院要问</a></li><li class="actived"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/announcements/">通知公告</a></li><li class=""><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/news/category/16/">校园时讯</a></li><li class=""><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/news/category/20/">媒体建筑</a></li></ul></div></div><div class="ic blank-50"></div></section>';
$this->including('includes/footer.niml');
echo '</body></html>';
/*
 * CODE END
 */
