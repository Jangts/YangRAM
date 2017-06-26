<?php
namespace GPS\Models\ViewModels\OI;

use Status;
use Tangram\NIDO\DataObject;
use Model;
use AF\Util\OIML;
use Library\ect\Pager;
use GPS\Models\Data\Page;



class Form extends DataObject {
	private $k;

    public function __construct($pid, $localdict, $length, $params){
		$this->data = [];
		if($pid=='0'){
            $this->buildForm(new Page, $localdict);
        }else{
            if($page = Page::byId($pid)){
                $this->buildForm($page, $localdict);
            }else{
			    DIE('PAGE NOT FOUND');
		    }
        }
	}

    private function buildForm($page, $localdict){
		$words = $localdict->labels;

		$pagename = '<inputs type="text"><label>'.$words["name"].'</label>';
		$pagename .= '<input type="text" name="name" value="'.$page->name.'" placeholder=""></inputs>';
		$data = [$pagename];

        if($page->type){
            $page = $page->getValidFields();
            $type_options = '<inputs type="multiple"><label>'.$words["type"].'</label><select name="type" disabled>';
			$type_options .= '<option value="'.$page["type"].'" class="level-1" selected>'.$words["types"][$page["type"]].'</option>'; 
			$type_options .= '</select></inputs>';
        }else{
            $page = $page->toArray();
			unset($page["gec_default_alias"]);
			unset($page["url"]);
			unset($page["use_context"]);
			unset($page["initial_template"]);
            $type_options = '<inputs type="multiple"><label>'.$words["type"].'</label><select name="type">';
			$type_options .= '<option value="1" class="level-1" selected>'.$words["types"][1].'</option>'; 
			$type_options .= '<option value="2" class="level-1">'.$words["types"][2].'</option>'; 
			$type_options .= '<option value="3" class="level-1">'.$words["types"][3].'</option>'; 
			$type_options .= '<option value="4" class="level-1">'.$words["types"][4].'</option>'; 
			$type_options .= '<option value="5" class="level-1">'.$words["types"][5].'</option>'; 
            $type_options .= '<option value="6" class="level-1">'.$words["types"][6].'</option>'; 
			$type_options .= '<option value="7" class="level-1">'.$words["types"][7].'</option>';
			$type_options .= '<option value="8" class="level-1">'.$words["types"][8].'</option>';
			$type_options .= '</select></inputs>';
        }
		$data[] = $type_options;

		
		if(array_key_exists("gec_default_alias", $page)){
			$mark = '<inputs type="multiple"><label>'.$words["mark"].'</label>';
            $mark .= '<el type="afterlabel">'.$words["mark"].'</el>';
			$mark .= '<input type="text" name="mark" value="'.$page["mark"].'" placeholder="">';
			$mark .= '<el type="beforeinput">'.$words["default"].'</el>';
			$mark .= '<input type="text" name="gec_default_alias" value="'.$page["gec_default_alias"].'" placeholder=""></inputs>';
		}else{
			$mark = '<inputs type="text"><label>'.$words["mark"].'</label>';
			$mark .= '<input type="text" name="mark" value="'.$page["mark"].'" placeholder=""></inputs>';
		}
		$data[] = $mark;

		$title = '<inputs type="text"><label>'.$words["title"].'</label>';
		$title .= '<input type="text" name="title" value="'.$page["title"].'" placeholder=""></inputs>';
		$data[] = $title;
		$description = '<inputs type="textarea"><label>'.$words["description"].'</label>';
		$description .= '<textarea type="text" name="description" placeholder="">'.$page["description"].'</textarea></inputs>';
		$data[] = $description;

		if(array_key_exists("prepage_number", $page)){
			$paging = '<inputs type="multiple"><label>'.$words["paging"].'</label>';
			$paging .= '<el type="afterlabel">'.$words["prepage_number"].'</el>';
			$paging .= '<input type="text" name="prepage_number" value="'.$page["prepage_number"].'" placeholder="">';
			$paging .= '<el type="beforeinput">'.$words["sort_order"].'</el>';
			$paging .= '<input type="text" name="sort_order" value="'.$page["sort_order"].'" placeholder=""></inputs>';
			$data[] = $paging;
		}

		if(array_key_exists("use_base64", $page)){
			if($page["use_base64"]==1){
				$checkeds = [1,0];
			}else{
				$checkeds = [0,1];
			}
			$context = '<inputs type="multiple"><label>'.$words["context"].'</label>';
        	$context .= '<input type="radio" name="use_base64" value="1" '.$checkeds[0].'/>';
        	$context .= '<el type="afterinput">是</el>';
        	$context .= '<input type="radio" name="use_base64" value="0" '.$checkeds[1].'/>';
        	$context .= '<el type="afterinput">否</el></inputs>';
			$data[] = $context;
		}

		if(array_key_exists("use_context", $page)){
			if($page["use_context"]==1){
				$checkeds = [1,0];
			}else{
				$checkeds = [0,1];
			}
			$context = '<inputs type="multiple"><label>'.$words["context"].'</label>';
        	$context .= '<input type="radio" name="use_context" value="1" '.$checkeds[0].'/>';
        	$context .= '<el type="afterinput">是</el>';
        	$context .= '<input type="radio" name="use_context" value="0" '.$checkeds[1].'/>';
        	$context .= '<el type="afterinput">否</el></inputs>';
			$data[] = $context;
		}

		$template = '<inputs type="multiple"><label>'.$words["template"].'</label>';
		$template .= '<input type="text" name="theme" value="'.$page["theme"].'" placeholder="">';
		$template .= '<gap>/</gap>';
		$template .= '<input type="text" name="template" class="page-template" value="'.$page["template"].'" placeholder="">';	
		$template .= '<click type="pick" class="pick-button">'.$words["browse"].'</click></inputs>';
		$data[] = $template;

		$remark = '<inputs type="textarea"><label>'.$words["remark"].'</label>';
		$remark .= '<textarea type="text" name="remark" placeholder="">'.$page["remark"].'</textarea></inputs>';
		$data[] = $remark;

		$keywords = '<inputs type="tags" class="seo">';
		$keywords .= '<input name="keywords" type="text" placeholder="'.$words["keywords"].'" value="'.$page["keywords"].'"></inputs>';
		$data[] = $keywords;

        $this->data = $data;
	}

	public function render(){
		return implode('', $this->data);
    }
}