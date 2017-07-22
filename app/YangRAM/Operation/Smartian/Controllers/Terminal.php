<?php


class Query {
	private static function authority(){
		if(URI::isUOIRF()&&SYS::$router->REQUEST[2]=='smartian'&&abs(SYS::$user->isOperator()-3)==2){
			return SYS::$user->isOperator();
		}
		return 0;
	}
	
	public static function exec($sql) {
		if(self::authority()){
			if($query = SQL::query($sql)){
				self::table($query);
				exit;
			}
		}
		echo '<tips>Operation failure or lack of authority.</tips>';
	}
	
	private static function table($query){
		if(is_resource($query)&&$row = mysqli_fetch_assoc($query)){
			$row0 = '';
			$row1 = '';
			foreach($row as $key=>$val){
				$row0 = '<el>'.$key.'</el>';
				$row1 = '<el>'.$val.'</el>';
			}
			echo '<list type="table">';
			echo '<item>'.$row0.'</item><item>'.$row1.'</item>';
			while($row = mysqli_fetch_assoc($query)){
				echo '<item>';
				foreach($row as $key=>$val){
					echo '<el>'.$val.'</el>';
				}
				echo '</item>';
			}
			echo '</list>';
		}elseif(is_bool($query)){
			echo '<tips>Operate Successfully!</tips>';
		}
	}
	
	public static function ctrl($cmd, $params) {
		if(self::authority()){
			switch($cmd){
				//基本/通用
				//System
				case 'set_sys_name':
				case 'set_domain':
				case 'set_database_table_prefix':
				case 'set_default_language':
				case 'set_uniform_oi_url':
				case 'set_uniform_oi_language':
				case 'set_uniform_oi_wait_time':
				case 'set_getter_url':
				case 'set_setter_url':
				case 'set_cache_valid_time':
				self::Basic_General($cmd, $params);
				break;
				case 'set_debug_mode_on':
				self::Basic_General('set_debug_mode', 1);
				break;
				case 'set_debug_mode_off':
				self::Basic_General('set_debug_mode', 0);
				break;
				case 'set_image_safe_mode_on':
				self::Basic_General('set_image_safe_mode', 1);
				break;
				case 'set_image_safe_mode_off':
				self::Basic_General('set_image_safe_mode', 0);
				break;
				//Recycling Rules
				case 'create_recycling_rule':
				case 'update_recycling_rule':
				
				case 'delete_recycling_rule':
				
				break;
				
				//Recycling Rules
				case 'update_template_cache':
				NIC::updateTemplateCache();
				exit('<tips>Template Cache have updated. If it does not come into effect, please try again later.</tips>');
				break;
				//内容
				//Preset Content Models
				case 'set_preset_info':
				case 'set_preset_on':
				case 'set_preset_off':
				case 'set_preset_open_application':
				case 'set_preset_preview_template':
				
				break;
				//Preset Content Categories
				case 'new_category':
				case 'set_category':
				case 'del_category':
				
				break;
				//Content Tags
				case 'check_all_tags':
				
				break;
				//语言/地区
				//Locations
				case 'create_location':
				case 'update_location_info':
				
				case 'delete_location_and_its_language':
				
				break;
				//Languages
				case 'create_info_of_lang':
				case 'update_info_by_lang':
				if($array=json_decode($params, true)){
					$params = $array;
				}else{
					die('<tips>Parameters Error;.</tips>');
				}
				case 'delete_info_of_lang':
				self::setInformationByLanguage($cmd, $params);
				break;
				//频道/栏目与路由
				//Channels
				case 'create_channel':
				case 'update_channel_info':
				
				case 'delete_channel_and_its_columns':
				
				break;
				//Columns
				case 'create_column':
				case 'update_column_info':
				
				case 'delete_column':
				
				break;
				//Router Settings
				case 'check_all_path_rules':
				
				break;
				case 'insert_path_rule':
				case 'update_path_rule':
				if($array=json_decode($params, true)){
					$params = $array;
				}else{
					die('<tips>Parameters Error;.</tips>');
				}
				case 'upward_path_rule':
				case 'downward_path_rule':
				case 'delete_path_rule':
				self::setPathRules($cmd, $params);
				break;
				case 'update_path_rules_cache':
				URI::buildPathData();
				exit('<tips>Router Path Rules have updated. If it does not come into effect, please try again later.</tips>');
				break;
				//更新/安全
				case 'check_updates':
				case 'add_oauth_api':
				case 'set_oauth_api':
				case 'del_oauth_api':
				case 'scan_trojan_horses':
				
				break;
				//应用/功能
				case 'notification_permission_for_app':
				self::toggleNotification($params);
				break;
				case 'set_contents_for_app':
				self::setContentsForApp($params);
				break;
				case 'remove_app_by_id':
				self::removeApplication($params);
				break;
				case 'update_appinfo_cache':
				APP::buildAppsData();
				exit('<tips>Application Information Cache have updated. If it does not come into effect, please try again later.</tips>');
				break;
				//隐藏命令
				case 'reg_edit_application':
				case 'reg_edit_theme':
				case 'update_preset_fields':
				if($array=json_decode($params, true)){
					$params = $array;
				}else{
					die('<tips>Parameters Error;.</tips>');
				}
				break;
				case 'del_dev_application':
				self::removeApplication($params);
				break;
				//未定义
				default:
				exit('<tips>Invalid Command.</tips>');
			}
		}else{
			exit('<tips>Operation failure or lack of authority.</tips>');
		}
	}
	
	private static function Basic_General($cmd, $param){
		if(General_Settings::Basic_General($cmd, $param)){
			echo '<tips>Operate Successfully!</tips>';
		}else{
			echo '<tips>Operation failure.</tips>';
		}
	}
	
	private static function setInformationByLanguage($cmd, $params){
		switch($cmd){
			case 'create_info_of_lang':
			$reault = Agency_Languges::careteLanguage($params);
			break;
			case 'update_info_by_lang':
			$reault = Agency_Languges::updateLanguage($params);
			break;
			case 'delete_info_of_lang':
			$reault = Agency_Languges::deleteLanguage($params);
			break;
		}
		if($reault){
			echo '<tips>Operate Successfully!</tips>';
		}else{
			echo '<tips>Operation failure.</tips>';
		}
	}
	
	private static function setPathRules($cmd, $params){
		switch($cmd){
			case 'insert_path_rule':
			$reault = Channel_Columns_Router::insertPathRule($params);
			break;
			case 'update_path_rule':
			$reault = Channel_Columns_Router::updatePathRule($params);
			break;
			case 'upward_path_rule':
			$reault = Channel_Columns_Router::upwardPathRule($params);
			break;
			case 'downward_path_rule':
			$reault = Channel_Columns_Router::downwardPathRule($params);
			break;
			case 'delete_path_rule':
			$reault = Channel_Columns_Router::deletePathRule($params);
			break;
		}
		if($reault){
			echo '<tips>Operate Successfully!</tips>';
		}else{
			echo '<tips>Operation failure.</tips>';
		}
	}
	
	private static function toggleNotification($params){
		if($array=json_decode($params, true)){
			if(isset($array[0])&&is_numeric($array[0])&&$array>1000&&isset($array[1])&&is_numeric($array[1])){
				$reault = Applications_Themes::toggleNotification($array[0], $array[1]);
			}else{
				die('<tips>Parameters Error;.</tips>');
			}
		}else{
			if(is_numeric($params)&&$params>1000){
				$reault = Applications_Themes::toggleNotification($params, 1);
			}else{
				die('<ltips>Parameters Error;.</tips>');
			}
		}
		if($reault){
			echo '<tips>Operate Successfully!</tips>';
		}else{
			echo '<tips>Operation failure.</tips>';
		}
	}
	
	private static function setContentsForApp($params){
		if($array=json_decode($params, true)&&isset($array[0])&&is_numeric($array[0])&&$array>1000&&isset($array[1])&&is_array($array[1])){
			if(Applications_Themes::setContentsForApp($array[0], $array[1])){
				echo '<tips>Operate Successfully!</tips>';
			}else{
				echo '<tips>Operation failure.</tips>';
			}
		}else{
			die('<tips>Parameters Error;.</tips>');
		}
	}
	
	private static function removeApplication($appid){
		if(is_numeric($appid)&&$appid>1000){
			if(Applications_Themes::removeApplication($appid)){
				echo '<tips>Operate Successfully!</tips>';
			}else{
				echo '<tips>Operation failure.</tips>';
			}
		}else{
			die('<tips>Parameters Error;.</tips>');
		}
	}
}