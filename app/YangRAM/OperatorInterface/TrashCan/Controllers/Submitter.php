<?php
namespace TC\Controllers;

use RDO;
use CMF\Models\SRC;
use CMF\Models\SRCLite;
use TC\Models\Data\RecycleRule;

class Submitter extends \OIC\OICtrller_BC {
	const
	Folder = 'CM\SRC\Folder',
	GEC = 'CM\GEC',
	EMC = 'CM\EMC',
	SPC = 'CM\SPC';
	
	private static function recoverSRC($post){
		if(empty($post["id"])){
			if(SRCLite::remove("`ID` in ('".preg_replace('/\s*,\s*/',"','",$post["ids"])."')", false, $post["itemtype"])){
				exit ('<SUCCESS>');
			}
		}else{
			if(SRCLite::remove("`ID` = '".$post["id"]."'", false, $post["itemtype"])){
				exit ('<SUCCESS>');
			}
		}
		exit ('<FAILED>');
	}

	private static function deleteSRC($post){
		if(empty($post["id"])){
			if(SRC::delete("`ID` in ('".preg_replace('/\s*,\s*/',"','",$post["ids"])."')", $post["itemtype"])){
				exit ('<SUCCESS>');
			}
		}else{
			if(SRC::delete("`ID` = '".$post["id"]."'", $post["itemtype"])){
				exit ('<SUCCESS>');
			}
		}
		exit ('<FAILED>');
	}

	private static function recoverItems($classname, $pkname, $post){
		if(empty($post["id"])){
			if($classname::remove("`$pkname` in ('".preg_replace('/\s*,\s*/',"','",$post["ids"])."')", false)){
				exit ('<SUCCESS>');
			}
		}else{
			if($classname::remove("`$pkname` = '".$post["id"]."'", false)){
				exit ('<SUCCESS>');
			}
		}
		exit ('<FAILED>');
	}

	private static function deleteItems($classname, $pkname, $post){
		if(empty($post["id"])){
			if($classname::delete("`$pkname` in ('".preg_replace('/\s*,\s*/',"','",$post["ids"])."')")){
				exit ('<SUCCESS>');
			}
		}else{
			if($classname::delete("`$pkname` = '".$post["id"]."'")){
				exit ('<SUCCESS>');
			}
		}
		exit ('<FAILED>');
	}

	private static function getItemByRules($rule, $post){
		$rdo = new RDO;
		if(empty($post["id"])){
			return $rdo->using(DB_APP.'a'.$rule["handle_appid"].'_'.$rule["database_table"])
			->where($rule["recycled_state_field"], 1)
			->where($rule["index_field"], "('".preg_replace('/\s*,\s*/',"','",$post["ids"])."')", 'IN');
		}else{
			return $rdo->using(DB_APP.'a'.$rule["handle_appid"].'_'.$rule["database_table"])
			->where($rule["recycled_state_field"], 1)
			->where($rule["index_field"], $post["id"]);
		}
	}

	private static function recoverExtendItems($post){
		$rule = RecycleRule::identity($post["itemtype"]);
		$rdo = self::getItemByRules($rule->toArray(), $post);
		$rdos = $rdo->select();
		if($rdos&&$rdos->getCount()){
			if($rdo->update([
				$rule->recycled_state_field =>	0,
				$rule->recycled_time_field => 	DATETIME
			])){
				exit('<SUCCESS>');
			}
		}
		exit ('<FAILED>');
	}
	
	private static function deleteExtendItems($post){
		$rule = RecycleRule::identity($post["itemtype"]);
		$rdo = self::getItemByRules($rule->toArray(), $post);
		$rdos = $rdo->select();
		if($rdos&&$rdos->getCount()){
			if($rdo->delete()){
				exit('<SUCCESS>');	
			}
		}
		exit ('<FAILED>');
	}

	public function recover(){
		$post = $this->request->FORM->toArray();
		if(isset($post["datatype"])&&isset($post["itemtype"])&&(isset($post["id"])||isset($post["ids"]))){
			switch($post["datatype"]){
				case 'lib':
				switch($post["itemtype"]){
					case 'img':
					case 'txt':
					case 'wav':
					case 'vod':
					case 'doc':
					return self::recoverSRC($post);

					case 'fld':
					return self::recoverItems(self::Folder, 'id', $post);

					case 'gec':
					return self::recoverItems(self::GEC, 'ID', $post);

					case 'emc':
					return self::recoverItems(self::EMC, 'id', $post);
				}
				break;

				case 'spc':
				return self::recoverItems(self::SPC, 'ID', $post);

				case 'xtd':
				return self::recoverExtendItems($post);
			}
		}
		exit('<ERROR>');
	}
	
	public function delete(){
		$post = $this->request->FORM->toArray();
		if(isset($post["datatype"])&&isset($post["itemtype"])&&(isset($post["id"])||isset($post["ids"]))){
			switch($post["datatype"]){
				case 'lib':
				switch($post["itemtype"]){
					case 'img':
					case 'txt':
					case 'wav':
					case 'vod':
					case 'doc':
					return self::deleteSRC($post);

					case 'fld':
					return self::deleteItems(self::Folder, 'id', $post);

					case 'gec':
					return self::deleteItems(self::GEC, 'ID', $post);

					case 'emc':
					return self::deleteItems(self::EMC, 'id', $post);
				}
				break;

				case 'spc':
				return self::deleteItems(self::SPC, 'ID', $post);

				case 'xtd':
				return self::deleteExtendItems($post);
			}
		}
		exit('<ERROR>');
	}
}
