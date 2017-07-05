<?php
namespace Library\compilers;
use Storage;

const OS        = '.os';
const VARNAME   = '([\w\$]+)';
const SPACE     =   '[\s\r\n]';
const EQUAL     =   '(=|:)';
const KEYWORD   =   '(const|static|private|public|type)';
const FRAGMENT  =   '<OS_FRAGMENT_(\d+)';

class OperationScript {
    const USE_REGEXP = '/use\s+<([^>\n]+)>;*/';
    const STRING_REGEXP = '/"[^"\n]+"/';
    const FRAGMENT_REGEXP_O = '/\/\*[\s\S]*?\*\//';
    const FRAGMENT_REGEXP_I = '/\([^\(\)]*\)/';
    const FRAGMENT_REGEXP_II = '/\[[^\[\]]*\]/';
    const FRAGMENT_REGEXP_III = '/\{[^\{\}]*\}/';
    const EQUAL_REGEXP = '/'.SPACE.'*'.EQUAL.SPACE.'*/';
    const BREAK_REGEXP = '/'.SPACE.'+/';
    const KEYWORD_REGEXP = '/[\s;]*'.KEYWORD.'\s*/';

    const EXP_REGEXP = '/'.KEYWORD.'\s+'.VARNAME.'{0,1}(.+)/';
    const EXP_VALUE_REGEXP = '/^'.EQUAL.'([^;]+)/';
    const EXP_CLASS_REGEXP = '/^\s*('.FRAGMENT.')>/';
    const EXP_METHOD_REGEXP = '/^\s*'.FRAGMENT.'>\s*('.FRAGMENT.')>/';
    const EXP_FRAGMENT_REGEXP = '/'.FRAGMENT.'>*/';
    const EXP_SIMPLE_REGEXP = '/'.VARNAME.'([^,\}]+),*/';

    const KEYWORD = '<OS_KEYWORD_'.BOOTTIME.'>';
    const INCOMMA = '<OS_INCOMMA>';
    const QUOT = '<OS_QUTO>';
    const INCOMMA_STRING = '<OS_INCOMMA_STRING_';
    const QUOT_STRING = '<OS_QUTO_STRING_';
    const FRAGMENT = '<OS_FRAGMENT_';

    private
    $filenames = [],
    $source = 'alert("Error");',

    $strings1 = [],
    $strings2 = [],
    $fragments = [],

    $tokens = [],

    $midast = [],

    $exps = [],

    $constnames = ['__LANG__', 'self'],

    $private_methods = [],

    $ast = [];

    final public function __construct($dir){
		$this->dir = $dir;
	}

	final public function complie($osfile, $outfile, $minfile, $lang=false){
        global $NEWIDEA;
        $this->source = $this->loadSource($osfile);
        $this->tokenizer();
        $this->walk();
        $this->transformer();
        $this->generator($NEWIDEA, $outfile, $minfile, $lang);
        return $this->code;
	}

    private function loadSource($filename){
        if(in_array($filename, $this->filenames)){
            return '';
        }
        $this->filenames[] = $filename;
        $content = file_get_contents($filename);
        preg_match_all(self::USE_REGEXP, $content, $matches, PREG_SET_ORDER);
        if($matches){
            foreach($matches as $match){
                $search = $match[0];
                $replacefile = $this->dir.$match[1].OS;
                if(is_file($replacefile)){
                    $replace = $this->loadSource($replacefile);
                }else{
                    $replace = '';
                }
                $content = str_replace($search, $replace, $content);
            }
        }
        return $content;
    }

    private function tokenizer(){
        $content = preg_replace(self::EQUAL_REGEXP, '$1', $this->source);
        $content = $this->replaceConsts($content);
        $content = $this->replaceStrings($content);
        $content = $this->replacefragments($content);
        $content = preg_replace(self::BREAK_REGEXP, ' ', $content);
        $content = preg_replace(self::KEYWORD_REGEXP, ";\n$1 ", $content) . ';';
        
        preg_match_all(self::EXP_REGEXP, $content, $matches, PREG_SET_ORDER);
        if($matches){
            $this->tokens = $matches;
        }
    }

    private function replaceConsts($content){
        $content = str_replace("\'", self::INCOMMA, $content);
        return str_replace('\"', self::QUOT, $content);
    }

    private function replaceStrings($content){
        $array = explode("'", $content);
        $content = '';
        $inString = true;
        $isOpened = false;
        foreach($array as $string){
            if($inString){
                $content .= $string;
                if($isOpened){
                    if(substr_count($string, '"')%2){
                        $isOpened = false;
                        $inString = false;
                    }
                }else{
                    if(substr_count($string, '"')%2){
                        $isOpened = true;
                    }else{
                        $inString = false;
                    }
                }
            }else{
                $content .= self::INCOMMA_STRING.array_push($this->strings1, $string).'>';
                $inString = true;
            }
        }

        return preg_replace_callback(self::STRING_REGEXP , function($matches) {
            $replace = self::QUOT_STRING.array_push($this->strings2, $matches[0]).'>';
            return $replace;
        }, $content);
    }

    private function replacefragments($content){
        $content= preg_replace(self::FRAGMENT_REGEXP_O, '', $content);
        while(preg_match_all(self::FRAGMENT_REGEXP_I, $content, $matches, PREG_SET_ORDER)){
            foreach($matches as $match){
                $search = $matches[0];
                $replace = self::FRAGMENT.array_push($this->fragments, $match[0]).'>';
                $content = str_replace($search, $replace, $content);
            }
        }
        while(preg_match_all(self::FRAGMENT_REGEXP_II, $content, $matches, PREG_SET_ORDER)){
            foreach($matches as $match){
                $search = $matches[0];
                $replace = self::FRAGMENT.array_push($this->fragments, $match[0]).'>';
                $content = str_replace($search, $replace, $content);
            }
        }
        while(preg_match_all(self::FRAGMENT_REGEXP_III, $content, $matches, PREG_SET_ORDER)){
            foreach($matches as $match){
                $search = $matches[0];
                $replace = self::FRAGMENT.array_push($this->fragments, $match[0]).'>';
                $content = str_replace($search, $replace, $content);
            }
        }
        //var_dump($content);
        return $content;
    }

    private function walk() {
        foreach($this->tokens as $token){
            if($token[2]){
                $this->walk_simple($token[1], $token[2], $token[3]);
            }elseif(preg_match(self::EXP_FRAGMENT_REGEXP, $token[3], $matches)){
                $this->walk_group($token[1], $matches[1]);
            }
        }
        //var_dump($this->midast);
        //die;
    }

    private function walk_group($type, $n) {
        preg_match_all(self::EXP_SIMPLE_REGEXP, $this->fragments[$n-1], $matches, PREG_SET_ORDER);
        //var_dump($this->fragments[$n-1] , $matches);
        if($matches){
            foreach($matches as $token){
                $this->walk_simple($type, $token[1], $token[2]);
            }
        }
    }

    private function walk_simple($type, $name, $token) {
        if(preg_match(self::EXP_METHOD_REGEXP, $token, $matches)){
             $this->midast[] = [
                'name'  =>  $name,
                'type'  =>  $type . ' method',
                'args'  =>  $this->fragments[$matches[1]-1],
                'code'  =>  $matches[2]
             ];
        }elseif(preg_match(self::EXP_VALUE_REGEXP, $token, $matches)){
            $exps = explode(',', $matches[2]);
            $this->midast[] = [
                'name'  =>  $name,
                'type'  =>  $type . ' member',
                'value' =>  $exps[0]
            ];
            $count = count($exps);
            if($count>1){
                for($i = 1; $i < $count; $i++){
                    $exp = explode('=', $exps[$i]);
                    if(isset($exp[2])){
                        $this->midast[] = [
                            'name'  =>  $exp[0],
                            'type'  =>  $type . ' method',
                            'args'  =>  $this->fragments[preg_replace('/\D+/', '', $exp[1])-1],
                            'code'  =>  preg_replace('/(>|\s|;)/', '', $exp[2])
                        ];
                    }elseif(isset($exp[1])){
                        $this->midast[] = [
                            'name'  =>  $exp[0],
                            'type'  =>  $type . ' member',
                            'value' =>  $exp[1]
                        ];
                    }else{
                        new Status('Syntactic Error :'.$exps[$i], true);
                    }
                }
            }
        }elseif(preg_match(self::EXP_CLASS_REGEXP, $token, $matches)){
            $this->midast[] = [
                'name'  =>  $name,
                'type'  =>  $type,
                'body' =>  $matches[1]
            ];
        }
    }

    private function classify(){
        $exps = [[],[],[],[]];
        foreach($this->midast as $exp){
            switch($exp['type']){
                case 'const member':
                if(in_array($exp['name'], $this->constnames)){
                    die('Connot Redeclare Const '.$exp['name']);
                }else{
                    $this->constnames[] = $exp['name'];
                    $exp['type'] = 'member';
                    $exps[0][$exp['name']] = $exp;
                }
                break;

                case 'type':
                $exp['type'] = 'class';
                $exps[0][$exp['name']] = $exp;
                break;

                case 'static member':
                $exp['type'] = 'member';
                $exps[1][$exp['name']] = $exp;
                break;

                case 'private member':
                $exp['type'] = 'member';
                $exps[2][$exp['name']] = $exp;
                break;

                case 'public member':
                $exp['type'] = 'member';
                $exps[3][$exp['name']] = $exp;
                break;

                case 'const method':
                if(in_array($exp['name'], $this->constnames)){
                    new Status('Connot Redeclare Const '.$exp['name'], true);
                }else{
                    $this->constnames[] = $exp['name'];
                    $exp['type'] = 'method';
                    $exps[0][$exp['name']] = $exp;
                }
                break;

                case 'static method':
                $exp['type'] = 'method';
                $exps[1][$exp['name']] = $exp;
                break;

                case 'private method':
                $exp['type'] = 'method';
                $exps[2][$exp['name']] = $exp;
                break;

                case 'public method':
                $exp['type'] = 'method';
                $exps[3][$exp['name']] = $exp;
                break;
            }
        }
        return $exps;
    }

    private function transformer() {
        $ast = [
			'type' => 'AST',
			'body' => []
        ];
        $this->constnames[] = 'pm_'.uniqid();
        $this->constnames[] = 'pm_'.uniqid();
        $this->constnames[] = '__APPDIR__';
        $this->constnames[] = 'privates';
        $this->exps = $this->classify();
        //var_dump($this->exps);
        foreach($this->exps as $n => $expt){
            if($n==0){
                $ast['body'] = $this->walk_consts($expt);
            }else{
                $ast['body'][] = [
                    'type' => 'Const',
                    'name' => $this->constnames[$n],
			        'body' => [
                        'type'  =>  'Object',
                        'body'  =>  $this->walk_const($expt, $n===2)
                    ]
                ];
            }
        };
        $ast['body'][] = [
            'type' => 'Const',
            'name' => 'privates',
			'body' => [
                'type'  =>  'Object',
                'body'  =>  $this->private_methods
            ]
        ];
        $this->ast = $ast;
        //var_dump($ast, $this->private_methods);
        //var_dump($this->private_methods);
        //die;
	}

    private function walk_consts($expt) {
        $array = [];
        foreach($expt as $exp){
            if($exp['type'] == 'member'){
                $array[] = [
                    'type' => 'Const',
                    'name' => $exp['name'],
			        'body' => [
                        'type'  =>  'String',
                        'value' =>  $this->trans_member($exp['value'])
                    ]
                ];                
            }elseif($exp['type'] == 'method'&&preg_match(self::EXP_FRAGMENT_REGEXP, $exp['code'], $matches)){
                $array[] = [
                    'type' => 'Const',
                    'name' => $exp['name'],
			        'body' => [
                        'type'  =>  'String',
                        'value' =>  'function' . $exp['args'] . $this->trans_fragment($this->fragments[$matches[1]-1])
                    ]
                ];
            }elseif($exp['type'] == 'class'&&preg_match(self::EXP_FRAGMENT_REGEXP, $exp['body'], $matches)){
                $array[] = [
                    'type' => 'Const',
                    'name' => $exp['name'],
			        'body' => [
                        'type'  =>  'String',
                        'value' =>  'YangRAM.API.declareClass(' . $this->walk_type($this->fragments[$matches[1]-1]) . ')'
                    ]
                ];
            }
        }
        return $array;
    }

    private function walk_const($expt, $is_private) {
        $array = [];
        foreach($expt as $exp){
            if($exp['type'] == 'member'){
                $member = $exp['name'] . ' : ' . $this->trans_member($exp['value'], $is_private);
                if($is_private){
                    $this->private_methods[]  = $member;
                }
                $array[] = $member;
            }elseif($exp['type'] == 'method'&&preg_match(self::EXP_FRAGMENT_REGEXP, $exp['code'], $matches)){
                $array[] = $this->trans_method($exp['name'], $exp['args'], $this->fragments[$matches[1]-1], $is_private);
            }
        }
        return $array;
    }

    private function walk_type($value) {
        $value = preg_replace_callback('/'.VARNAME.'\s*'.FRAGMENT.'>\s*('.FRAGMENT.')>/', function($matches){
            return $matches[1] . ': function' . $this->fragments[$matches[2]-1] . $this->trans_fragment($this->fragments[$matches[4]-1], false);
        }, $value);
        $value = preg_replace_callback(self::EXP_FRAGMENT_REGEXP, function($matches){
            return $this->trans_fragment($this->fragments[$matches[1]-1], false);
        }, $value);
        return $value;
    }

    private function trans_member($value) {
        if(preg_match('/'.self::INCOMMA_STRING.'(\d+)/', $value, $matches)){
            return "'".$this->strings1[$matches[1]-1]."'";
        }
        if(preg_match('/'.self::QUOT_STRING.'(\d+)/', $value, $matches)){
            return $this->strings2[$matches[1]-1];
        }
        if(preg_match(self::EXP_FRAGMENT_REGEXP, $value, $matches)){
            return $this->trans_fragment($this->fragments[$matches[1]-1]);
        }
        return $value;
    }

    private function trans_method($name, $args, $value, $is_private) {
        if($is_private){
            $this->private_methods[]  = $name . $args . ' {' . "\n\t\t"
                . 'return ' . $this->constnames[2] . '.' . $name . '.call(__thisapp__' . preg_replace('/^\s*(\w)/', ', $1', str_replace('(', '', $args))
                . "\n" . '}';
        }
        return $name
            . $args
            . $this->trans_fragment($value);
    }

    private function trans_fragment($value) {
        $value = preg_replace_callback(self::EXP_FRAGMENT_REGEXP, function($matches){
            return $this->trans_fragment($this->fragments[$matches[1]-1]);
        }, $value);

        $value = preg_replace_callback('/'.self::INCOMMA_STRING.'(\d+)>/', function($matches){
            return "'".$this->strings1[$matches[1]-1]."'";
        }, $value);

        $value = preg_replace_callback('/'.self::QUOT_STRING.'(\d+)>/', function($matches){
            return $this->strings2[$matches[1]-1];
        }, $value);
        return $value;
    }

    private function generator($NEWIDEA, $outfile, $minfile, $lang){
        if(in_array(AI_CURR, ['SETTINGS', 'I4PLAZA', 'EXPLORER', 'TRASHCAN'])){
            $code = "RegApplication('".AI_CURR."', (__thisapp__, System, YangRAM, Using, Global, undefined) => {\n";
            $code .= "'use strict';\n";
            $code .= "const __LANG__ = System.Runtime.locales.".AI_CURR.";\n";
        }else{
            $code = "RegApplication(".AI_CURR.", (__thisapp__, YangRAM, Using, Global, undefined) => {\n";
            $code .= "'use strict';\n";
            if($lang){
                $code .= "const __LANG__ = ".$lang.";\n";
		    }else{
                if(preg_match_all('/.*(\=|\r|\n|\s|\,|\(|\{)__LANG__(\.|\r|\n|\s|\;|\,|\)|\}).*/', $this->source, $matches)){
                    echo 'Your code used const "__LANG__", but your app does not support language pack.Please review your codes.';
                    foreach($matches[0] as $index=>$code){
                        $code = trim($code);
                        echo "\n[$index] $code";
                    }
                    exit;
                }else{
                    $code .= "const __LANG__ = {};\n";
                }
            }
        }

        $code .= "const __ = (word) => {\n"; 
        $code .= "\treturn YangRAM.API.TXT.dictReader(__LANG__, word);\n"; 
        $code .= "};\n"; 
        
        //$code .= 'const '. join(",\n\t", $this->constnames) . ";\n";
        $code .= "const __APPDIR__ = '".AD_CURR."';\n";
        foreach($this->ast['body'] as $const){
            $code .= 'const '.$const['name'];
            $code .= ' = ';
            if($const['body']['type']==='String'){
                $code .= $const['body']['value'];
            }elseif($const['body']['type']==='Object'){
                $code .= '{'. "\n\t";
                if(count($const['body']['body'])){
                    $code .= join(",\n\t", $const['body']['body']);
                }
                $code .= '}';
            }
            $code .= ";\n";
        }
        $code = str_replace(self::INCOMMA, "\'", $code);
        $code = str_replace(self::QUOT, '\"', $code);
        
        $code .= 'YangRAM.extends(__thisapp__, true, '.$this->constnames[3].");\n";
        $code .= "\n});";
        $mini = JSMin::minify($code);
        Storage::writeFile($outfile, $code);
        Storage::writeFile($minfile, $mini);
        if(_USE_DEBUG_MODE_){
			$this->code = $code;
		}else{
			$this->code = $mini;
		}
    }
}
