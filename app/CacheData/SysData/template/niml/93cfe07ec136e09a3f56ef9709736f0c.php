<?php
/*
 * PHP CODE COMPILED FROM NIML
 * CODE START
 */
echo '<!DOCTYPE html><html lang="zh-CN"><head>';
$this->including('includes/head.niml');
echo '<link href="http://interblocks.nidn.yangram.ni/src/see/Slider/style.css" type="text/css" rel="stylesheet"><link href="';
echo $__SRCDIR;
echo 'stylesheets/list.css" type="text/css" rel="stylesheet"></head><body>';
$this->including('includes/header.niml');
echo '<section class="ic section bg-light"><div class="ic blank-30"></div><div class="ic w1200 pa-20 bg-white grid-21" style=" box-shadow: 0px 0px 2px #DDD"><div class="nm-5"><div class="side-list-title">栏目导航</div><ul class="side-list"><li class="';
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
echo 's/news/category/7/">校友动态</a></li><li class="';
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
echo 's/publications/category/3/">《校友风采》</a></li></ul></div><div class="nm-1">&nbsp;</div><div class="nm-15 main-container"><div class="ic blank-10"></div><header><span class="dir"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/news/">校友动态</a><i>/</i><a href=" ';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/news/category/';
echo $___CATEGORY_ID;
echo '/">';
switch($___CATEGORY_ID){
	case '6':
	echo '校友动态';
	break;
	case '7':
	echo '母校动态';
	break;
}
echo '</a></span></header><div class="ic blank-10"></div><ul id="mylist" class="ic list media-list">';
foreach($___LIST as $index => $item){
	echo '<li class="ic ma-b20"><a class="title ic grid-21 " href="';
	echo $REQUEST->REST_HANDLER_DIR;
	echo 's/news/article/';
	echo base64_encode($item->ID);
	echo '" target=" _blank"><div class="ic nm-6"><div class="figure-box">';
	if( isset($item->dev_figure) && $item->dev_figure == true ){
		echo '<figure><img src="';
		echo $item->dev_figure;
		echo '"></figure>';
	}
	else{
		echo '<figure><img src="';
		echo $__SRCDIR;
		echo 'images/nopic.jpg"></figure>';
	}
	echo '</div></div><div class="nm-15 ic grid-21"><div class="nm-1">&nbsp;</div><div class="nm-20"><div class="ic ft-666 ft-18 lh-36">';
	if( isset($item->dev_essence) && $item->dev_essence == '1' ){
		if( isset($item->IS_TOP) && $item->IS_TOP == '1' ){
			echo mb_substr($item->TITLE,0,16);
			echo ''.PHP_EOL.'<i class="top">置顶</i>';
		}
		else{
			echo mb_substr($item->TITLE,0,19);
			echo '';
		}
		echo '<i class="essence">精华</i>';
	}
	else{
		if( isset($item->IS_TOP) && $item->IS_TOP == '1' ){
			echo mb_substr($item->TITLE,0,19);
			echo ''.PHP_EOL.'<i class="top">置顶</i>';
		}
		else{
			echo mb_substr($item->TITLE,0,23);
			echo '';
		}
}
	echo '<span>';
	echo date('Y-m-d');
	echo '</span></div><div class="news-tips ic al-just ft-14 ft-999 lh-18 indent-20">';
	echo $item->dev_abstract;
	echo '.....</div><div class="ic al-right ft-12 ft-999 lh-21 pa-v05 pa-none-bottom">更多&gt;&gt;</div></div></div></a></li>';
}
echo '</ul><ul class="page-list page-list-side">';
$pages =\Library\ect\Pager::getPageListDataByCount(9);
echo '<li class="pages-list-item " onclick="window.location.href=\'?page=';
echo $pages["f"];
echo '\'">首页</li>';
if( isset($pages["f"]) && $pages["f"] < $___Cpage ){
	echo '<li class="pages-list-item " onclick="window.location.href=\'?page=';
	echo $pages["p"];
	echo '\'">上一页</li>';
}
$length=$pages["length"];
for($index=0;$index<$length;$index++){
	if( isset($pages[$index]) && $pages[$index] == $___Cpage ){
		echo '<li class="pages-list-item curr " onclick="window.location.href=\'?page=';
		echo $pages[$index];
		echo '\'">';
		echo $pages[$index];
		echo '</li>';
	}
	else{
		echo '<li class="pages-list-item " onclick="window.location.href=\'?page=';
		echo $pages[$index];
		echo '\'">';
		echo $pages[$index];
		echo '</li>';
	}
}
if( isset($pages["l"]) && $pages["l"] > $___Cpage ){
	echo '<li class="pages-list-item " onclick="window.location.href=\'?page=';
	echo $pages["n"];
	echo '\'">下一页</li>';
}
echo '<li class="pages-list-item " onclick="window.location.href=\'?page=';
echo $pages["l"];
echo '\'">末页</li></ul></div></div><div class="ic blank-50"></div></section>';
$this->including('includes/footer.niml');
echo '<script></script></body></html>';
/*
 * CODE END
 */
