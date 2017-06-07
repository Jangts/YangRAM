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
echo '<section class="ic section bg-light"><div class="ic blank-50"></div><div class="ic w1200 bg-white grid-21" style=" box-shadow: 0px 0px 5px #CCC"><div class="nm-5"><div><div class="nav-left">栏目导航</div><ul class="nav-left-list"><li class="';
if( isset($___CATEGORY_ID) && $___CATEGORY_ID == '6' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/news/category/6/">校友动态</a></li><li class="';
if( isset($___CATEGORY_ID) && $___CATEGORY_ID == '7' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/news/category/7/">母校动态</a></li><li class="';
if( isset($___CATEGORY_ID) && $___CATEGORY_ID == '2' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/publications/category/2/">《校友通讯》</a></li><li class="';
if( isset($___CATEGORY_ID) && $___CATEGORY_ID == '3' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/publications/category/3/">《校友风采》</a></li></ul></div><div class="ic blank-10"></div></div><div class="nm-half">&nbsp;</div><div class="nm-15"><article class="news-content"><header>';
echo $___TITLE;
echo '</header><div class="news-desc"><span>';
echo $___PUBTIME;
echo '</span><span>编辑：';
echo $___AUTHOR;
echo '</span><span>来源：';
if( isset($___SOURCE) && $___SOURCE == true ){
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
if( isset($___Earlier_InSameCategory) && $___Earlier_InSameCategory == true ){
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
if( isset($___Later_InSameCategory) && $___Later_InSameCategory == true ){
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
echo '</div></div></div></div><div class="ic blank-50"></div></section>';
$this->including('includes/footer.niml');
echo '</body></html>';
/*
 * CODE END
 */
