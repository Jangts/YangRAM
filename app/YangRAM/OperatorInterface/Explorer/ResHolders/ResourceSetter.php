<?php
namespace Explorer\ResHolders;

class ResourceSetter extends \AF\ResourceHolders\ResourceSetter_BC {
	protected $controllers = [
		'submit'	=>	array(
			'classname'	=>	'Submitter',
			'methods'	=>	array(
				'mod_name'	=>	array(
					'minArgsLength'	=>	0
				),
				'new_folder'	=>	array(
					'minArgsLength'	=>	0
				),
				'rmv_folder'	=>	array(
					'minArgsLength'	=>	0
				),
				'rmv_content'	=>	array(
					'minArgsLength'	=>	0
				),
				'rmv_img'	=>	array(
					'minArgsLength'	=>	0
				),
				'rmv_txt'	=>	array(
					'minArgsLength'	=>	0
				),
				'rmv_wav'	=>	array(
					'minArgsLength'	=>	0
				),
				'rmv_vod'	=>	array(
					'minArgsLength'	=>	0
				),
				'rmv_doc'	=>	array(
					'minArgsLength'	=>	0
				),
				'move_to_folder'	=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
