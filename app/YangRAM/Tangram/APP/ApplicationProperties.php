<?php
namespace Tangram\APP;

use Tangram\IDEA;

include 'NI_ApplicationProperties_BC.php';

/**
 *	Application Configuration Options Model
 *	应用配置选项模型
 */
final class ApplicationProperties extends NI_ApplicationProperties_BC {
	protected function loadResourceHolders(array $rhmanifest){
		if(isset($rhmanifest['ContentProvider'])){
			$this->regResHolder('ContentProvider', $rhmanifest['ContentProvider']);
		}else{
			$this->regResHolder('ContentProvider', [
				'classname'	=>	'ResHolders\ContentProvider',
				'filename'	=>	'ResHolders/ContentProvider'
			]);
		}
		if(isset($rhmanifest['OISourceTransfer'])){
			$this->regResHolder('OISourceTransfer', $rhmanifest['OISourceTransfer']);
		}else{
			$this->regResHolder('OISourceTransfer', [
				'classname'	=>	'ResHolders\OISourceTransfer',
				'filename'	=>	'ResHolders/OISourceTransfer'
			]);
		}
		empty($rhmanifest['ResourceTransfer']) or $this->regResHolder('ResourceTransfer', $rhmanifest['ResourceTransfer']);
		empty($rhmanifest['ResourceBrowser']) or $this->regResHolder('ResourceBrowser', $rhmanifest['ResourceBrowser']);
		empty($rhmanifest['ResourceSetter']) or $this->regResHolder('ResourceSetter', $rhmanifest['ResourceSetter']);
		empty($rhmanifest['IPCommunicator']) or $this->regResHolder('IPCommunicator', $rhmanifest['IPCommunicator']);
		empty($rhmanifest['UnitTester']) or $this->regResHolder('UnitTester', $rhmanifest['UnitTester']);
	}
}
