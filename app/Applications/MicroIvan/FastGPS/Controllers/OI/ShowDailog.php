<?php
namespace GPS\Controllers\OI;

use AF\Models\Util\THMI;
use GPS\Models\Data\LocalDict;

class ShowDailog extends \Controller {
    public function themes(){
        $themes = THMI::all(AI_CURR);

		echo '<list class="theme-list">';
		if(count($themes)>0){
			foreach($themes as $theme){
				echo '<item class="theme" data-theme-alias="'.$theme->alias.'">'.$theme->name.'</item>';
			}			
		}else{
			
		}
		echo '</list>';
    }

    public function templates($theme){
        $path = PATH_VIEW.$theme.'/nimls';
		echo '<list class="template-list">';
		echo '<item class="themes">.../</item>';
		if(is_dir($path)){
			function getfiles($path, $theme, $pre = ''){
				$handle  = opendir($path);
				while( false !== ($file = readdir($handle))) {
				if($file != '.'&&$file!='..') {
					$childPath = $path.'/'.$file;
					if(is_dir($childPath)) {
						getfiles($childPath, $theme, $pre.'/'.$file);
					}else{
						echo '<item class="template" data-theme-alias="'.$theme.'" data-template-path="'.$pre.'/'.$file.'">'.$pre.'/'.$file.'</item>';
						}
					}
				}  
			}
			getfiles($path, $theme);
		}else{

		}
		echo '</list>';
    }
}
