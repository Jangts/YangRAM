<?php
/*
 * PHP CODE COMPILED FROM NIML
 * CODE START
 */
echo '<html lang="zh"><head><meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>';
echo $__Title;
echo '</title><meta content="';
echo $__Desc;
echo '" name="description"><meta content="';
echo $__KeyWords;
echo '" name="keywords"></head><body><ul class=" " id="news-list">';
foreach($___LIST as $index => $item){
	echo '<li class="ic ma-b20"><p><a class="title ic grid-21 " href="';
	echo $REQUEST->REST_HANDLER_DIR;
	echo 's/';
	echo $___PRESET_ALIAS;
	echo '/article/';
	echo base64_encode($item->ID);
	echo '" target=" _blank">';
	echo mb_substr($item->TITLE,0,23);
	echo ''.PHP_EOL.'</a></p><p><span>';
	echo date('Y-m-d');
	echo '</span></p><p>';
	echo $item->dev_abstract;
	echo '.....</p><p><a class="title ic grid-21 " href="';
	echo $REQUEST->REST_HANDLER_DIR;
	echo 's/';
	echo $___PRESET_ALIAS;
	echo '/article/';
	echo base64_encode($item->ID);
	echo '" target=" _blank">See More&gt;&gt;</a><p></li>';
}
echo '</ul><ul class="page-list page-list-side">';
$pages=\Library\ect\Pager::getPageListDataByCount(9);
echo '<li class="pages-list-item "><a href="\'?page=';
echo $pages["f"];
echo '\'">First</a></li>';
if( isset($pages["f"]) && $pages["f"] < $___Cpage ){
	echo '<li class="pages-list-item "><a href="\'?page=';
	echo $pages["p"];
	echo '\'">Prev</a></li>';
}
$length=$pages["length"];
for($index=0;$index<$length;$index++){
	if( isset($pages[$index]) && $pages[$index] == $___Cpage ){
		echo '<li class="pages-list-item curr "><a href="\'?page=';
		echo $pages[$index];
		echo '\'">';
		echo $pages[$index];
		echo '</a></li>';
	}
	else{
		echo '<li class="pages-list-item "><a href="\'?page=';
		echo $pages[$index];
		echo '\'">';
		echo $pages[$index];
		echo '</a></li>';
	}
}
if( isset($pages["l"]) && $pages["l"] > $___Cpage ){
	echo '<li class="pages-list-item "><a href="\'?page=';
	echo $pages["n"];
	echo '\'">Next</a></li>';
}
echo '<li class="pages-list-item "><a href="\'?page=';
echo $pages["l"];
echo '\'">Last</a></li></ul></div></body></html>';
/*
 * CODE END
 */
