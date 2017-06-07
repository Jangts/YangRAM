<?php
namespace Pages\Models\ViewModels\OI;

use Status;
use System\NIDO\DataObject;
use Model;
use AF\ViewRenderers\OIML;
use Library\ect\Pager;
use Pages\Models\Data\Page;



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
			unset($page["query_key"]);
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
			$type_options .= '</select></inputs>';
        }
		$data[] = $type_options;

		if(array_key_exists("title", $page)){
			$title = '<inputs type="text"><label>'.$words["title"].'</label>';
			$title .= '<input type="text" name="title" value="'.$page["title"].'" placeholder=""></inputs>';
			$data[] = $title;
			$description = '<inputs type="textarea"><label>'.$words["description"].'</label>';
			$description .= '<textarea type="text" name="description" placeholder="">'.$page["description"].'</textarea></inputs>';
			$data[] = $description;
		}

		if(array_key_exists("query_key", $page)){
			$query = '<inputs type="multiple"><label>'.$words["query"].'</label>';
			$query .= '<el type="afterlabel">'.$words["query_key"].'</el>';
			$query .= '<input type="text" name="query_key" value="'.$page["query_key"].'" placeholder="">';
			$query .= '<el type="beforeinput">'.$words["word_segmentation"].'</el>';
			$query .= '<input type="text" name="word_segmentation" value="'.$page["word_segmentation"].'" placeholder=""></inputs>';
			$data[] = $query;
		}

		if(array_key_exists("prepage_number", $page)){
			$paging = '<inputs type="multiple"><label>'.$words["paging"].'</label>';
			$paging .= '<el type="afterlabel">'.$words["prepage_number"].'</el>';
			$paging .= '<input type="text" name="prepage_number" value="'.$page["prepage_number"].'" placeholder="">';
			$paging .= '<el type="beforeinput">'.$words["sort_order"].'</el>';
			$paging .= '<input type="text" name="sort_order" value="'.$page["sort_order"].'" placeholder=""></inputs>';
			$data[] = $paging;
		}

		if(array_key_exists("url", $page)){
			$url = '<inputs type="text"><label>'.$words["url"].'</label>';
			$url .= '<input type="text" name="url" value="'.$page["url"].'" placeholder="//www.yangram.net/"></inputs>';
			$url .= '<inputs type="line"></inputs>';
			$data[] = $url;
			if($page["type"]==7){
				$data[] = '<paragraph>'.$words["url_desc"].'</paragraph>';
			}
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
		

		if(array_key_exists("KEY_LIMIT", $page)){
			$limited = '<inputs type="multiple"><label>'.$words["limited"].'</label><select name="KEY_LIMIT">';
			$limited .= '<option value="1" class="level-1" selected>'.$words["limites"][1].'</option>'; 
			$limited .= '<option value="2" class="level-1">'.$words["limites"][2].'</option>'; 
			$limited .= '<option value="3" class="level-1">'.$words["limites"][3].'</option>'; 
			$limited .= '<option value="4" class="level-1">'.$words["limites"][4].'</option>'; 
			$limited .= '<option value="5" class="level-1">'.$words["limites"][5].'</option>'; 
			$limited .= '</select></inputs>';
			$data[] = $limited;
		}

		if(array_key_exists("template", $page)){
			$template = '<inputs type="multiple"><label>'.$words["template"].'</label>';
			$template .= '<input type="text" name="theme" value="'.$page["theme"].'" placeholder="">';
			$template .= '<gap>/</gap>';
			if(array_key_exists("initial_template", $page)){
				$template .= '<input type="text" name="template" class="page-template-short" value="'.$page["template"].'" placeholder="">';
				$template .= '<gap>|</gap>';
				$template .= '<input type="text" name="initial_template" class="page-template-short" value="'.$page["initial_template"].'" placeholder="">';
			}else{
				$template .= '<input type="text" name="template" class="page-template" value="'.$page["template"].'" placeholder="">';	
			}
			$template .= '<click type="pick" class="pick-button">'.$words["browse"].'</click></inputs>';
			$data[] = $template;
		}

		if(array_key_exists("remark", $page)){
			$remark = '<inputs type="textarea"><label>'.$words["remark"].'</label>';
			$remark .= '<textarea type="text" name="remark" placeholder="">'.$page["remark"].'</textarea></inputs>';
			$data[] = $remark;
		}

		if(array_key_exists("keywords", $page)){
			$keywords = '<inputs type="tags" class="seo">';
			$keywords .= '<input name="keywords" type="text" placeholder="'.$words["keywords"].'" value="'.$page["keywords"].'"></inputs>';
			$data[] = $keywords;
		}
        $this->data = $data;
	}

	public function render(){
		return implode('', $this->data);
    }
}