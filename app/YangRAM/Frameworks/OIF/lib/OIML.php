<?php
namespace OIF\lib;

include_once(PATH_LIBX.'niml/lib/NIML.php');

class OIML extends \NIML {
	use layout;
	use form;
	use status;
	
    public
	$leftTAG = '{{',
	$rightTAG = '}}';

	public function getFilenames($template, $is_include = false){
		return [AP_CURR.'Views/'.$template.'.oiml', $compiled = PATH_DAT_TPL.'oiml/'.AI_CURR.'/'.$template.".php"];
	}
}
