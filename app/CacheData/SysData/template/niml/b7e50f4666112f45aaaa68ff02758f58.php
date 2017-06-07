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
echo '" name="keywords"></head><body><article><header>';
echo $___TITLE;
echo '</header><p><span>Hits: ';
echo $___KEY_COUNT;
echo '<span></p>';
echo htmlspecialchars_decode($___CONTENT);
echo '</article><div><p>Prev:'.PHP_EOL.'';
if( isset($___Earlier_InSameCategory) && $___Earlier_InSameCategory == true ){
	echo '<a href="';
	echo $REQUEST->REST_HANDLER_DIR;
	echo 's/';
	echo $___PRESET_ALIAS;
	echo '/article/';
	echo base64_encode($___Earlier_InSameCategory->ID);
	echo '/">';
	echo mb_substr($___Earlier_InSameCategory->TITLE,0,19);
	echo ''.PHP_EOL.'</a>';
}
else{
	echo 'None ';
}
echo '</p><p>Next:'.PHP_EOL.'';
if( isset($___Later_InSameCategory) && $___Later_InSameCategory == true ){
	echo '<a href="';
	echo $REQUEST->REST_HANDLER_DIR;
	echo 's/';
	echo $___PRESET_ALIAS;
	echo '/article/';
	echo base64_encode($___Later_InSameCategory->ID);
	echo '/">';
	echo mb_substr($___Later_InSameCategory->TITLE,0,19);
	echo ''.PHP_EOL.'</a>';
}
else{
	echo 'None ';
}
echo '</p></body></html>';
/*
 * CODE END
 */
