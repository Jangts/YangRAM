<?php
namespace CM\SPC;
use AF\Models\BaseR3Model;

/**
 *	Special Use Content Custom Field Model
 *	专用内容自定义字段属性模型
 *	用来创建、修改、删除专用内容自定义字段的模型
 *  分为如下22种字段类型
 *  **  int
 *  **  url
 *  **  tiny
 *  **  text
 *  **  tags
 *  **  date 
 *  **  time 
 *  **  file
 *  **  week
 *  **  year
 *  **  month
 *  **  color
 *  **  files
 *  **  email
 *  **  radio 
 *  **  hidden
 *  **  number
 *  **  editor
 *  **  content
 *  **  options
 *  **  checkbox
 *  **  datetime
 *  其中text是默认字段类型
 */
final class Field extends BaseR3Model  {
	protected static
	$ca_path = PATH_DAT_CNT.'presetfields/',
    $table = DB_SPC.'fields',
    $defaults = [
        'id'				=>	0,
        'name'     		    =>	'dev_figure',
		'alias'				=>	'New Field',     
        'field_type'		=>	'VARCHAR(65536)',
        'input_type'        =>  'text',
        'default_value'     =>  '',
        'file_type'         =>  '',
        'row_num'		    =>	0,
        'option_name'       =>  '',
        'option_value'      =>  '',
        'tips'              =>  '',
        'sort'				=>	9999,
        'set_id'		    =>	0,
        'KEY_STATE'		=>	1		
    ];

    protected function build($data, $posted = false){
        parent::build($data, $posted);
        $this->readonly = true;
    }

    public static function byType($SET_ID){
        if(is_numeric($SET_ID)){
            return parent::query("`set_id` = $SET_ID" , [['sort', false]]);
        }
        if(is_string($SET_ID)){
            if($SET = Preset::alias($SET_ID)){
                return parent::query("`set_id` = $SET->id" , [['sort', false]]);
            }
        }
        return [];
	}
}
