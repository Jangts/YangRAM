<?php
namespace Tangram\CTRL;

use Tangram\IDEA;
use Status;
use Tangram\NIDO\DataObject;

/**
 *	Application Configuration Options Model
 *	应用配置选项模型
 */
abstract class NI_ApplicationProperties_BC extends DataObject {
	protected
	$appinfoid = -1,
	$install_path = '',
	$option_filename = '';

	protected $data = [
		'Suitspace'		=>	'',
		'Namespace'		=>	'',
		'Code'			=>	'',
		'Name'			=>	'',
		'Version'		=>	IDEA::VERSION,
		'KeyWords'		=>	'',
		'Description'	=>	'',
		'Homepage'		=>	'',
		'Issues'		=>	'',
		'AuthorInfs'	=> [
			'AuthorName'	=>	'',
			'Developers'	=>	'',
			'Homepage'		=>	'',
			'Issues'		=>	''
		],
		'ResHolders'	=>	[],
		'Icons'			=>	[
			'font'		=>	NULL,
			'logo'		=>	[
				'80'		=> "icon.png"
			]
		],
		'Requires'		=>	[],
		'Dependents'	=>	[],
		'Permissions'	=>	[]
	];

	final public function __construct(array $appinfo){
		if(isset($appinfo['APPID'])){
			$this->appid = $appinfo['APPID'];
			$this->install_path = ROOT.$appinfo['DIR'];
			$this->option_filename = ROOT.$appinfo['DIR'].'AppProperties.json';
			if($realpath = realpath($this->option_filename)){
				$this->extendsProperties($appinfo);
			}else{
				new Status('708.0', 'Application '.$appinfo['Name'].' Initialization Failure', 'Cannot Find Options File [' . $this->option_filename . ']', true, true);
			}
		}else{
			new Status(709.1, 'Parameter Error!', true);
		}
	}

	final private function extendsProperties(array $appinfo){
		$this->data['Code'] = $appinfo['Code'];
		$string = file_get_contents($this->option_filename);
		$props = json_decode($string, true);
		if($props){
			if(empty($props['namespace'])){
				new Status('709.0', 'Application '.$appinfo['Name'].' Initialization Failure', 'Must define A Namespace For Application.', true);
			}
			if(isset($props['suitspace'])){
				$this->data['Suitspace'] = $props['suitspace'];
				$this->data['Namespace'] = $props['suitspace'].'\\'.$props['namespace'];
			}else{
				$this->data['Namespace'] = $props['namespace'];
			}
			if(isset($props['metadata'])){
				$this->loadMetaData($props['metadata']);
			}
			if(isset($props['metadata'])){
				$this->loadAuthorInfo($props['metadata']);
			}
			if(isset($props['manifest'])){
				$this->loadResourceHolders($props['manifest']);
			}
			if(isset($props['icons'])){
				$this->loadIcons($props['icons']);
				
			}
			if(isset($props['requires'])){
				$this->loadRequires($props['requires']);
			}
			if(isset($props['dependents'])){
				$this->loadDependents($props['dependents']);
			}
		}else{
			new Status('709.0', 'Application '.$appinfo['Name'].' Initialization Failure', 'Please Check You Options.json File.', true);
		}
	}

	protected function loadMetaData(array $metadata){
		empty($metadata['name']) or $this->data['Name'] = $metadata['name'];
		empty($metadata['version']) or $this->data['Version'] = $metadata['version'];
		if(isset($metadata['keywords'])&&is_array($metadata['keywords'])){
			$this->data['KeyWords'] = join(', ', $metadata['keywords']);
		}
		empty($metadata['description']) or $this->data['Description'] = $metadata['description'];
        empty($metadata['homepage']) or $this->data['Homepage'] = $metadata['homepage'];
        empty($metadata['issues']) or $this->data['Issues'] = $metadata['issues'];
	}

	protected function loadAuthorInfo(array $authorinfs){
		empty($authorinfs['author_name']) or $this->data['AuthorInfs']['AuthorName'] = $authorinfs['author_name'];
		empty($authorinfs['developers']) or $this->data['AuthorInfs']['Developers'] = $authorinfs['developers'];
		empty($authorinfs['homepage']) or $this->data['AuthorInfs']['Homepage'] = $authorinfs['homepage'];
		empty($authorinfs['issues']) or $this->data['AuthorInfs']['Issues'] = $authorinfs['issues'];
	}

	protected function loadResourceHolders(array $rhmanifest){
		if(isset($rhmanifest['ContentProvider'])){
			$this->regResHolder('ContentProvider', $rhmanifest['ContentProvider']);
		}else{
			$this->regResHolder('ContentProvider', [
				'classname'	=>	'ResHolders\ContentProvider',
				'filename'	=>	'ResHolders/ContentProvider'
			]);
		}
		empty($rhmanifest['ResourceTransfer']) or $this->regResHolder('ResourceTransfer', $rhmanifest['ResourceTransfer']);
		empty($rhmanifest['ResourceBrowser']) or $this->regResHolder('ResourceBrowser', $rhmanifest['ResourceBrowser']);
		empty($rhmanifest['ResourceSetter']) or $this->regResHolder('ResourceSetter', $rhmanifest['ResourceSetter']);
		empty($rhmanifest['IPCommunicator']) or $this->regResHolder('IPCommunicator', $rhmanifest['IPCommunicator']);
		empty($rhmanifest['UnitTester']) or $this->regResHolder('UnitTester', $rhmanifest['UnitTester']);
	}

	protected function regResHolder($type, $names){
		$this->data['ResHolders'][$type] = [
			'classname'	=>	$names['classname'],
            'filename'	=>	$names['filename'].'.php'
		];
	}

	protected function loadIcons($icons){
		if(isset($icons['logo'])){
			if(is_array($icons['logo'])){
				foreach($icons['logo'] as $size => $image){
					$this->data['Icons']['logo'][$size] = $image;
				}
			}elseif(is_string($icons['logo'])){
				$this->data['Icons']['logo']['80'] = $icons['logo'];
			}
		}
		if(isset($icons['ect.'])&&is_array($icons['logo'])){
			foreach($icons['ect.'] as $alisa => $image){
				$this->data['Icons'][$alisa] = $image;
			}
		}
	}

	final private function loadRequires(array $requires){
		$array = [];
		if(isset($requires['nik'])){
			foreach ($requires['nik'] as $value) {
				$array[] = PATH_TNI.$value;
			}
		}
		if(isset($requires['lib'])){
			foreach ($requires['lib'] as $value) {
				$array[] = PATH_LIB.$value;
			}
		}
		if(isset($requires['xtp'])){
			foreach ($requires['lib'] as $value) {
				$array[] = PATH_LIB.$value;
			}
		}
		if(isset($requires['stl'])){
			$path = $this->suit_path;
			foreach ($requires['stl'] as $value) {
				$array[] = $path.$value;
			}
		}
		if(isset($requires['app'])){
			$path = $this->install_path;
			foreach ($requires['app'] as $value) {
				$array[] = $path.$value;
			}
		}
		$this->data['Requires'] = $array;
	}

    final private function loadDependents(array $dependents){
		foreach ($dependents as $key=>$value) {
			$this->data['Dependents'][$key] = $value;
		}
	}
}
