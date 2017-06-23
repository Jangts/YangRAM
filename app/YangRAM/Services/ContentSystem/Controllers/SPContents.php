<?php
namespace CMS\Controllers;
use Status;
use Response;
use Tangram\NIDO\DataObject;
use Model;
use Controller;
use AF\Models\App as NAM;
use CM\GEC;
use CM\SPC\Preset;
use CM\SPC\Category;
use CM\SPCLite;
use CM\SPC;
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

	public function get_content_view(){
		
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











	private
	$pages = NULL,
	$niml = NULL;

	private function error($code){
		$page = Page::byId(404);
		if($page){

		}else{
			$status = new Status(404);
			$status->write('PAGES_RENDERDER_ERROR_'.$code);
			return $status->cast(Status::CAST_PAGE, Status::TEMP_404);
		}
	}

	public function main($pid){
		$this->page = Page::byId($pid);
		if($this->page){
			$this->niml = new Niml();
			switch ($this->page->type) {
				case 1:
				return $this->freePage();
				case 2:
				return $this->GEC();
				case 3:
				return $this->SPCListPage();
				case 4:
				return $this->SPCPage();
				case 5:
				return $this->jumpPage();
			}
		}
		$this->error('NOPAGE');
	}

	private function freePage(){
		$this->page->countit();
		$renderer = new Niml();
		$renderer->assign("__Title", $this->page->title);
		$renderer->assign("__KeyWords", $this->page->keywords);
		$renderer->assign("__Desc", $this->page->description);
		$renderer->using($this->page->theme);

		$column = $this->request->COLUMN;
		$renderer->assign('__Column', $column->__COL_ALIAS);
		$renderer->assign('__Columns', $column->__COL_TREE);
		$renderer->display($this->page->template);
	}

	private function GEC(){
		$page = $this->page;
		$args = $this->request->PARAMS;
		if(isset($args->contentgroup)){
			$group = $args->contentgroup;
		}else{
			$this->error('NOTCCG');
		}
		if(isset($args->alias)){
			$alias = $args->alias;
		}else{
			$alias = NULL;
		}

		//var_dump($group, $alias);
		//die;
		if($content = GEC::byId($group, $alias)){
			$this->page->countit();

			$title = str_replace('&lt;{ct}&gt;', $content->TITLE, $page->title);
			$keywords = str_replace('&lt;{ck}&gt;', $content->KEYWORDS, $page->keywords);
			$description = str_replace('&lt;{cd}&gt;', $content->DESCRIPTION, $page->description);
			$title = str_replace('&lt;{cg}&gt;', $content->GROUPCODE, $title);
			$keywords = str_replace('&lt;{cg}&gt;', $content->GROUPCODE, $keywords);
			$description = str_replace('&lt;{cg}&gt;', $content->GROUPCODE, $description);

			$renderer = new Niml();
			$renderer->assign("__Title", $title);
			$renderer->assign("__KeyWords", $keywords);
			$renderer->assign("__Desc", $description);
			$renderer->assign("Fp_GROUPCODE", $content->GROUPCODE);
			$renderer->assign("Fp_ALIAS", $content->ALIAS);
			$renderer->assign("Fp_BANNER", $content->BANNER);
			$renderer->assign("Fp_KEY_COUNT", $content->KEY_COUNT);
			$renderer->assign("Fp_CONTENT", $content->CONTENT);
			$renderer->assign("Fp_Content", htmlspecialchars_decode($content->CONTENT));
			$renderer->assign("Fp_CUSTOM_I", $content->CUSTOM_I);
			$renderer->assign("Fp_CUSTOM_II", $content->CUSTOM_II);
			$renderer->using($page->theme);

			$column = $this->request->COLUMN;
			$renderer->assign('__Column', $column->__COL_ALIAS);
			$renderer->assign('__Columns', $column->__COL_TREE);
			$renderer->display($page->template);
		}else{
			$this->error('NOGEC');
		}
	}

	private function SPCListPage(){
		$page = $this->page;
		$args = $this->request->PARAMS;
		$preset_alias = $args->preset;
		$category_id = $args->category;


		# 需要使用Page自带的方法计算排序和选取范围
		# 用到的参数为Page的sort_order和prepage_number
		# 可能需要的参数为Parameters的page
		$order = $this->page->orderby();
		$take = $this->page->take($args->page);

		if(is_numeric($category_id)){
			if($category_id){
				$list = SPC::getList($preset_alias, $category_id, SPCLite::PUBLISHED, $order, $take[0], $take[1]);
				//$list = SPCLite::getList($preset_alias, $category_id, SPCLite::PUBLISHED, $order, $take[0], $take[1]);
				$category = Category::identity($category_id);
				$preset = Preset::id($category->set_id);
				$preset_alias = $preset->alias;
				$title = str_replace('&lt;{cat}&gt;', $category->title, $page->title);
				$keywords = str_replace('&lt;{cak}&gt;', $category->keywords, $page->keywords);
				$description = str_replace('&lt;{cad}&gt;', $category->description, $page->description);
			}elseif(is_string($preset_alias)){
				$list = SPC::getList($preset_alias, 0, SPCLite::PUBLISHED, $order, $take[0], $take[1]);
				//$list = SPCLite::unclassified($preset_alias, $order, $take);
				$preset = Preset::alias($preset_alias);
				$category = Category::identity(0);
				$category_id = 0;
				$title = str_replace('&lt;{cat}&gt;', '', $page->title);
				$keywords = str_replace('&lt;{cak}&gt;', '', $page->keywords);
				$description = str_replace('&lt;{cad}&gt;', '', $page->description);
			}
		}elseif(is_string($preset_alias)){
			$list = SPC::getList($preset_alias, NULL, SPCLite::PUBLISHED, $order, $take[0], $take[1]);
			//$list = SPCLite::byUse($preset_alias, $order, $take);
			$preset = Preset::alias($preset_alias);
			$category = NULL;
			$category_id = NULL;
			$title = str_replace('&lt;{cat}&gt;', '', $page->title);
			$keywords = str_replace('&lt;{cak}&gt;', '', $page->keywords);
			$description = str_replace('&lt;{cad}&gt;', '', $page->description);
		}else{
			$this->error('NOARGS');
		}

		//var_dump($order, $take, $list);
		$this->page->countit();
		$title = str_replace('&lt;{cpn}&gt;', $preset->name, $title);
		$keywords = str_replace('&lt;{cpn}&gt;', $preset->name, $keywords);
		$description = str_replace('&lt;{cpn}&gt;', $preset->name, $description);

		$renderer = new Niml();
		$renderer->assign("__Title", $title);
		$renderer->assign("__KeyWords", $keywords);
		$renderer->assign("__Desc", $description);
		$renderer->assign('___PRESET_ALIAS', $preset_alias);
		$renderer->assign('___CATEGORY_ID', $category_id);
		$renderer->assign('___PRESET', $preset);
		$renderer->assign('___CATEGORY', $category);
		$renderer->assign('___LIST', $list);
		$renderer->using($page->theme);

		$column = $this->request->COLUMN;
		$renderer->assign('__Column', $column->__COL_ALIAS);
		$renderer->assign('__Columns', $column->__COL_TREE);
		$renderer->display($page->template);
	}

	private function SPCPage(){
		$page = $this->page;
		$args = $this->request->PARAMS;
		$preset_alias = $args->preset;
		$item_id = $args->item;

		if(($content = SPC::byId($item_id))&&($content->SET_ALIAS==$preset_alias)){
			$this->page->countit();

			$preset = Preset::id($preset_alias);
			$category_id = $content->CAT_ID;
			$category = Category::identity($category_id);
			if($category){
				$cat = $category->title;
				$cak = $category->keywords;
				$cad = $category->description;
			}else{
				$cat = '';
				$cak = '';
				$cad = '';
			}

			$title = str_replace('&lt;{ct}&gt;', $content->TITLE, $page->title);
			$keywords = str_replace('&lt;{ck}&gt;', $content->KEYWORDS, $page->keywords);
			$description = str_replace('&lt;{cd}&gt;', $content->DESCRIPTION, $page->description);
			$title = str_replace('&lt;{cat}&gt;', $cat, $title);
			$keywords = str_replace('&lt;{cak}&gt;', $cak, $keywords);
			$description = str_replace('&lt;{cad}&gt;', $cad, $description);
			$title = str_replace('&lt;{cpn}&gt;', $preset->name, $title);
			$keywords = str_replace('&lt;{cpn}&gt;', $preset->name, $keywords);
			$description = str_replace('&lt;{cpn}&gt;', $preset->name, $description);

			$renderer = new Niml();
			$renderer->assign("__Title", $title);
			$renderer->assign("__KeyWords", $keywords);
			$renderer->assign("__Desc", $description);
			$renderer->assign($content->toArray(), '___');
			if($preset->basic_type="ablm"){
				if(!$array = json_decode(htmlspecialchars_decode($content->IMAGES), true)){
					$array = [];
				}
				$renderer->assign('___IMAGES_TO_ARRAY', $array);
			}
			$renderer->using($page->theme);
			$renderer->assign('___PRESET_ALIAS', $preset_alias);
			$renderer->assign('___CATEGORY_ID', $category_id);
			$renderer->assign('___PRESET', $preset);
			$renderer->assign('___CATEGORY', $category);

			$column = $this->request->COLUMN;
			$renderer->assign('__Column', $column->__COL_ALIAS);
			$renderer->assign('__Columns', $column->__COL_TREE);
			$renderer->display($page->template);
		}else{
			$this->error('NOSPC');
		}
	}

	private function jumpPage(){
		$dir = $this->page->dir;
		$rq = $this->request->PARAMS->rq;
		if(isset($rq)){
			$url = str_replace("&lt;REQUEST&gt;", $rq, $dir);
		}else{
			$url = $dir;
		}
		$response = Response::instance('303');
		$response->setHeaders('Location', $url);
		$response->sendHeaders();
		die;
	}
}
