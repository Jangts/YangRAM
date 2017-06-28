<?php
namespace CMS\Controllers;

use Status;
use Response;
use Tangram\NIDO\DataObject;
use Model;
use Controller;
use AF\Models\App as NAM;
use CMF\Models\GEC;
use CMF\Models\SPC\Preset;
use CMF\Models\SPC\Category;
use CMF\Models\SPCLite;
use CMF\Models\SPC;
/**
 *
 */
class SPContents extends Controller {
	private static $sorts = [
		SPCLite::ID_DESC,
		SPCLite::ID_ASC,
		SPCLite::PUBTIME_DESC,
		SPCLite::PUBTIME_ASC,
		SPCLite::MTIME_DESC,
		SPCLite::MTIME_ASC,
		SPCLite::HIT_DESC,
		SPCLite::HIT_ASC,
		SPCLite::RANK_DESC,
		SPCLite::RANK_ASC,
		SPCLite::LEVEL_DESC,
		SPCLite::LEVEL_ASC,
		SPCLite::TITLE_DESC,
		SPCLite::TITLE_ASC,
		SPCLite::TITLE_DESC_GBK,
		SPCLite::TITLE_ASC_GBK
	];

	private static function getXmlArray($data){
		$array = [];
		foreach($data as $key=>$val){
			if(is_numeric($key)){
				if(is_array($val)){
					$array[] = array(
						'tag' 	=>	'item',
						'value'	=>	self::getXmlArray($val)
					);
				}else{
					$array[] = array(
						'tag' 	=>	'item',
						'value'	=>	$val
					);
				}
			}else{
				$array[] = array(
					'tag' 	=>	$key,
					'value'	=>	$val
				);
			}
		}
		return $array;
	}

	private function render($data, $code = 200){
		$args = $this->request->PARAMS;
		switch($args->returntype){
			case 'xml':
			$response = Response::instance($code, Response::XML);
			$array = array(
				'tag'	=>	'result',
				'value'	=>	self::getXmlArray($data)
			);
			$xml = DataObject::arrayToXml($array);
			//var_dump($array, $xml);
			//die;
			$response->send($xml);
			break;
			case 'serialize':
			$response = Response::instance($code, Response::TXT);
			break;
			default:
			$response = Response::instance($code, Response::JSON);
			$json = DataObject::enclose($data)->toJson();
			$response->send($json);
		}
	}

	public function content($id){
		
	}

	public function preset($id){
		
	}

	public function cat($id){
		
	}

	public function tag($id){
		
	}

	public function get_list_by_preset($preset, $order_code = 2, $start = 0, $length = 0){
		if(is_numeric($preset)){
			$presetinfo = Preset::id($preset);
			$preset_alias = $presetinfo->alias;
		}elseif(is_string($preset)){
			$preset_alias = $preset;
		}else{
			return null;
		}
		$order = isset(self::$sorts[$order_code]) ? self::$sorts[$order_code] : self::$sorts[2];
		$list = SPC::getList($preset_alias, NULL, SPCLite::PUBLISHED, $order, $start, $length, Model::LIST_AS_ARR);
		$this->render($list);
	}

	public function get_list_by_cat($catid, $order_code = 2, $start = 0, $length = 0){
		if(is_numeric($catid)){
			$order = isset(self::$sorts[$order_code]) ? self::$sorts[$order_code] : self::$sorts[2];
			$list = SPC::getList(NULL, $catid, SPCLite::PUBLISHED, $order, $start, $length, Model::LIST_AS_ARR);
			$this->render($list);
		}else{
			return null;
		}
	}

	public function get_list_by_tag($tag, $order_code = 2, $start = 0, $length = 0){
		
	}

	public function get_presets($start = 0, $length = 0){
		
	}

	public function get_presets_by_basic_type($id){
		
	}

	public function get_cats($preset){
		
	}

	public function get_tags($preset){
	}

	public function get_content_view($id){
		$this->render(SPCLite::viewCount($id));
	}

	
	public static function returnContentsInfo($__REQUEST){
		if($__REQUEST[4]=='presets'){
			return self::format(Contents::getPresets(true));
		}
		if(empty($__REQUEST[5])){
			SYS::$writer->returnStatus(404);
		}
		if(preg_match("/cat_(\d+)/",$__REQUEST[4], $matches)){
			if($__REQUEST[5]=='info'){
				return self::format(Contents::getCategoryInfoById($matches[1]));
			}
			SYS::$writer->returnStatus(404);
		}
		if(preg_match("/[a-z]+/", $__REQUEST[4])){
			$presetinfo = Contents::getPresetInfoByAlias($__REQUEST[4]);
		}elseif(preg_match("/\d+/",$__REQUEST[4])){
			$presetinfo = Contents::getPresetInfoById($__REQUEST[4]);
		}else{
			SYS::$writer->returnStatus(404);
		}
		if(empty($presetinfo)){
			SYS::$writer->returnStatus(404);
		}
		if($__REQUEST[5]=='info'){
			self::format($presetinfo);	
		}
		if($__REQUEST[5]=='list'){
			return self::getContentsByPresetAlias($presetinfo["alias"], $__REQUEST);
		}
		if($__REQUEST[5]=='cat_list'){
			return self::getContentsByCategory($presetinfo["alias"], $__REQUEST);
		}
		if($__REQUEST[5]=='tag_list'){
			return self::getContentsByTag($presetinfo["alias"], $__REQUEST);
		}
		if($__REQUEST[5]=='cats'){
			return self::format(Contents::getCategoriesByPresetID($presetinfo["id"]));
		}
		if($__REQUEST[5]=='tags'){
			return self::format(Contents::getTagsByPresetAlias($presetinfo["alias"]));
		}
		SYS::$writer->returnStatus(404);
	}
	
	private static function getSort($__REQUEST, $index = 6){
		if(empty($__REQUEST[$index])){
			return 7;
		}else{
			switch($__REQUEST[$index]){
				case 't_pos':
				return 4;
				break;
				case 't_inv':
				return 3;
				break;
				case 'v_pos':
				return 8;
				break;
				case 'v_inv':
				return 7;
				break;
			}
		}
	}
	
	private static function getContentsByCategory($alias, $__REQUEST){
		$cat_id = $__REQUEST[6];
		$order = self::getSort($__REQUEST, 7);
		if(isset($__REQUEST[8])){
			$excerpts = explode('-', $__REQUEST[7]);
			if(isset($excerpts[1])){
				$start = is_numeric($excerpts[0]) ? $excerpts[0]: 0;
				$num = (is_numeric($excerpts[1]) &&  $excerpts[1] > $excerpts[0] ) ? $excerpts[1] - $excerpts[0] : 0;
			}else{
				$start = 0;
				$num = is_numeric($excerpts[0]) ? $excerpts[0] : 0;
			}
		}else{
			$start = 0;
			$num = 0;
		}
		if(count($_GET)){
			self::format(Contents::getContents($alias, $cat_id, 2, $order, $start, $num, $_GET));
		}else{
			self::format(Contents::getList($alias, $cat_id, $order, $start, $num));
		}
	}
	
	private static function getContentsByTag($alias, $__REQUEST){
		if(isset($_GET["tag"])){
			$tag = $__REQUEST["tag"];
			$order = self::getSort($__REQUEST);
			self::format(Contents::getContentsByTag($alias, $order, $tag));
		}else{
			self::format([]);
		}
	}
	
	private static function getContentsByPresetAlias($alias, $__REQUEST){
		$order = self::getSort($__REQUEST);
		if(isset($__REQUEST[7])){
			$excerpts = explode('-', $__REQUEST[7]);
			if(isset($excerpts[1])){
				$start = is_numeric($excerpts[0]) ? $excerpts[0]: 0;
				$num = (is_numeric($excerpts[1]) &&  $excerpts[1] > $excerpts[0] ) ? $excerpts[1] - $excerpts[0] : 0;
			}else{
				$start = 0;
				$num = is_numeric($excerpts[0]) ? $excerpts[0] : 0;
			}
		}else{
			$start = 0;
			$num = 0;
		}
		if(count($_GET)){
			self::format(Contents::getContents($alias, -1, 2, $order, $start, $num, $_GET));
		}else{
			self::format(Contents::getList($alias, -1, $order, $start, $num));
		}
	}
}
