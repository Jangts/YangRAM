<?php
namespace OIF\models\traits;

trait inputs {
	/**
 	 *  OIFORM争对28种SPC字段类型的渲染方式，自定义类型统一使用HTML原生input[type=textarea]标签
	 *
 	 *  **  ip          直接使用HTML原生input[type=text]标签，不使用特别控件，但是使用YangRAM.API.form进行填值检查
 	 *  **  is          使用YangRAM.API.form.Radio组件渲染
 	 *  **  int         直接使用HTML原生input[type=number]标签
 	 *  **  url         直接使用HTML原生input[type=text]标签，不使用特别控件，但是使用YangRAM.API.form进行填值检查
 	 *  **  text        直接使用HTML原生input[type=text]标签
 	 *  **  tags        使用YangRAM.API.form.Tags组件渲染
 	 *  **  date        使用System.TimePiker组件渲染，渲染数据类型为fulldate
 	 *  **  file        直接使用HTML原生input[type=text]标签
 	 *  **  rate        使用System.SevenStarsRater/System.FifteenStarsRater/System.ThirtyStarsRater等组件渲染
 	 *  **  time        使用System.TimePiker组件渲染，渲染数据类型为timeofday
 	 *  **  week        使用System.TimePiker组件渲染，渲染数据类型为dayofweek
 	 *  **  color       使用System.ColorPiker组件渲染
 	 *  **  email       直接使用HTML原生input[type=text]标签，不使用特别控件，但是使用YangRAM.API.form进行填值检查
 	 *  **  files       直接使用HTML原生input[type=textarea]标签
 	 *  **  month       使用System.TimePiker组件渲染，渲染数据类型为numbermonth
 	 *  **  radio       直接使用HTML原生input[type=radio]标签
 	 *  **  stamp       使用System.TimePiker组件渲染，渲染数据类型为timestamp
 	 *  **  editor      使用YangRAM.API.form.Editor组件渲染
 	 *  **  hidden      直接使用HTML原生input[type=hidden]标签
 	 *  **  number      直接使用HTML原生input[type=number]标签
 	 *  **  imgtext     使用System.Uploader组件渲染
 	 *  **  options     使用System.OptionSelector组件渲染
 	 *  **  percent     使用System.Percentager组件渲染
 	 *  **  checkbox    直接使用HTML原生input[type=checkbox]标签
 	 *  **  datetime    使用System.TimePiker组件渲染，渲染数据类型为datetime
 	 *  **  longtext    直接使用HTML原生input[type=textarea]标签
 	 *  **  textarea    直接使用HTML原生input[type=textarea]标签
 	 *  **  uploader    使用System.Uploader组件渲染
	 *
 	 */
	public static function inputs($field, $value){
        $html = '';
		if(empty($field['is_last'])){
			$line = '';
        }else{
			$line = '<line></line>';
        }
        switch($field["input_type"]){
            case 'primer': case 'subtitle':
                $html .= '<inputs type="'.$field["input_type"].'">';
                $html .= self::insertInput('text', $field["name"], $value, $field["tips"], false, false, $field["input_type"]);
                $html .= '</inputs>'.$line;
                break;
            case 'hide':
                $html .= '<inputs type="hidden">';
                $html .= self::insertInput('text', $field["name"], $value, false, true);
                $html .= '</inputs>';
                break;
			/* ** *** **** ***** ****** *******/
            case 'text':  case 'video': case 'int': case 'number':
                $html .= '<inputs type="text">';
                $html .= self::insertLabel($field["alias"]);
                $html .= self::insertInput('text', $field["name"], $value, $field["tips"], false, false, $field["input_type"]);
                $html .= '</inputs>'.$line;
                break;
            case 'file': case 'uploader':
                $html .= '<inputs type="text" inpreview="outpreview">';
                $html .= self::insertLabel($field["alias"]);
                $html .= self::insertInput('text', $field["name"], $value, $field["tips"]);
				if($field["file_type"]=='image'){
					$html .= '<image-vision name="'.$field["name"].'" ></image-vision>';
				}
                $html .= '<click type="pick" picker="uploader" name="'.$field["name"].'" filetype="'.$field["file_type"].'">Upload</click></inputs>'.$line;
                break;
			/* ** *** **** ***** ****** *******/
            case 'time':
			case 'date':
			case 'datetime':
			case 'week':
			case 'month':
                $html .= '<inputs type="text">';
                $html .= self::insertLabel($field["alias"]);
                $html .= self::insertInput('text', $field["name"], $value, $field["tips"], false, false, $field["input_type"]);
                $html .= '<click type="pick" picker="timepicker" pick-data-type="'.self::datatypeOfTimePiker($field["input_type"]).'" name="'.$field["name"].'">Pick</click></inputs>'.$line;
                break;
            /* ** *** **** ***** ****** *******/
            case 'is':
                $html .= '<inputs type="multiple">';
                $html .= self::insertLabel($field["alias"]);
                $html .= self::insertRadios($field["name"], $value, '1,0', $field["option_name"]);
                $html .= '</inputs>'.$line;
                break;
            case 'radio':
                $html .= '<inputs type="multiple">';
                $html .= self::insertLabel($field["alias"]);
                $html .= self::insertRadios($field["name"], $value, $field["option_value"], $field["option_name"]);
                $html .= '</inputs>'.$line;
                break;
            case 'checkbox':
                $html .= '<inputs type="multiple">';
                $html .= self::insertLabel($field["alias"]);
                $html .= self::insertCheckboxes($field["name"], $value, $field["option_value"], $field["option_name"]);
                $html .= '</inputs>'.$line;
                break;
            case 'options':
                $html .= '<inputs type="multiple">';
                $html .= self::insertLabel($field["alias"]);
                $html .= self::insertSelect($field["name"], $value, $field["option_value"], $field["option_name"]);
                $html .= '</inputs>'.$line;
                break;

            /* ** *** **** ***** ****** *******/
            case 'textarea': case 'images':
                $html .= '<inputs type="textarea">';
                $html .= self::insertLabel($field["alias"]);
                $html .= self::insertTextarea($field["name"], $value, $field["tips"], $field["row_num"]);
                $html .= '</inputs>';
                break;
            case 'longtext':
                $html .= '<inputs type="longtext">';
                $html .= self::insertLabel($field["alias"]);
                $html .= self::insertTextarea($field["name"], $value, $field["tips"], $field["row_num"]);
                $html .= '</inputs>';
                break;
            case 'editor':
                $html .= '<inputs type="editor">';
                $html .= self::insertLabel($field["alias"]);
                $html .= self::insertTextarea($field["name"], $value, $field["tips"], $field["row_num"]);
                $html .= '</inputs>';
                break;
		}
		return $html;
	}

    private static function datatypeOfTimePiker($input_type){
		switch($input_type){
			case 'time':
			return 'timeofday';

			case 'date':
			return 'fulldate';

			case 'week':
			return 'dayofweek';

			case 'month':
			return 'numbermonth';
		}
		return 'datetime';
	}

	private static function insertLabel($name){
		if($name&&$name!=''){
			return '<label>'.$name.'</label>';
		}
		return '';
	}
	
	private static function insertInput($type, $key, $val, $placeholde = false, $hide = false, $check = false, $valtype = 'text'){
		$html = '<input type="'.$type.'" name="'.$key.'" value="'.$val.'"';
		if($placeholde&&$placeholde!=''){
			$html .= ' placeholder="'.$placeholde.'"';
		}
		if($hide){
			$html .= ' hidden="hidden"';
		}
		if($check){
			$html .= ' checked="checked"';
		}
		if($valtype=='int'){
			$html .= ' data-ib-validate="integer"';
		}
		$html .= ' />';
		return $html;
	}
	
	private static function insertRadios($key, $val, $vals, $names){
		$valArray = preg_split('/,\s*/', $vals);
		$nameArray = preg_split('/,\s*/', $names);
		$length = count($valArray) < count($nameArray) ? count($valArray) : count($nameArray);
		$html = '';
		for($i=0;$i<$length;$i++){
			if($valArray[$i]==$val){
				$html .= self::insertInput('radio', $key, $valArray[$i], false, false, true);
			}else{
				$html .= self::insertInput('radio', $key, $valArray[$i]);
			}
			$html .= '<el type="afterinput">'.$nameArray[$i].'</el>';
		}
		return $html;
	}
	
	private static function insertCheckboxes($key, $val, $vals, $names){
		$valArray = preg_split('/,\s*/', $vals);
		$nameArray = preg_split('/,\s*/', $names);
		$length = count($valArray) < count($nameArray) ? count($valArray) : count($nameArray);
		$html = '';
		for($i=0;$i<$length;$i++){
			if($valArray[$i]==$val){
				$html .= self::insertInput('checkbox', $key, $valArray[$i], false, false, true);
			}else{
				$html .= self::insertInput('checkbox', $key, $valArray[$i]);
			}
			$html .= '<el type="afterinput">'.$nameArray[$i].'</el>';
		}
		return $html;
	}
	
	private static function insertSelect($key, $val, $vals, $names){
		$valArray = preg_split('/,\s*/', $vals);
		$nameArray = preg_split('/,\s*/', $names);
		$length = count($valArray) < count($nameArray) ? count($valArray) : count($nameArray);
		$html = '<select name="'.$key.'">';
		for($i=0;$i<$length;$i++){
			$html .= self::insertLabel($nameArray[$i]);
			if($valArray[$i]==$val){
				$html .= '<option value="'.$valArray[$i].'" selected="selected">'.$nameArray[$i].'</option>';
			}else{
				$html .= '<option value="'.$valArray[$i].'">'.$nameArray[$i].'</option>';
			}
		}
		$html .= '</select>';
		return $html;
	}
	
	private static function insertTextarea($key, $val, $placeholde = false, $rownum = 0, $hide = false){
		$height = 30 * $rownum;
		$html = '<textarea name="'.$key.'"';
		if($placeholde&&$placeholde!=''){
			$html .= ' placeholder="'.$placeholde.'"';
		}
		if($hide){
			$html .= ' hidden=""';
		}
		if($height>0){
			$html .= ' style="height: '.$height.'px;"';
		}
		$html .= '>'.$val.'</textarea>';
		return $html;
	}
	
	private static function insertFont($note){
		if($note&&$note!=''){
			return '<font>('.$note.')</font>';
		}
		return '';
	}
}