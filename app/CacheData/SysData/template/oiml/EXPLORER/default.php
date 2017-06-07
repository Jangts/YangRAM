<?php
/*
 * PHP CODE COMPILED FROM NIML
 * CODE START
 */
echo '<view title="';
echo $PAGETITLE;
echo '" lang="';
echo $LANGUAGE;
echo '"><top-vision bgcolor="light"><vision class="logotype"></vision><vision class="searcher"><input value="" onfocus="this.style.backgroundColor=\'#FFF\'" onblur="this.style.backgroundColor=\'#E6E6E6\',this.value=\'\'" placeholder="';
echo $PLACEHOLDER;
echo '"></vision><vision class="click-item check-capacity"><click href="3::capacity/">Capacity</click></vision></top-vision><vision bgcolor="white"><left y-scrollbar="true" bgcolor="silvery"><list>';
foreach($SIDEBAR as $index => $item){
$attrs = ''; foreach($item['attrs'] as $attr=>$val){ $attrs.= ' '.$attr.'="'.$val.'"'; }
                    
	echo '<item';
	echo $attrs;
	echo '>';
	echo $item["text"];
	echo '</item>';
}
echo '</list></left><main posi="right" bgcolor="white"><vision class="main-topbar">';
echo $TOPVISION;
echo '</vision><vision y-scrollbar="true" class="main-content" x-type="tile" src="';
echo $CONTENTSRC;
echo '">';
echo $CONTENT;
echo '</vision></main></vision><bottom-vision bgcolor="dark" hidden=""></bottom-vision></view>';
/*
 * CODE END
 */
