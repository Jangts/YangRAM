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
echo $REQUEST->DIR;
echo 's/stunews/category/27/">学院校友会</a></li><li class=""><a href="';
echo $REQUEST->DIR;
echo 's/stunews/category/28/">校友会快讯</a></li><li class=""><a href="';
echo $REQUEST->DIR;
echo 's/students/category/32/">优秀校友</a></li><li class=""><a href="';
echo $REQUEST->DIR;
echo 's/stunews/category/29/">校友捐赠</a></li><li class="actived"><a href="';
echo $REQUEST->DIR;
echo 'contact/">联系我们</a></li></ul></div><div class="nm-1">&nbsp;</div><div class="nm-15 main-container"><div class="ic blank-10"></div><header><span class="dir"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/stunews/">校友之家</a><i>/</i><a href="';
echo $REQUEST->DIR;
echo 'contact/">联系我们</a></span></header><div class="ic blank-10"></div></div></div><div class="ic blank-50"></div></section>';
$this->including('includes/footer.niml');
echo '<script></script></body></html>';
/*
 * CODE END
 */
