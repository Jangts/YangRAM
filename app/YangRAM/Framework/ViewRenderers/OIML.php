<?php
namespace AF\ViewRenderers;

include_once(PATH_LIBX.'tplengines/niml/NIML.php');

class OIML extends \NIML {
	use oifragments\layout;
	use oifragments\form;
	use oifragments\status;
	
    public
	$leftTAG = '{{',
	$rightTAG = '}}';

	public function getFilenames($template, $is_include = false){
		return [AP_CURR.'Views/'.$template.'.oiml', $compiled = PATH_DAT_TPL.'oiml/'.AI_CURR.'/'.$template.".php"];
	}
}
