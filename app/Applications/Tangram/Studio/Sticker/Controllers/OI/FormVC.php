<?php
namespace Studio\Stk\Controllers\OI;

use CM\EMC;
use AF\ViewRenderers\OIML;
use Studio\Stk\Models\LocalDict;

class FormVC extends \OIC\BaseOICtrller {

	public function emc($id){
		$localdict = LocalDict::instance();
		$params = $this->request->PARAMS;
		if($id==='new'){
			$content = new EMC();
			$content->type = 0;
		}else{
			$content = EMC::byId($id);
		}
		$args = $this->emcargs($params, $id);
		$oiml = new OIML;
		$oiml->assign('LOCAL', $localdict);
        $oiml->assign('PAGETITLE', $localdict->appname);
		$oiml->assign('LANG', $localdict->code());
		$oiml->assign('TOP', array('home_list'=>'unavailable', 'new_mod'=>''));
		
		if($content){
			$oiml->assign('CONTENT', $this->getForm($content->toArray(), $localdict).$this->getButtons($args));
		}else{
			$oiml->assign('CONTENT', $this->getPage404($localdict));
		}
		$oiml->display('common');
	}

	private function emcargs($params, $id){
        $params = $params->toArray();
		if(isset($params["sort"])){
			$args = $id.', '.$params["sort"];
		}else{
			$args = $id.', nd';
		}
		if(isset($params["page"])){
			$args .= ', '.$params["page"];
		}else{
			$args .= ', 1';
		}
		if(isset($params["type"])){
			$args .= ', '.$params["type"];
		}else{
			$args .= ', 0';
		}
		if(isset($params["group"])){
			$args .= ', '.$params["group"];
		}
        return $args;
    }
	
	private function getForm($content, $localdict){
		$localdict = $localdict->toArray();
		$html = '<form-vision x-cid="'.$content["id"].'"><form>';

		$html .= '<inputs type="text" class="notopline">';
		$html .= '<label>Name</label>';
		$html .= '<input type="text" name="name" maxlength="32" value="'.str_replace('"', '&quot;', $content["name"]).'">';
		$html .= '</inputs>';
		
		$types = array(
			'1'	=>	'',
			'2'	=>	'',
			'3'	=>	'',
			'4'	=>	'',
			'5'	=>	'',
		);
		if(array_key_exists($content["type"], $types)){
			$types[$content["type"]] = 'selected';
			$disabled = 'disabled';
		}else{
			$types[1] = 'selected';
			$disabled = '';
		}
		$html .= '<inputs type="multiple"><label>Type</label><select name="type" '.$disabled.'>';
		foreach($types as $key=>$val){
			$html .= '<option value="'.$key.'" class="level-1" '.$val.'>'.$localdict["types"][$key]["desc"].'</option>'; 
		}
		$html .= '</select></inputs>';
		
		$html .= '<inputs type="text">';
		$html .= '<label>Group</label>';
		$html .= '<input type="text" name="groupname" maxlength="64" value="'.str_replace('"', '&quot;', $content["groupname"]).'" placeholder="">';
		$html .= '</inputs>';
		$html .= '<inputs type="text">';
		$html .= '<label>Label</label>';
		$html .= '<input type="text" name="label" maxlength="16" value="'.str_replace('"', '&quot;', $content["label"]).'" placeholder="">';
		$html .= '</inputs>';
		$html .= '<inputs type="longtext">';
		$html .= '<label>Code</label>';
		$html .= '<textarea name="code">'.$content["code"].'</textarea>';
		$html .= '</inputs>';
		$html .= '</form></form-vision>';
		return $html;
	}
    
    private function getButtons($args){
		$html = '<edit-panel bgcolor="magenta">';
		$html .= '<click href="trigger://ToList" args="'.$args.'" class="left-panel-button">Return To List</click>';
		$html .= '<click href="trigger://ToTop" class="right-totop-button">';
		$html .= '<vision class="arrow"></vision>';
		$html .= '<vision class="stick"></vision>';
		$html .= '</click>';
		$html .= '<click href="trigger://SaveCode" args="'.$args.'" class="right-panel-button odd">Save Label</click>';
		//$html .= '<click href="trigger://PasteCode" class="paste right-btn">Paste Code</click>';
		$html .= '<click href="trigger://CopyCode" class="right-panel-button even">Copy Code</click>';
		$html .= '</edit-panel>';
		return $html;
    }

	private function getPage404($localdict){
		return '<main posi="center" type="msgbox"><br />Embedded Label Content Not Found</main>';
	}
}
