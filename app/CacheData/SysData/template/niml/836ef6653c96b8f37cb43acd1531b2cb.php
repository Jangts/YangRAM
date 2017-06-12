<?php
/*
 * PHP CODE COMPILED FROM NIML
 * CODE START
 */
echo '<header class="main-header"><section class="topbar"><div class="container container-1240"><figure class="school-logo"><a href="http://www.whsw.edu.cn/" target="_blank"><img src="';
echo $__SRCDIR;
echo 'images/school-logo.png"></a></figure><form action="/so/" class="topsearch"><input type="text" name="q" class="topsearchi" placeholder="搜索建筑工程学院"><i class="topsearchb"></i></form></div></section><section class="menubar"><figure class="college-logo"><a href="';
echo $REQUEST->DIR;
echo '"><img src="';
echo $__SRCDIR;
echo 'images/college-logo.png"></a></figure><nav class="mainnav"><ul class="ic navmenu inline" data-ic-auto="true"><li class="menu-item hasson ';
if( isset($__Column) && $__Column == 'default' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 'g/about/article/index.html">学院概况</a><ul class="submenu inline" data-width="320"><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_g_about_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 'g/about/article/index.html">学院简介</a></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_leadership_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/leadership/">现任领导</a></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_teachers_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/teachers/">教师风采</a></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_photos_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/photos/">建筑印象</a></li></ul></li><li class="menu-item hasson ';
if( in_array($__Columns[1], ['_s_news_', '_s_announcements_']) != false ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/news/">学院动态</a><ul class="submenu under inline al-center" data-width="320"><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category15' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/news/category/15/">学院要问</a></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_announcements_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/announcements/">通知公告</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category16' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/news/category/16/">校园时讯</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category20' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/news/category/20/">媒体建筑</a></li></ul></li><li class="menu-item hasson ';
if( isset($__Columns[1]) && $__Columns[1] == '_g_departments_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 'g/departments/article/tmgcx.html">机构设置</a><ul class="submenu under inline al-center" data-width="540"><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'tmgcx.html' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 'g/departments/article/tmgcx.html">土木工程系</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'gcglx.html' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 'g/departments/article/gcglx.html">工程管理系</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'jzhjysbgcx.html' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 'g/departments/article/jzhjysbgcx.html">建筑环境与设备工程系</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'dyjys.html' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 'g/departments/article/dyjys.html">德育教研室</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'jzsyzx.html' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 'g/departments/article/jzsyzx.html">建筑实验中心</a></li></ul></li><li class="menu-item hasson ';
if( in_array($__Columns[1], ['_s_enrollment_', '_s_jiuyes_']) != false ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/enrollment/">招生就业</a><ul class="submenu under inline al-center" data-width="430"><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category1' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/enrollment/category/1/">本科生</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category2' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/enrollment/category/2/">专科生</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category3' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/enrollment/category/3/">继续教育</a></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_jiuyes_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/jiuyes/">就业信息</a></li></ul></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_fazhans_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/fazhans/">转型发展</a></li><li class="menu-item hasson ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_party_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/party/">党团思政</a><ul class="submenu under inline al-center" data-width="240"><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category12' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/party/category/12/">常务工作</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category13' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/party/category/13/">政工干部</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category14' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/party/category/14/">团务工作</a></li></ul></li><li class="menu-item hasson ';
if( in_array($__Columns[1], ['_s_chuangs_', '_s_students_']) != false ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/chuangs/">创新创业</a><ul class="submenu under inline al-center" data-width="160"><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_stunews_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/chuangs/">创新创业</a></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_students_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/students/">创业之星</a></li></ul></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_stunews_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/stunews/">校友之家</a></li></ul></nav></section></header>';
/*
 * CODE END
 */
