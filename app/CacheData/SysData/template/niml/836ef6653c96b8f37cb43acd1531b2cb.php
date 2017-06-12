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
echo 'g/about/article/index.html">学院概况</a><ul class="submenu inline" data-width="400"><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'index.html' ){
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
if( isset($__Columns[2]) && $__Columns[2] == 'majors.html' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 'g/about/article/majors.html">专业介绍</a></li><li class="menu-item ';
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
echo 's/news/">学院动态</a><ul class="submenu under inline al-center" data-width="400"><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category15' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/news/category/15/">学院要闻</a></li><li class="menu-item ';
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
echo 's/news/category/20/">媒体建筑</a></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_announcements_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/announcements/">通知公告</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category21' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/news/category/21/">抬头看路</a></li></ul></li><li class="menu-item hasson ';
if( isset($__Columns[1]) && $__Columns[1] == '_g_departments_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/departments/">机构设置</a><ul class="submenu under inline al-center" data-width="540"><li class="menu-item ';
if( isset($___CATEGORY_ID) && $___CATEGORY_ID == '33' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/departments/category/33/">土木工程系</a></li><li class="menu-item ';
if( isset($___CATEGORY_ID) && $___CATEGORY_ID == '34' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/departments/category/34/">工程管理系</a></li><li class="menu-item ';
if( isset($___CATEGORY_ID) && $___CATEGORY_ID == '35' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/departments/category/35/">建筑环境与设备工程系</a></li><li class="menu-item ';
if( isset($___CATEGORY_ID) && $___CATEGORY_ID == '36' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/departments/category/36/">德育教研室</a></li><li class="menu-item ';
if( isset($___CATEGORY_ID) && $___CATEGORY_ID == '37' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->REST_HANDLER_DIR;
echo 's/departments/category/37/">工程实验中心</a></li></ul></li><li class="menu-item hasson ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_enrollment_' ){
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
if( isset($__Columns[2]) && $__Columns[2] == 'category5' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/enrollment/category/5/">3+2专升本</a></li><li class="menu-item ';
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
if( isset($__Columns[2]) && $__Columns[2] == 'category4' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/enrollment/category/4/">就业信息</a></li></ul></li><li class="menu-item hasson ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_fazhans_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/fazhans/">转型发展</a><ul class="submenu under inline al-center" data-width="400"><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category6' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/fazhans/category/6/">科研成果</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category7' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/fazhans/category/7/">学科竞赛</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category8' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/fazhans/category/8/">教师培训</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category9' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/fazhans/category/9/">教学改革</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category10' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/fazhans/category/10/">校企合作</a></li></ul></li><li class="menu-item hasson ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_party_' ){
	echo 'actived';
}
elseif(
isset($__Columns[2]) && $__Columns[2] == 'category30' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/party/">党团思政</a><ul class="submenu under inline al-center" data-width="430"><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category12' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/party/category/12/">党支部风采</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category13' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/party/category/13/">团校</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category30' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/students/category/30/">典型培育</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category14' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/party/category/14/">群团组织</a></li><li class="menu-item ';
if( isset($__Columns[2]) && $__Columns[2] == 'category11' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/party/category/11/">青年之声</a></li></ul></li><li class="menu-item hasson ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_chuangs_' ){
	echo 'actived';
}
elseif(
isset($__Columns[2]) && $__Columns[2] == 'category30' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/chuangs/">创新创业</a><ul class="submenu under inline al-center" data-width="400"><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_stunews_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/chuangs/category//">双创赛事</a></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_students_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/chuangs/category//">双创典型</a></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_stunews_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/chuangs/category//">项目展示</a></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_students_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/chuangs/category//">创梦讲话</a></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_stunews_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/chuangs/category//">品牌活动</a></li></ul></li><li class="menu-item hasson ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_stunews_' ){
	echo 'actived';
}
elseif(
isset($__Columns[2]) && $__Columns[2] == 'category30' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/stunews/">校友之家</a><ul class="submenu under inline al-right" data-width="400"><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_stunews_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/stunews/">双创赛事</a></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_students_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/stunews/">双创典型</a></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_stunews_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/stunews/">项目展示</a></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_students_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/stunews/">创梦讲话</a></li><li class="menu-item ';
if( isset($__Columns[1]) && $__Columns[1] == '_s_stunews_' ){
	echo 'actived';
}
echo '"><a href="';
echo $REQUEST->DIR;
echo 's/stunews/">品牌活动</a></li></ul></li></ul></nav></section></header>';
/*
 * CODE END
 */
