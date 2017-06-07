<?php
/*
 * PHP CODE COMPILED FROM NIML
 * CODE START
 */
echo '<!DOCTYPE html><head>';
$this->including('includes/head.niml');
echo '<link type="text/css" rel="stylesheet" href="http://interblocks.nidn.yangram.ni/src/see/Slider/style.css"><link type="text/css" rel="stylesheet" href="';
echo $__SRCDIR;
echo 'stylesheets/homepage.css"></head><body>';
$this->including('includes/header.niml');
echo '<div class="container">';
$this->label('ad_slider1');
echo '<section class="news ic h390 grid-12"><div class="ic hfull nm-8"><div class="newslist ic stabs" data-ic-auto="true"><h3>学院要闻</h3>';
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
echo '</ul></div></div><div class="nm-4 "><div class="notices"><h3>通知公告</h3>';
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
echo '</ul><p class="see-more"><a href="" target="_blank">更多&gt;&gt;</a></p></div></div></section>';
$this->label(2);
echo '<section class="articles ic h310 grid-12"><script id="newstp" type="text/template"><li><a href="{@url}" target="_blank">{@title}</a></li></script><div class="nm-4"><div class="artilisttabs ic w380 h260 ma-25 tabs" data-ic-auto="true" data-tabs-trigger="mouseover" data-tabs-keyclass="actived"><header class="tabanchors"><span class="tab-anchor actived" data-tab-index="0" data-src="">校园时讯</span><span class="tab-anchor" data-tab-index="1" data-src="">媒体建筑</span><i class="see-more"></i></header><ul class="xysx tab-section actived"></ul><ul class="jzmt tab-section"></ul></div></div><div class="nm-4"><div class="artilisttabs ic w380 h260 ma-25 tabs" data-ic-auto="true" data-tabs-trigger="mouseover" data-tabs-keyclass="actived"><header class="tabanchors"><span class="tab-anchor actived" data-tab-index="0" data-src="">创新创业</span><span class="tab-anchor" data-tab-index="1" data-src="">转型发展</span><i class="see-more"></i></header><ul class="cxcy tab-section actived"></ul><ul class="zxfz tab-section"></ul></div></div><div class="nm-4"><div class="artilisttabs ic w380 h260 ma-25 tabs" data-ic-auto="true" data-tabs-trigger="mouseover" data-tabs-keyclass="actived"><header class="tabanchors"><span class="tab-anchor actived" data-tab-index="0" data-src="">就业信息</span><span class="tab-anchor" data-tab-index="1" data-src="">校友之家</span><i class="see-more"></i></header><ul class="jyxx tab-section actived"></ul><ul class="xyzj tab-section"></ul></div></div></section><section class="photos"><header class="photos-title"><h3 class="">建筑印象 SCENERY</h3><a href="" class="more-photos">更多&gt;&gt;</a></header>';
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
echo '<script type="text/javascript">var includes = ['.PHP_EOL.'\'$_/data/\','.PHP_EOL.'\'$_/data/Base64.Cls\','.PHP_EOL.'\'$_/dom/Elements/\','.PHP_EOL.'\'$_/dom/Template.Cls\','.PHP_EOL.'\'$_/see/Slider/\','.PHP_EOL.'\'$_/see/Slider/colx3.opts\','.PHP_EOL.'\'$_/see/Slider/slide-vert.opts\''.PHP_EOL.'];'.PHP_EOL.'iBlock(includes, function(_, global, undefined) {'.PHP_EOL.'var document = global.document;'.PHP_EOL.'var location = global.location;'.PHP_EOL.'var $ = _.dom.select;'.PHP_EOL.'$(\'#myslide\').each(function(index, element) {'.PHP_EOL.'new _.see.Slider(this, \'colx3\', {'.PHP_EOL.'kbCtrlAble: true'.PHP_EOL.'});'.PHP_EOL.'});'.PHP_EOL.'$(\'.banners\').each(function(index, element) {'.PHP_EOL.'new _.see.Slider(this, \'slide-vert\', {'.PHP_EOL.'kbCtrlAble: false'.PHP_EOL.'});'.PHP_EOL.'});'.PHP_EOL.'$(\'.artilisttabs .see-more\').click(function() {'.PHP_EOL.'var url = $(\'.tab-anchor.actived\', this.parentNode).data(\'src\');'.PHP_EOL.'if (url) {'.PHP_EOL.'global.open(url, \'_blank\');'.PHP_EOL.'}'.PHP_EOL.'});'.PHP_EOL.'var newscats = {'.PHP_EOL.'xysx: {'.PHP_EOL.'set_alias: \'news\','.PHP_EOL.'url: \'';
echo $REQUEST->DIR;
echo 'o/contents/spc/get_list_by_cat/16/2/0/6/\''.PHP_EOL.'},'.PHP_EOL.'jzmt: {'.PHP_EOL.'set_alias: \'news\','.PHP_EOL.'url: \'';
echo $REQUEST->DIR;
echo 'o/contents/spc/get_list_by_cat/20/2/0/6/\''.PHP_EOL.'},'.PHP_EOL.'cxcy: {'.PHP_EOL.'set_alias: \'chuangs\','.PHP_EOL.'url: \'';
echo $REQUEST->DIR;
echo 'o/contents/spc/get_list_by_preset/chuangs/2/0/6/\''.PHP_EOL.'},'.PHP_EOL.'zxfz: {'.PHP_EOL.'set_alias: \'fazhans\','.PHP_EOL.'url: \'';
echo $REQUEST->DIR;
echo 'o/contents/spc/get_list_by_preset/fazhans/2/0/6/\''.PHP_EOL.'},'.PHP_EOL.'jyxx: {'.PHP_EOL.'set_alias: \'jiuyes\','.PHP_EOL.'url: \'';
echo $REQUEST->DIR;
echo 'o/contents/spc/get_list_by_preset/jiuyes/2/0/6/\''.PHP_EOL.'},'.PHP_EOL.'xyzj: {'.PHP_EOL.'set_alias: \'stunews\','.PHP_EOL.'url: \'';
echo $REQUEST->DIR;
echo 'o/contents/spc/get_list_by_preset/stunews/2/0/6/\''.PHP_EOL.'}'.PHP_EOL.'},'.PHP_EOL.'coder = new _.data.Base64(),'.PHP_EOL.'template = new _.dom.Template($(\'#newstp\').html());'.PHP_EOL.'_.each(newscats, function(cat, meta) {'.PHP_EOL.'_.data.json(meta.url, function(data) {'.PHP_EOL.'template.clear();'.PHP_EOL.'_.each(data, function(i, news) {'.PHP_EOL.'template.complie({'.PHP_EOL.'url: \'';
echo $REQUEST->DIR;
echo 's/\' + news.SET_ALIAS + \'/article/\' + coder.encode(news.ID),'.PHP_EOL.'title: news.TITLE'.PHP_EOL.'});'.PHP_EOL.'});'.PHP_EOL.'template.echo(), $(\'.artilisttabs .tab-section.\' + cat).html(template.echo());'.PHP_EOL.'}, function() {'.PHP_EOL.'console.log(cat + \'加载失败\');'.PHP_EOL.'})'.PHP_EOL.'});'.PHP_EOL.'}, true);'.PHP_EOL.'</script></body></html>';
/*
 * CODE END
 */
