<?php
namespace Library\formattings;

class Timer {
	private static function auto($time){
		if (date("Y-m-d", $time) == date("Y-m-d")) {
			return date("H:ia", $time);
		} else if (date("Y-m-d") - date("Y-m-d", $time) < 7) {
			return date("l", $time);
		} else if (date("Y", $time) == date("Y")) {
			return date("m-d", $time);
		} else {
			return date("Y-m-d", $time);
		}
	}

	private static function plural($number, $single, $plurel){
		$num = floor($number);
		if ($num > 1) {
			return $num.$plural;
		} else {
			return $num.$single;
		}
	}

	private $data = [
		"before"	=>	[
        	"year ago"	=>	" year ago",
        	"years ago"	=>	" years ago",
        	"month ago"	=>	" month ago",
        	"months ago"	=>	" months ago",
        	"week ago"	=>	" week ago",
        	"weeks ago"	=>	" weeks ago",
        	"day ago"	=>	" day ago",
        	"days ago"	=>	" days ago",
        	"hour ago"	=>	" hour ago",
        	"hours ago"	=>	" hours ago",
        	"in an hour"	=>	" in an hour",
        	"in half an hour"	=>	" in half an hour",
        	"in 5 minutes"	=>	" in 5 minutes",
        	"just now"	=>	" just now"
		],
    	"now"	=>	" at present",
    	"after"	=>	[
        	"year later"	=>	" year later",
        	"years later"	=>	" years later",
        	"month later"	=>	" month later",
        	"months later"	=>	" months later",
        	"week later"	=>	" week later",
        	"weeks later"	=>	" weeks later",
        	"day later"	=>	" day later",
        	"days later"	=>	" days later",
        	"hour later"	=>	" hour later",
        	"hours later"	=>	" hours later",
       		"within an hour"	=>	" within an hour",
        	"within half an hour"	=>	" within half an hour",
        	"within 5 minutes"	=>	" within 5 minutes",
        	"immediately"	=>	" immediately"
		]
	];

	private function before($dist){
		$localdictwords = $this->data["before"];
		if ($dist > 31536000) {
			return self::plural($dist / 31536000, $localdictwords["year ago"], $localdictwords["years ago"]);
		} else if ($dist > 2678400) {
			return self::plural($dist / 2678400, $localdictwords["month ago"], $localdictwords["months ago"]);
		} else if ($dist > 604800) {
			return self::plural($dist / 604800, $localdictwords["week ago"], $localdictwords["weeks ago"]);
		} else if ($dist > 86400) {
			return self::plural($dist / 86400, $localdictwords["day ago"],  $localdictwords["days ago"]);
		} else if ($dist > 3600) {
			return self::plural($dist / 3600, $localdictwords["hour ago"], $localdictwords["hours ago"]);
		}
	}

	private function after($dist){
		$localdictwords = $this->data["after"];
		if ($dist > 31536000) {
			return self::plural($dist / 31536000, $localdictwords["year later"], $localdictwords["years later"]);
		} else if ($dist > 2678400) {
			return self::plural($dist / 2678400, $localdictwords["month later"], $localdictwords["months later"]);
		} else if ($dist > 604800) {
			return self::plural($dist / 604800, $localdictwords["week later"], $localdictwords["weeks later"]);
		} else if ($dist > 86400) {
			return self::plural($dist / 86400, $localdictwords["day later"],  $localdictwords["days later"]);
		} else if ($dist > 3600) {
			return self::plural($dist / 3600, $localdictwords["hour later"], $localdictwords["hours later"]);
		}
	}

	private function relative($time){
		$before = $this->data["before"];
		$now = $this->data["now"];
		$after = $this->data["after"];
		$dist = time() - $time;
		if ($dist > 3600) {
			return self::before($dist);
		} else if ($dist > 60) {
			$minute = floor($dist / 60);
			if ($minute > 30) {
				return $before["in an hour"];
			} else if ($minute > 5) {
				return $before["in half an hour"];
			} else {
				return $before["in 5 minutes"];
			}
		} else if ($dist > 0) {
			return $before["just now"];
		} else if ($dist == 0) {
			return $now;
		} else if ($dist < -3600) {
			self::after(-$dist);
		} else if ($dist < -60) {
			$minute = -floor($dist / 60);
			if ($minute > 30) {
				return $after["within an hour"];
			} else if ($minute > 5) {
				return $after["within half an hour"];
			} else {
				return $after["within 5 minutes"];
			}
		} else {
			return $after["immediately"];
		}
	}

	public function __construct($lang){
		if(is_file(PATH_LIBX.'localtimes/'.$lang.'.json')&&($data = json_decode(PATH_LIBX.'localtimes/'.$lang.'.json', true))){
			$this->data = $data;
		}else{
			$la = substr($lang, 0, 2);
			$files = glob(PATH_LIBX.'localtimes/'.$la.'*.json');
			if(isset($files[0])){
				if(is_file($files[0])&&($data = json_decode($files[0], true))){
					$this->data = $data;
				}
			}
		}
	}

	public function format($_time, $format = "Y-m-d H:i:s") {
		$time = strtotime($_time);
		switch ($format) {
			case "auto":
			return self::auto($time);
			break;
			case "relative":
			return self::relative($time);
			break;
			default:
			return date($format, $time);
			break;
		}
	}

	public function when(){
		switch(date("H")){
			case 1:case 2:case 3:case 4:
			return 'WEE';
			break;
			case 5:case 6:
			return 'DAWN';
			break;
			case 7:case 8:case 9:case 10:case 11:
			return 'MORNING';
			break;
			case 12:case 13:
			return 'NOON';
			break;
			case 14:case 15:case 16:case 17:
			return 'AFTERNOON';
			case 18:case 19:case 20:case 21:
			return 'EVENING';
			case 22:case 23:case 0:
			return 'NIGHT';
			break;
		}
	}

	public function timeDistance() {}
}