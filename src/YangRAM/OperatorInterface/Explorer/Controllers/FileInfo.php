<?php
namespace Explorer\Controllers;

use Controller;
use CM\SRC;

class FileInfo extends Controller {
    public function img($ID){
        $row = SRC::byId($ID, 'img');
		if($row){
			if(is_file(PATH_PUB.$row->LOCATION)){
				$row = $row->toArray();
				unset($row["FILE_TYPE"]);
				unset($row["LOCATION"]);
				unset($row["DURATION"]);
				unset($row["KEY_IS_RECYCLED"]);
				unset($row["USR_ID"]);
				echo json_encode($row);
			}else{
				$row->destroy();
				echo '<ERROR404>';
			}
		}else{
			echo '<ERROR>';
		}
	}
	
	public function txt($ID){
		$row = SRC::byId($ID, 'txt');
		if($row){
            $row = $row->toArray();
			if(is_file(PATH_PUB.$row["LOCATION"])){
				$row["FILE_CONTENT"] = file_get_contents(PATH_PUB.$row["LOCATION"]);
				unset($row["FILE_TYPE"]);
				unset($row["LOCATION"]);
				unset($row["DURATION"]);
				unset($row["IMAGE_SIZE"]);
				unset($row["WIDTH"]);
				unset($row["HEIGHT"]);
				unset($row["KEY_IS_RECYCLED"]);
				unset($row["USR_ID"]);
				echo json_encode($row);
			}else{
				$row->destroy();
				echo '<ERROR404>';
			}
		}else{
			echo '<ERROR>';
		}
	}

	public function wav($ID){
		$row = SRC::byId($ID, 'wav');
		if($row){
            $row = $row->toArray();
			if(is_file(PATH_PUB.$row["LOCATION"])){
				unset($row["FILE_TYPE"]);
				unset($row["LOCATION"]);
				unset($row["IMAGE_SIZE"]);
				unset($row["WIDTH"]);
				unset($row["HEIGHT"]);
				unset($row["KEY_IS_RECYCLED"]);
				unset($row["USR_ID"]);
				echo json_encode($row);
			}else{
				$row->destroy();
				echo '<ERROR404>';
			}
		}else{
			echo '<ERROR>';
		}
	}
}
