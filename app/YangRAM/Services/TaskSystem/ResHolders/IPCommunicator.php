<?php
namespace Workers\ResHolders;
use Status;

class IPCommunicator extends \AF\ResourceHolders\IPCommunicator_BaseClass {
	protected
	$apis = [
		"master" => [
			"classalias"	=>	"master",
			"methodname"	=>	"main",
			"params"		=>	[],
			"argc" 			=>	0
		],
		"worker" => [
			"classalias"	=>	"process",
			"methodname"	=>	"main",
			"params"		=>	[],
			"argc" 			=>	0
		],
		"timer" => [
			"classalias"	=>	"timer",
			"methodname"	=>	"main",
			"params"		=>	[],
			"argc" 			=>	0
		]
	];
}
