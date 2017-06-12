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
echo 'g/about/article/index.html">学院简介</a></li><li class=""><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/leadership/">现任领导</a></li><li class=""><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/teachers/">教师风采</a></li><li class=""><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/photos/">建筑印象</a></li></ul></div><div class="nm-1">&nbsp;</div><div class="nm-15 main-container"><div class="ic blank-10"></div><header><span class="dir"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 'g/about/article/index.html">学院概况</a><i>/</i>';
echo $___TITLE;
echo '</span></header><div class="ic blank-10"></div><article class="news-content">';
echo htmlspecialchars_decode($___CONTENT);
echo '</article></div></div><div class="ic blank-50"></div></section>';
$this->including('includes/footer.niml');
echo '<script></script></body></html>';
/*
 * CODE END
 */
