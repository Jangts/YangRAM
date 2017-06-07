<?php
namespace Studio\Pub\Models;
use System\NIDO\DataObject;
use AF\ViewRenderers\OIML;

class OIInputs extends DataObject {
	private $values;
    
    public function __construct($valtype, $dev_fields, $values, $localdict){
        $this->data = [];
		$this->values = $values;
		if($dev_fields&&count($dev_fields)>0){
			$this->basic($valtype, false, $localdict);
			$this->custom($dev_fields);
		}else{
			$this->basic($valtype, true, $localdict);
		}
		
		$this->editor($valtype, $localdict);
	}

    private function basic($valtype, $isLast, $localdict){
        $data = [];
		switch($valtype){
			case 'msgs':
            $data[] = array(
				'name'			=>	'PUBTIME',
				'alias'			=>	$localdict['Pub Time'],
				'input_type'	=>	'datetime',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	date("Y-m-d H:i:s"),
				'file_type'		=>	NULL,
			);
			break;
			case 'arti':
            $data[] = array(
				'name'			=>	'AUTHOR',
				'alias'			=>	$localdict['Author'],
				'input_type'	=>	'text',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
			);
            $data[] = array(
				'name'			=>	'SOURCE',
				'alias'			=>	$localdict['From'],
				'input_type'	=>	'text',
				'tips'			=>	'This Site',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
			);
            $data[] = array(
				'name'			=>	'GENRE',
				'alias'			=>	$localdict['Genre'],
				'input_type'	=>	'text',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
			);
            $data[] = array(
				'name'			=>	'TAGS',
				'alias'			=>	$localdict['Tags'],
				'input_type'	=>	'text',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
			);
			break;
			case 'news':
            $data[] = array(
				'name'			=>	'SUBTITLE',
				'alias'			=>	'',
				'input_type'	=>	'subtitle',
				'tips'			=>	$localdict['Sub Title'],
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
			);
            $data[] = array(
				'name'			=>	'AUTHOR',
				'alias'			=>	$localdict['Author'],
				'input_type'	=>	'text',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
			);
            $data[] = array(
				'name'			=>	'SOURCE',
				'alias'			=>	$localdict['From'],
				'input_type'	=>	'text',
				'tips'			=>	'(链接请用“名称=>URL”格式)',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
			);
            $data[] = array(
				'name'			=>	'PUBTIME',
				'alias'			=>	$localdict['Pub Time'],
				'input_type'	=>	'datetime',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	date("Y-m-d H:i:s"),
				'file_type'		=>	NULL,
			);
			break;
			case 'down':
            $data[] = array(
				'name'			=>	'SRC',
				'alias'			=>	$localdict['Source'],
				'input_type'	=>	'uploader',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
			);
            $data[] = array(
				'name'			=>	'DESCRIPTION',
				'alias'			=>	$localdict['Description'],
				'input_type'	=>	'textarea',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
				'is_last'		=>	$isLast
			);
			break;
			case 'play':
            $data[] = array(
				'name'			=>	'THUMB',
				'alias'			=>	$localdict['Thumbnail'],
				'input_type'	=>	'uploader',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	'image',
			);
            $data[] = array(
				'name'			=>	'SRC',
				'alias'			=>	$localdict['Source'],
				'input_type'	=>	'uploader',
				'tips'			=>	'',
				'row_num'		=>	10,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
				'is_last'		=>	$isLast
			);
			$data[] = array(
				'name'			=>	'ARTIST',
				'alias'			=>	$localdict['Author'],
				'input_type'	=>	'text',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
			);
			$data[] = array(
				'name'			=>	'DESCRIPTION',
				'alias'			=>	$localdict['Description'],
				'input_type'	=>	'textarea',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
				'is_last'		=>	$isLast
			);
			break;
			case 'ablm':
            $data[] = array(
				'name'			=>	'THUMB',
				'alias'			=>	$localdict['Thumbnail'],
				'input_type'	=>	'uploader',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	'image',
			);
            $data[] = array(
				'name'			=>	'IMAGES',
				'alias'			=>	$localdict['Images'],
				'input_type'	=>	'images',
				'tips'			=>	'',
				'row_num'		=>	10,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
				'is_last'		=>	$isLast
			);
			$data[] = array(
				'name'			=>	'ARTIST',
				'alias'			=>	$localdict['Author'],
				'input_type'	=>	'text',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
			);
			$data[] = array(
				'name'			=>	'DESCRIPTION',
				'alias'			=>	$localdict['Description'],
				'input_type'	=>	'textarea',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
				'is_last'		=>	$isLast
			);
			break;
			case 'wiki':
            $data[] = array(
				'name'			=>	'IMAGE',
				'alias'			=>	$localdict['Picture'],
				'input_type'	=>	'uploader',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	'image',
			);
			break;
			case 'item':
            $data[] = array(
				'name'			=>	'IMAGE',
				'alias'			=>	$localdict['Picture'],
				'input_type'	=>	'uploader',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	'image',
			);
			break;
			case 'resm':
			$data[] = array(
				'name'			=>	'NAME',
				'alias'			=>	$localdict['Name'],
				'input_type'	=>	'text',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
			);
			$data[] = array(
				'name'			=>	'ALIAS',
				'alias'			=>	$localdict['Alias'],
				'input_type'	=>	'text',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
			);
			$data[] = array(
				'name'			=>	'SEX',
				'alias'			=>	$localdict['Gender'],
				'input_type'	=>	'options',
				'tips'			=>	'',
				'row_num'		=>	0,
				'option_name'   =>  '男,女,保密',
                'option_value'  =>  '0,1,2',
				'default_value'	=>	2,
				'file_type'		=>	NULL,
			);
            $data[] = array(
				'name'			=>	'PHOTO',
				'alias'			=>	$localdict['Photo'],
				'input_type'	=>	'uploader',
				'tips'			=>	'',
				'row_num'		=>	0,
				'default_value'	=>	'',
				'file_type'		=>	'image',
			);
			break;
		};
		$this->data['base_fields'] = $data;
	}
    
	private function custom($dev_fields){
		$data = [];
		if($dev_fields&&count($dev_fields)>0){
			$m = count($dev_fields) - 1;
			foreach($dev_fields as $n=>$field){
				$field = $field->toArray();
				if($n==$m&&
				!isset($this->content["CONTENT"])&&
				!isset($this->content["MEANING"])&&
				!isset($this->content["DETAILS"])&&
				!isset($this->content["RESUME"])){
					$field["is_last"] = true;
				}
				$data[] = $field;
			}
		}
		$this->data['custom_fields'] = $data;
	}

	private function editor($valtype, $localdict){
		switch($valtype){
			case 'msgs':
			case 'arti':
			case 'news':
			$this->data['editor_fields'] = array(array(
				'name'		=>	'CONTENT',
				'alias'		=>	$localdict['Content'],
				'input_type'	=>	'editor',
				'tips'		=>	'',
				'row_num'	=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
				'is_last'		=>	true
			));
			break;
			case 'wiki':
			$this->data['editor_fields'] = array(array(
				'name'		=>	'MEANING',
				'alias'		=>	$localdict['Meanings'],
				'input_type'	=>	'editor',
				'tips'		=>	'',
				'row_num'	=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
				'is_last'		=>	true
			));
			break;
			case 'item':
			$this->data['editor_fields'] = array(array(
				'name'		=>	'DETAILS',
				'alias'		=>	$localdict['Details'],
				'input_type'	=>	'editor',
				'tips'		=>	'',
				'row_num'	=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
				'is_last'		=>	true
			));
			break;
			case 'resm':
			$this->data['editor_fields'] = array(array(
				'name'		=>	'RESUME',
				'alias'		=>	$localdict['Characters'],
				'input_type'	=>	'editor',
				'tips'		=>	'',
				'row_num'	=>	0,
				'default_value'	=>	'',
				'file_type'		=>	NULL,
				'is_last'		=>	true
			));
			break;
		}
		return '';
	}
	
    public function render(){
		$form = '';
		$values = $this->values;
		foreach($this->data as $dev_fields){
			foreach($dev_fields as $field){
				$form .= OIML::inputs($field, $values);
			}
		}
        return $form;
    }
}