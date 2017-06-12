<?php
/*
 * PHP CODE COMPILED FROM NIML
 * CODE START
 */
echo '<!DOCTYPE html><head>';
$this->including('includes/head.niml');
echo '<link type="text/css" rel="stylesheet" href="';
echo $__AF_SRCDIR;
echo 'Interblocks/see/Slider/style.css"><link type="text/css" rel="stylesheet" href="';
echo $__SRCDIR;
echo 'stylesheets/homepage.css"></head><body>';
$this->including('includes/header.niml');
echo '<div class="container">';
$this->label('ad_slider1');
echo '<section class="news ic h390 grid-12"><div class="ic hfull nm-8"><div class="newslist ic stabs" data-ic-auto="true"><h3><a href="';
echo $REQUEST->DIR;
echo 's/news/category/15/">学院要闻</a></h3>';
$fields=json_decode('["dev_figure","dev_abstract"]',true);
$newslist=\CM\SPC::getList('news',15,\CM\SPCLite::PUBLISHED,\CM\SPCLite::PUBTIME_DESC,0,9,\Model::LIST_AS_OBJ,$fields);
echo '<ul class="news-figures">';
foreach($newslist as $index => $item){
	echo '<li class="nf-item tab-section"><figure><a href="';
	echo $REQUEST->DIR;
	echo 's/news/article/';
	echo base64_encode($item->ID);
	echo '/" target="_blank"><img src="';
	echo $item->dev_figure;
	echo '"></a><figcaption><h5 class="news-title"><a href="';
	echo $REQUEST->DIR;
	echo 's/news/article/';
	echo base64_encode($item->ID);
	echo '/" target="_blank">';
	echo mb_substr($item->TITLE,0,22);
	echo ''.PHP_EOL.'</a></h5><p class="news-desc">';
	echo mb_substr($item->dev_abstract,0,100);
	echo '...'.PHP_EOL.'</p></figcaption></figure></li>';
}
echo '</ul><ul class="news-metainfs">';
foreach($newslist as $index => $item){
	echo '<li data-tab-index="';
	echo $index;
	echo '" class="nm-item tab-anchor actived"><a href="';
	echo $REQUEST->DIR;
	echo 's/news/article/';
	echo base64_encode($item->ID);
	echo '/" target="_blank">';
	echo mb_substr($item->TITLE,0,22);
	echo ''.PHP_EOL.'</a></li></li>';
}
echo '</ul></div></div><div class="nm-4 "><div class="notices"><h3><a href="';
echo $REQUEST->DIR;
echo 's/announcements/">通知公告</a></h3>';
$annclist=\CM\SPC::getList('announcements',NULL,\CM\SPCLite::PUBLISHED,\CM\SPCLite::PUBTIME_DESC,0,6,\Model::LIST_AS_OBJ);
echo '<ul class="notice-cards">';
foreach($annclist as $index => $item){
	echo '<li class="notice-card"><p class="notice-time">';
	echo date('Y-m-d',strtotime($item->PUBTIME));
	echo '</p><p class="notice-message"><a href="';
	echo $REQUEST->DIR;
	echo 's/announcements/article/';
	echo base64_encode($item->ID);
	echo '/" target="_blank">';
	echo mb_substr($item->TITLE,0,22);
	echo ''.PHP_EOL.'</a></p></li>';
}
echo '</ul><p class="see-more"><a href="';
echo $REQUEST->DIR;
echo 's/announcements/" target="_blank">更多&gt;&gt;</a></p></div></div></section>';
$this->label(2);
echo '<section class="articles ic h310 grid-12"><script id="newstp" type="text/template"><li><a href="{@url}" target="_blank">{@title}</a></li></script><div class="nm-4"><div class="artilisttabs ic w380 h260 ma-25 tabs" data-ic-auto="true" data-tabs-trigger="mouseover" data-tabs-keyclass="actived"><header class="tabanchors"><span class="tab-anchor actived" data-tab-index="0" data-src="';
echo $REQUEST->DIR;
echo 's/news/category/16/">校园时讯</span><span class="tab-anchor" data-tab-index="1" data-src="';
echo $REQUEST->DIR;
echo 's/news/category/20/">媒体建筑</span><i class="see-more"></i></header><ul class="xysx tab-section actived"></ul><ul class="jzmt tab-section"></ul></div></div><div class="nm-4"><div class="artilisttabs ic w380 h260 ma-25 tabs" data-ic-auto="true" data-tabs-trigger="mouseover" data-tabs-keyclass="actived"><header class="tabanchors"><span class="tab-anchor actived" data-tab-index="0" data-src="';
echo $REQUEST->DIR;
echo 's/chuangs/">创新创业</span><span class="tab-anchor" data-tab-index="1" data-src="';
echo $REQUEST->DIR;
echo 's/fazhans/">转型发展</span><i class="see-more"></i></header><ul class="cxcy tab-section actived"></ul><ul class="zxfz tab-section"></ul></div></div><div class="nm-4"><div class="artilisttabs ic w380 h260 ma-25 tabs" data-ic-auto="true" data-tabs-trigger="mouseover" data-tabs-keyclass="actived"><header class="tabanchors"><span class="tab-anchor actived" data-tab-index="0" data-src="';
echo $REQUEST->DIR;
echo 's/jiuyes/">就业信息</span><span class="tab-anchor" data-tab-index="1" data-src="';
echo $REQUEST->DIR;
echo 's/stunews/">校友之家</span><i class="see-more"></i></header><ul class="jyxx tab-section actived"></ul><ul class="xyzj tab-section"></ul></div></div></section><section class="photos"><header class="photos-title"><h3 class="">建筑印象 SCENERY</h3><a href="';
echo $REQUEST->DIR;
echo 's/photos/" class="more-photos">更多&gt;&gt;</a></header>';
$photos=\CM\SPC::getList('photos',NULL,\CM\SPCLite::PUBLISHED,\CM\SPCLite::CTIME_DESC,0,5,\Model::LIST_AS_OBJ);
echo '<ul class="newfive">';
foreach($photos as $index => $item){
	echo '<li><figure><a href="';
	echo $REQUEST->DIR;
	echo 's/photos/article/';
	echo base64_encode($item->ID);
	echo '/" target="_blank"><img src="';
	echo $item->dev_thumb;
	echo '"></a><figcaption><a href="';
	echo $REQUEST->DIR;
	echo 's/photos/article/';
	echo base64_encode($item->ID);
	echo '/" target="_blank">';
	echo mb_substr($item->TITLE,0,18);
	echo '...'.PHP_EOL.'</a></figcaption></figure></li>';
}
echo '</ul></section></div>';
$this->including('includes/footer.niml');
echo '<script type="text/javascript" src="';
echo $__SRCDIR;
echo 'scripts/index.js"></script></body></html>';
/*
 * CODE END
 */
