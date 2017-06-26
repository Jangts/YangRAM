<?php
namespace Library\ect;

class HTMLClose {
    public static function compile($input){
        $obj = new HTMLClose($input);
        return $obj->complete();
    }

    public
    $body = '',
    $strlen = 0,
    $midast = [],
    $ast = [];

    public function __construct($body) {
        $this->body = $body;
        if (strpos($body, '<') === false) {
            $ast = [$body];
            return;
        }
        $this->lexicalanAlysis($body);
    }

    private function lexicalanAlysis($body) {
        $midast = [
            'tags'  => [],
            // 'left'  => [],
            // 'right' => [],
            'text'  => []
        ];
        $strlen = strlen($body);
        $strsum = '';
        
        for ($i = 0; $i < $strlen; ++$i) {
            $current = substr($body, $i, 1);
            if ($current == '<') {
                // html 代码开始
                $tagnum = 1;
                $htmtxt = '';
            } else if ($tagnum == 1) {
                // 一段 html 代码结束
                if ($current == '>') {
                    /**
                     * 去除首尾空格，如 <br /  > < img src="" / > 等可能出现首尾空格
                     */
                    $htmtxt = trim($htmtxt);
                     
                    /**
                     * 判断最后一个字符是否为 /，若是，则标签已闭合，不记录
                     */
                    if (substr($htmtxt, -1) != '/') {
                        // 判断第一个字符是否 /，若是，则放在 right 单元
                        $f = substr($htmtxt, 0, 1);
                        if ($f == '/') {
                            // 去掉 /
                            $midast['tags'][] =  [
                                'is_open'    =>  false,
                                'tagname'   =>  str_replace('/', '', $htmtxt)
                            ];
                            $midast['text'][] = $strsum;
                            $strsum = '';
                        } else if ($f != '?') {
                            // 判断是否为 ?，若是，则为 PHP 代码，跳过
                            
                            /**
                             * 判断是否有半角空格，若有，以空格分割，第一个单元为 html 标签
                             * 如 <h2 class="a"> <p class="a">
                             */
                            if (strpos($htmtxt, ' ') !== false) {
                                // 分割成2个单元，可能有多个空格，如：<h2 class="" id="">
                                $tagname = strtolower(current(explode(' ', $htmtxt, 2)));
                            } else {
                                // 若没有空格，整个字符串为 html 标签，如：<b> <p> 等
                                $tagname = strtolower($htmtxt);
                            }
                            $midast['tags'][] = [
                                'is_open'    =>  true,
                                'tagname'   =>  $tagname,
                                'tagtext'   =>  $htmtxt
                            ];$tagname;
                            $midast['text'][] = $strsum;
                            $strsum = '';
                        }
                    }
                    
                    // 字符串重置
                    $htmtxt = '';
                    $tagnum = 0;
                } else {
                    /**
                     * 将< >之间的字符组成一个字符串
                     * 用于提取 html 标签
                     */
                    $htmtxt .= $current;
                }
            } else {
                // 非 html 代码才记数
                --$size;
            }
            
            $ord_var_c = ord($body{$i});
            
            switch (true) {
                case (($ord_var_c & 0xE0) == 0xC0):
                    // 2 字节
                    $strsum .= substr($body, $i, 2);
                    $i += 1;
                    break;

                case (($ord_var_c & 0xF0) == 0xE0):
                    // 3 字节
                    $strsum .= substr($body, $i, 3);
                    $i += 2;
                    break;

                case (($ord_var_c & 0xF8) == 0xF0):
                    // 4 字节
                    $strsum .= substr($body, $i, 4);
                    $i += 3;
                    break;

                case (($ord_var_c & 0xFC) == 0xF8):
                    // 5 字节
                    $strsum .= substr($body, $i, 5);
                    $i += 4;
                    break;

                case (($ord_var_c & 0xFE) == 0xFC):
                    // 6 字节
                    $strsum .= substr($body, $i, 6);
                    $i += 5;
                    break;
                default:
                    // 1 字节
                    $strsum .= $current;
            }
        }
        $midast['text'][] = $strsum;
        $this->syntacticAnalyzer($this->midast = $midast);
    }

    public function syntacticAnalyzer($midast) {
        $tags = $midast['tags'];
        $text = $midast['text'];
        $opens = [];
        foreach ($tags as $index => $tag) {
            $tagname = $tag['tagname'];
            if($tag['is_open']){
                if(!in_array($tagname, ['img', 'input', 'br', 'link', 'meta'])){
                    $opens[] = $tagname;
                    $opens = array_merge($opens);
                }
            }else{
                if($len = count($opens)){
                    $max = $len-1;
                    if($opens[$max]===$tagname){
                        unset($opens[$max]);
                        $opens = array_merge($opens);
                    }else{
                        $posi = array_search($tagname, $opens);
                        if($posi!==false){
                            for($max; $max > $posi; $max--){
                                $text[$index] = '></' . $opens[$max]  . $text[$index];
                                unset($opens[$max]);
                            }
                            unset($opens[$posi]);
                            $opens = array_merge($opens);
                        }else{
                            $text[$index] = '><' . $tagname  . $text[$index];
                        }
                    }
                }else{
                    if($index){
                        $text[$index] = '><' . $tagname  . $text[$index];
                    }else{
                        $text[$index] = '<' . $tagname . '>' . $text[$index];
                    }
                }
            }
        }
        if($len = count($opens)){
            $max = $len-1;
            for($max; $max >= 0; $max--){
                $text[] = '</' . $opens[$max] . '>';
                unset($opens[$max]);
            }
        }
        $this->ast = $text;
    }
    
    public function complete() {
        return implode('', $this->ast);
    }
}

// header("Content-Type: text/plain;");

// $input = 'aaa</p></div><h3 class="a">0000<p>1111<b>2222</b></b>3333</h3><img src="123.jpg" /><br><p>abcd<span>e';

// $obj = new HTMLClose($input);

// var_dump($input, $obj->complete(), $obj);
