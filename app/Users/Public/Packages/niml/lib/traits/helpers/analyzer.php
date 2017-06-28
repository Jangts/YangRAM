<?php
/*
 * NIML Compiler
 */
trait NIML_traits_helpers_analyzer {
    private function unescape($string){
        if(preg_match_all('/@__string__(\d+);/', $string, $matches)){
            foreach ($matches[1] as $key) {
                $string = str_replace('@__string__'.$key.';', '"'.$this->strings[$key].'"', $string);
            }
        }
        if(preg_match_all('/@__string__left__(\d+);/', $string, $matches)){            
            foreach ($matches[1] as $key) {
                //var_dump('@__string__left__'.$key.';', '"'.$this->strings_L[$key], $string);
                $string = str_replace('@__string__left__'.$key.';', '"'.$this->strings_L[$key], $string);
            }
            //die;
        }
        if(preg_match_all('/@__string__right__(\d+);/', $string, $matches)){
            foreach ($matches[1] as $key) {
                //var_dump('@__string__right__'.$key.';', '"'.$this->strings_R[$key], $string);
                $string = str_replace('@__string__right__'.$key.';', $this->strings_R[$key].'"', $string);
            }
        };
        return str_replace('@__quote__;', '\"', $string);
	}

    private function string($string){
        if(preg_match('/^@__string__(\d+);$/', $string, $matches)){
            $string = str_replace('@__string__'.$matches[1].';', $this->strings[$matches[1]], $string);
            return str_replace('@__quote__;', '"', $string);
        }
		return false;
	}

    private function attribute($string){
        $attrs = [];
		$string = preg_replace('/\s*=\s*/', '=', trim($string));
        $array = preg_split('/\s+/', $string);
        foreach ($array as $str) {
            $param = explode('=', $str);
            $attrs[$param[0]] = isset($param[1]) ? (($value = $this->string($param[1])) ? $value: $param[1]) : $param[0];
        }
        return $attrs;
    }

    private function arguments($string){
        $args = [];
        $array = preg_split('/,\s*/', trim($string));
        foreach ($array as $string) {
            $args[] = $this->argument($string);
        }
        return $args;
    }

    private function argument($string){
        if(preg_match('/^("|\')([\s\S]*)\1$/', $string, $matches)){
            return array(
                'type'  =>  'String',
                'value' =>  $matches[2]
            );
        }
        return $this->get_type($string);
    }

    private function get_array($string){
        $elements = [];
        $array = explode(',', $string);
        $length = count($array);
        for ($i = 0; $i < $length; $i++) {
            if(preg_match('/^([\$\w\.]+)\(([@\$\w\[\`\]]+)$/', $array[$i], $matches)){
                $el = array(
                    'type'  =>  'CallExpression',
                    'name'  =>  $matches[1],
                    'args'  =>  array($this->get_type($matches[2]))
                );
                $i++;
                for ($i; $i < $length; $i++) {
                    if(preg_match('/^([@\w\;]+)\)$/', $array[$i], $ma)){
                        $el['args'][] = $this->get_type($ma[1]);
                        break;
                    }else{
                        $el['args'][] = $this->get_type($array[$i]);
                    }
                }
                $elements[] = $el;
            }
            elseif($el = $this->get_type($array[$i])){
                $elements[] = $el;
            }
        }
		return $elements;
    }

    private function get_type($string){
        // var_dump($string);
		if(is_string($string)){
			if(is_numeric($string)){
	            return array(
	                'type'  =>  'Number',
	                'value' =>  $string
	            );
	        }

            if(strtolower($string)==='true'){
	            return array(
	                'type'  =>  'Bool',
	                'value' =>  true
	            );
	        }

            if(strtolower($string)==='false'){
	            return array(
	                'type'  =>  'Bool',
	                'value' =>  false
	            );
	        }

            if(strtolower($string)==='null'){
	            return array(
	                'type'  =>  'Null',
	                'value' =>  NULL
	            );
	        }

            if($str = $this->string($string)){
	            return array(
	                'type'  =>  'String',
	                'value' =>  $this->unescape($str)
	            );
	        }

            if(preg_match('/^\'([^\']+)\'$/', $string, $matches)){
	            return array(
	                'type'  =>  'String',
	                'value' =>  $this->unescape($matches[1])
	            );
	        }

            if(preg_match('/^"([^"]+)"$/', $string, $matches)){
	            return array(
	                'type'  =>  'String',
	                'value' =>  $this->unescape($matches[1])
	            );
	        }

            if(preg_match('/^\$\w.+$/', $string)){
                if(preg_match('/^\$\w[\.\$\w]*$/', $string)){
                    if(!preg_match('/\]\w+$/',$string)){
                        return array(
        	                'type'  =>  'Variable',
        	                'value' =>  $string
        	            );
    	            }
                }
	        }

            if(preg_match('/^\w+$/', $string)){
	            return array(
	                'type'  =>  'Const',
                    'namespace' => '',
	                'value' =>  $string
	            );
	        }

            if(preg_match('/^([\w\.\s]+){0,1}(\w+\.\w+)$/', $string, $matches)){
	            return array(
	                'type'  =>  'Const',
                    'namespace' => '.'.$matches[1],
	                'value' =>  $matches[2]
	            );
	        }

            if(preg_match('/^([\w\\\\s]+){0,1}(\w+\:\:\w+)$/', $string, $matches)){
	            return array(
	                'type'  =>  'Const',
                    'namespace' => '.'.$matches[1],
	                'value' =>  $matches[2]
	            );
	        }

            if(preg_match('/^(\$[\w\.]+)\[(\$*\w+)\]$/', $string, $matches)){
	            return array(
	                'type'  =>  'ArrayElement',
	                'value' =>  $matches[1],
                    'index' =>  $matches[2]
	            );
	        }

            if(preg_match('/^(\$[\w\.]+)\[`([^\`]+)?`\]$/', $string, $matches)){
	            return array(
	                'type'  =>  'ArrayElement',
	                'value' =>  $matches[1],
                    'index' =>  '"'.$matches[2].'"'
	            );
	        }

            if(preg_match('/^((\w+\.)*)(\w+\.)(\$[\$\w\.]+)$/', $string, $matches)){
	            return array(
	                'type'  =>  'StaticMember',
                    'namespace' => '.'.$matches[1],
	                'value' =>  $matches[3] . str_replace('.', '->', $matches[4])
	            );
	        }
            
            if(preg_match('/^((\w+\.)*)(\w+\.)(\$[\$\w\.]+)\[(\$*\w+)\]$/', $string, $matches)){
	            return array(
	                'type'  =>  'StaticArrayElement',
                    'namespace' => '.'.$matches[1],
	                'value' =>  str_replace('.', '::', $matches[3]) . $matches[4],
                    'index' =>  $matches[5]
	            );
	        }

            if(preg_match('/^((\w+\.)*)(\w+\.)(\$[\$\w\.]+)\[`([^\`]+)?`\]$/', $string, $matches)){
	            return array(
	                'type'  =>  'StaticArrayElement',
                    'namespace' => '.'.$matches[1],
	                'value' =>  str_replace('.', '::', $matches[3]) . $matches[4],
                    'index' =>  '"'.$matches[5].'"'
	            );
	        }

           

			if(preg_match('/^\[([\s\S]+)\]$/', $string, $matches)){
				return array(
					'type'  =>  'Array',
					'eles' =>  $this->get_array($matches[1])
				);
			}

            if(preg_match('/^\s*(\d+|\$\w+)\s*(\+|\-|\*|\/|\%)\s*(\d+|\$\w+)\s*$/', $string, $matches)){
			    return array(
				    'type'  =>  'MathExpression',
	                'left'      =>  $matches[1],
                    'opchar'    =>  $matches[2],
                    'right'     =>  $matches[3]
			    );
		    }
		}
		elseif($string===true){
			return array(
				'type'  =>  'Bool',
				'value' =>  true
			);
		}
		elseif($string===false){
			return array(
				'type'  =>  'Bool',
				'value' =>  false
			);
		}
		elseif($string===NULL){
			return array(
				'type'  =>  'Null',
				'value' =>  NULL
			);
		}
        return array(
            'type'  =>  'String',
            'value' =>  'ERROR Expression [' . $string . ']'
        );
    }

    private static function var_unescape($string, $strings){
        if(preg_match_all('/@__varkey__(\d+);/', $string, $matches)){
            foreach ($matches[1] as $key) {
                $string = str_replace('@__varkey__'.$key.';', '['.$strings[$key].']', $string);
            }
        };
        return $string;
	}
}
