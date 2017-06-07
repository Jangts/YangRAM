<?php
/*
 * PHP CODE COMPILED FROM NIML
 * CODE START
 */
echo '<!DOCTYPE html><html lang="zh-CN"><head>';
$this->including('includes/head.niml');
echo '<link href="';
echo $__AF_SRCDIR;
echo 'Interblocks/see/Slider/style.css" type="text/css" rel="stylesheet"><link href="';
echo $__SRCDIR;
echo 'stylesheets/list.css" type="text/css" rel="stylesheet"></head><body>';
$this->including('includes/header.niml');
echo '<section class="ic w1180"><div class="ic ma-x20 grid-12"><div class="nm-8"><div class="ic pa-10"><div class="ic h510 slider" id="myslide"><div class="ic ov-hide stage wfull hfull"><div class="troupe"><section class="actor"><img src="';
echo $__SRCDIR;
echo 'images/1.jpg"></section><section class="actor"><img src="';
echo $__SRCDIR;
echo 'images/2.jpg"></section><section class="actor"><img src="';
echo $__SRCDIR;
echo 'images/1.jpg"></section><section class="actor"><img src="';
echo $__SRCDIR;
echo 'images/2.jpg"></section></div><div class="panel bottom-remote al-right bars-rd"><span class="slider-anchor" data-actor-index="0"></span><span class="slider-anchor" data-actor-index="1"></span><span class="slider-anchor" data-actor-index="2"></span><span class="slider-anchor" data-actor-index="3"></span></div></div></div><div class="ic bg-light ma-t20 grid-12">';
foreach($___LIST as $index => $item){
	echo '<div class="nm-6 common-list-item"><div class="ic w360 ma-v15 bg-fff">';
	if( isset($item->dev_figure) && $item->dev_figure == true ){
		echo '<div class="img"><a href="';
		echo $REQUEST->DIR;
		echo 's/news/article/';
		echo base64_encode($item->ID);
		echo '/" target="_blank"><img src="';
		echo $item->dev_figure;
		echo '" alt="';
		echo $item->TITLE;
		echo '"></a></div>';
	}
	echo '<div class="tit"><a href="';
	echo $REQUEST->DIR;
	echo 's/news/article/';
	echo base64_encode($item->ID);
	echo '/" target="_blank">';
	echo $item->TITLE;
	echo '</a></div></div></div>';
}
echo '</div>';
$pages=\Library\ect\Pager::getPageListDataByCount(9);
$length = $pages['length']; $width = ($length+4) * 62 -2;
                    
echo '<ul class="page-list" style="width:';
echo $width;
echo 'px;"><li class="pages-list-item " onclick="window.location.href=\'?page=';
echo $pages["f"];
echo '\'">首页</li>';
if( isset($pages["f"]) && $pages["f"] < $___Cpage ){
	echo '<li class="pages-list-item " onclick="window.location.href=\'?page=';
	echo $pages["p"];
	echo '\'">上一页</li>';
}
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
echo '\'">末页</li></ul></div></div><div class="nm-4"><div class="ic w360 ma-t40 ma-r10 ma-l30 bg-f6"><div class="ic x-h380 pa-v20"><figure class="common-right-image"><img src="';
echo $__SRCDIR;
echo 'images/right1.jpg"></figure><ul class="common-right-list"><li><a target="_blank" href="http://dolphin.yoka.com/c?z=yoka&amp;la=0&amp;si=7&amp;cg=7&amp;c=194&amp;ci=3&amp;or=11&amp;l=99&amp;bg=99&amp;b=112&amp;u=/brand/news/2017/0221/50137901070684.shtml" class="">斐泉亮相格莱美典礼</a></li><li><a target="_blank" href="http://dolphin.yoka.com/c?z=yoka&amp;la=0&amp;si=7&amp;cg=7&amp;c=195&amp;ci=3&amp;or=11&amp;l=98&amp;bg=98&amp;b=113&amp;u=/brand/news/2017/0224/50172401071132.shtml" class="">粉蓝时尚演绎自然美</a></li><li><a target="_blank" href="http://dolphin.yoka.com/c?z=yoka&amp;la=0&amp;si=7&amp;cg=7&amp;c=196&amp;ci=10&amp;or=203&amp;l=1949&amp;bg=1949&amp;b=1910&amp;u=http://clickc.admaster.com.cn/c/a82452,b1569986,c266,i0,m101,8a1,8b2,h"'.PHP_EOL.'class="">Fresh水润花瓣肌</a></li><li><a target="_blank" href="http://dolphin.yoka.com/c?z=yoka&amp;la=0&amp;si=7&amp;cg=7&amp;c=197&amp;ci=3&amp;or=11&amp;l=96&amp;bg=96&amp;b=115&amp;u=/brand/news/2017/0222/50147501070800.shtml" class="">让TA来还你静静吧！</a></li><li><a target="_blank" href="http://dolphin.yoka.com/c?z=yoka&amp;la=0&amp;si=7&amp;cg=7&amp;c=198&amp;ci=99&amp;or=190&amp;l=1805&amp;bg=1805&amp;b=1775&amp;u=https://www.underarmour.cn/Activity/HappyWomensDay2017?utm_channel=display&amp;utm_source=yoka&amp;utm_campaign=womensday&amp;utm_adgroup=hp&amp;utm_content=rightword"'.PHP_EOL.'class="">安德玛X 野兽派礼遇</a></li><li><a target="_blank" href="http://dolphin.yoka.com/c?z=yoka&amp;la=0&amp;si=7&amp;cg=7&amp;c=199&amp;ci=3&amp;or=11&amp;l=94&amp;bg=94&amp;b=117&amp;u=/brand/news/2017/0224/50172901071134.shtml" class="">邀你感受绿色时尚！</a></li></ul></div></div><div class="ic pa-10 ma-t30 ma-l20"><div class="list-right-second"><div class="ic h600 today"><div class="common_arrow_title"><span>护肤热门<i></i></span></div><div class="news" id="today_news"><div class="x-fcut"><a href="/life/mylove/2017/0113/49911701067747.shtml" onclick="_hmt.push([\'_trackEvent\',\'首页_推荐\',\'click\']);" target="_blank">小心！男人这10招让姑娘们防线失守</a></div><div class="x-fcut"><a href="/luxury/ju/2017/0113/pic49913501175540.shtml" onclick="_hmt.push([\'_trackEvent\',\'首页_推荐\',\'click\']);" target="_blank">日日前男友宠女票 定情物是60万蒂芙尼项链</a></div><div class="x-fcut"><a href="/club/photographer/2017/0113/pic49905601174726.shtml" onclick="_hmt.push([\'_trackEvent\',\'首页_推荐\',\'click\']);" target="_blank">麦当娜吊脚袜撩汉 阿曼达穿丝袜拍床照</a></div><div class="x-fcut"><a href="/life/mylove/2017/0113/49911701067747.shtml" onclick="_hmt.push([\'_trackEvent\',\'首页_推荐\',\'click\']);" target="_blank">小心！男人这10招让姑娘们防线失守</a></div><div class="x-fcut"><a href="/luxury/ju/2017/0113/pic49913501175540.shtml" onclick="_hmt.push([\'_trackEvent\',\'首页_推荐\',\'click\']);" target="_blank">日日前男友宠女票 定情物是60万蒂芙尼项链</a></div><div class="x-fcut"><a href="/club/photographer/2017/0113/pic49905601174726.shtml" onclick="_hmt.push([\'_trackEvent\',\'首页_推荐\',\'click\']);" target="_blank">麦当娜吊脚袜撩汉 阿曼达穿丝袜拍床照</a></div></div><div class="news_ad x-fcut"><a target="_blank" href="/" class="">FENDI 2017年早春系列</a></div><div class="change"><a id="today_btn" class="button_btn" href="javascript:;" target="_self" onclick="_hmt.push([\'_trackEvent\',\'首页_推荐\',\'click\']);"><em></em><i>换一组看看</i></a></div></div></div></div></div></div></section>';
$this->including('includes/footer.niml');
echo '<script>iBlock(['.PHP_EOL.'"$_/dom/Elements/",'.PHP_EOL.'"$_/see/Slider/fade.opts"'.PHP_EOL.'], function(pandora, global) {'.PHP_EOL.'var _ = pandora,'.PHP_EOL.'declare = pandora.declareClass,'.PHP_EOL.'cache = pandora.lockerArea,'.PHP_EOL.'document = global.document,'.PHP_EOL.'console = global.console;'.PHP_EOL.'var slider = new _.see.Slider(\'myslide\', \'fade\', {'.PHP_EOL.'speed: 6000,'.PHP_EOL.'duration: 1200,'.PHP_EOL.'kbCtrlAble: true'.PHP_EOL.'});'.PHP_EOL.'}, true);'.PHP_EOL.'</script></body></html>';
/*
 * CODE END
 */
