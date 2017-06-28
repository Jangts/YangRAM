<?php
namespace Studio\Pub\Models;

use Tangram\NIDO\DataObject;
use CMF\Models\SPC\Preset;
use CMF\Models\SPC\Field;
use CMF\Models\SPC\Category;
use CMF\Models\SPC;
use CMF\Models\SPC\Defaults;
use AF\Util\OIML;
use Library\ect\DataTree;

class SPCEditPage extends DataObject {    
    public function __construct($preset, $id, $args, $localdict){
        $presetinfo = Preset::alias($preset);
        if($presetinfo){
            $valtype = $presetinfo->basic_type;
            $theme = $presetinfo->theme;
            $template = $presetinfo->template;
            $fields = Field::byType($presetinfo->id);
            $content = $this->content($presetinfo, $id);
            $inputs = new OIInputs($valtype, $fields, $content, $localdict->edit);
            if($valtype=='news'){
                $primer = OIML::inputs(array(
                    'name'			=>	'PRIMER',
                    'alias'			=>	'',
                    'input_type'	=>	'primer',
                    'tips'			=>	$localdict->edit['Primer'],
                    'row_num'		=>	0,
                    'default_value'	=>	'',
                    'file_type'		=>	NULL,
                ), $content);
            }else{
                $primer = '';
            }
            $this->data = array(
                'theme'             =>  $theme,
                'template'          =>  $template,
                'primer'            =>  $primer,
                'content'           =>  $content,
                'titleplaceholder'  =>  $this->titleplaceholder($valtype, $localdict),
                'inputs'            =>  $inputs->render(),
                'cats'              =>  $this->categories($presetinfo->id, $content, $localdict),
                'args'              =>  $args
            );
        }else{
            # unknow preset alias
        }
    }

    private function content($presetinfo, $id){
		$content = [];
		if(is_numeric($id)){
            $content = SPC::byId($id);
            if($content){
                $content->toArray();
                /*
                 * 此处不需要解码
			    if($content){
				    foreach($content as $key=>$val){
					    $content[$key] = htmlspecialchars_decode($val);
				    }
			    }
                */
            }else{
                $content = Defaults::byType($presetinfo->alias)->values(); 
            }
		}elseif($id=='new'){
			$content = Defaults::byType($presetinfo->alias)->values(); 
		}
        return $content;
    }
    private function categories($sid, $content, $localdict){
        $categories = '';
		$resultCategoris = Category::byType($sid);
		$data = [];
		foreach($resultCategoris as $row){
            $data[] = $row->toArray();
		};
		$tree = new DataTree($data, 'id', 'parent', 'level');
		$tree->getAllOrderByRoot();
		$array = $tree->result;
		if(count($array)>0){
			foreach($array as $select){
				$categories .= '<option value="'.$select["id"].'" class="level-'.$select["level"].'"';
				if($content["CAT_ID"]==$select["id"]) {
					$categories .= ' selected';
				}
				$categories .= ' >';
				for ($i=1; $i<$select["level"]; $i++) {
					$categories .= '—';
				}
				$categories .= $select["name"].'</option>';
			}
            return $categories;
		}else{
			return '<option value="0" class="level-1">--No Classification--</option>';
		}
    }
    
    private function titleplaceholder($valtype, $localdict){
        return '在这里输入文章标题';
    }
}