<?php
namespace Smartian\Controllers;

use Controller;
use Library\ect\SearchEngine;

class Assistant extends Controller {
	private $kw;
	private $appid = 'YANGRAM';
	private $rules = [
		'apps' => [
			'Item' => 'App',
			'Table' => 'ni_reg_apps` AS A LEFT JOIN `ni_reg_sys_appinfos` AS B ON A.`app_id` = B.`app_id',
			'Index' => 'app_id',
			'Title' => 'app_name',
			'Desc' => 'app_description',
			'Remark' => 'launch, {index}',
			'Fields' => 'app_name, app_description, A.app_authorname',
			'Auxiliary' => 'app_is_ondock = 1',
			'Order' => ['app_count', false],
		]
	];
	
	public function search($appid) {
		$appid = strtoupper($appid);
		if(isset($this->request->PARAMS->kw)&&$this->request->PARAMS->kw!=''){
			$this->kw = $this->request->PARAMS->kw;
			if(file_exists(PATH_PUB.'SmartianSearchRules/'.$appid.'.json')){
				if($rules=json_decode(file_get_contents(PATH_PUB.'SmartianSearchRules/'.$appid.'.json'), true)){
					$this->appid = $appid;
					$this->rules = $rules;
				}
			}
			return $this->execute();
		}
		echo '<tips>I\'m sorry, there are something error, please check your input.</tips>';
	}
	
	private function execute($index = NULL) {
			$html = '';
			if($index){
				$rule = $this->rules[$index];
				$array = $this->searchRule($rule, $this->kw);
				if($array){
					$html .= $this->writeHtml($array, $rule, 1, 8);
				}
			}else{
				foreach($this->rules as $rule){
					$array = $this->searchRule($rule, $this->kw);
					if($array){
						$html .= '<aclass>'.$rule["Item"].':</aclass>';
						$html .= $this->writeHtml($array, $rule, 1, 6);
					}
				}
			}
			if($html==''){
				$html = '<tips>I\'m sorry, there is no result about "<kw>'.$this->kw.'</kw>".</tips>';
			}
			echo $html;	
	}
	
	private function searchRule($rule, $kw){
		$search = new SearchEngine($rule);
		$search->search($kw);
		return $search->getMarkedRS(20, 25);
	}
	
	private function writeHtml($array, $rule, $page, $num){
		$n = ($page - 1) * $num;
		$max = $page * $num;
		$html = '<list type="result">';
		for( ; $n < $max && $n < count($array); $n++){
			$html .= $this->render($rule, $array[$n]);
		}
		$html .= '</list>';
		return $html;
	}
	
	private function render($rule, $list){
		$args = str_replace('{index}', $list[$rule["Index"]], $rule["Params"]);
		if($rule["Desc"]==''){
			return '<item appid="'.$this->appid.'" args="'.$args.'">'.$list[$rule["Title"]].'</item>';
		}else{
			return '<item appid="'.$this->appid.'" args="'.$args.'"><el class="tit">'.$list[$rule["Title"]].'</el><desc>'.$list[$rule["Desc"]].'</edesc></item>';
		}
	}
}