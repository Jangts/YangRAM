<?php
namespace AF\Util;

use Tangram\NIDO\DataObject;

/**
 *	Unit Renderer
 *	核心单元渲染器，多用类，
 *  提供一个静态方法供状态码处理器渲染状态页面，同时
 *  提供一个单例供测试员端路由器（NIAF\Tester）渲染测试结果
 */
final class UnitTestResultsRenderer {
    private static function getTestHead($testname){
        return <<<HEAD
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>$testname</title>
<style>
body { margin: 0; padding: 0; background-color: #181833; }
body { color: #FFF; font-size: 14px; word-wrap: break-word; word-break: normal; word-break: break-all; }
header { padding: 8px 40px; background-color: rgba(0,0,51,.85); border-bottom: #09F 4px solid; }
header { line-height: 60px; color: #9F0; font-size: 24px; text-align: center; }
section { padding: 18px 0; margin: 1px 40px 2px; border-bottom: #09F 1px solid; }
section h3 { color: #6C0; font-size: 18px; font-weight: bold; text-decoration: underline; cursor: pointer; }
section h3 a { color: #6C0; }
section h4 { color: #FFF; font-size: 14px; font-weight: bold; }
section del { color: #669; }
section article { min-height: 30px; line-height: 20px; font-size: 12px; margin-bottom: 20px; }
section article p { padding: 5px 8px; background-color: rgba(85,153,187,.8); color: #111; }
section article pre { padding: 5px 8px; background-color: rgba(34,51,51,.7); color: #CCC; }
section article pre { white-space: pre-wrap!important; word-wrap: break-word!important; }
footer { padding: 8px 0; margin: 1px 40px 12px; border-top: #09F 1px solid; }
section article span.data-type { float: left; min-width: 30px; margin: 0; padding: 0 5px; background-color: rgba(0,187,187,.8); line-height: 30px; text-align: center; }
section article span.data-value { float: left; min-width: 30px; margin: 0; padding: 0 5px; background-color: rgba(0,187,102,.8); line-height: 30px; text-align: center;}
footer { line-height: 20px; color: #FFC; font-size: 15px; text-align: right; }
footer span.end-test { float: left; text-align: left; }
footer span.use-mmry { margin-left: 30px; }
#bganim-canvas { position: fixed; top: 0; left: 0; z-index:-1; }
</style>
</head>
HEAD;
    }

    private static function getTestSections($cache){
        $body = '<section>';
        foreach ($cache as $n => $data) {
            if(is_string($data)){
                if(strpos($data, '# SUBTEST')===0){
                    $explodes = explode('call myTest', $data);
                    if(isset($explodes[1])){
                        $anchor = '<a href="'._TESTER_.AC_CURR.'/'.$explodes[1].'/" target="_blank">'.$explodes[0].'</a>';
                    }else{
                        $anchor = $data;
                    }
                    if($n){
                        $body .= '</section><section><h3>' . $anchor . '</h3>';
                    }else{
                        $body .= '<h3>' . $anchor . '</h3>';
                    }
                }elseif($data==='#BATIGNORE'){
                    $body .= '<del>&lt;ignored&gt;</del>';
                }else{
                    if(strpos($data, '>>>XML:<') === 0){
                        $body .= '<h4>A SOURCE CODE STRING: </h4>';
                        $body .= '<article><pre language="xml"><code>' . htmlspecialchars($data) . '</code></pre></article>';
                    }elseif(strpos($data, '>>>JSON:{') === 0||strpos($data, '>>>JSON:[') === 0){
                        $body .= '<h4>A SOURCE CODE STRING: </h4>';
                        $body .= '<article><pre language="json"><code>' . htmlspecialchars(DataObject::jsonToJson($data, false)) . '</code></pre></article>';
                    }elseif(strpos($data, '>>>SERIALIZA:"') === 0){
                        $body .= '<h4>A SOURCE CODE STRING: </h4>';
                        $body .= '<article><pre language="serialize"><code>' . htmlspecialchars($data) . '</code></pre></article>';
                    }else{
                        $body .= '<h4>A STRING RETURN MESSAGE: </h4>';
                        $body .= '<article><p>' . str_replace(PHP_EOL, '<br />', htmlspecialchars($data)) . '</p></article>';
                    }
                    
                }
            }elseif(is_a($data, 'Tangram\NIDO\DataObject')){
                $body .= '<h4>A Tangram\DATUM NIDOECT: </h4>';
                $body .= '<article><pre><code>&gt;&gt;&gt;PRINT LIKE LIST DATA:'. PHP_EOL . htmlspecialchars(print_r($data->toArray(), true)) . '</code></pre></article>';
            }elseif(is_bool($data)){
                $body .= '<h4>A RETURN SCALAR DATA: </h4>';
                $body .= '<article><span class="data-type">boolean</span><span class="data-value">' . ($data ? 'true' : 'false') . '</span></article>';
            }elseif(is_scalar($data)){
                $body .= '<h4>A RETURN SCALAR DATA: </h4>';
                $body .= '<article><span class="data-type">' . gettype($data) . '</span><span class="data-value">' . $data . '</span></article>';
            }elseif(is_array($data)){
                $body .= '<h4>A RETURN LIST DATA: </h4>';
                $body .= '<article><pre><code>' . print_r($data, true) . '</code></pre></article>';
            }elseif($data===NULL){
                $body .= '<h4>NO RETURN DATA</h4>';
                $body .= '<h4>OR JUST RETURN A [ NULL ]</h4>';
            }else{
                $body .= '<h4>AN UNPRINT RETURN NIDOECT('.gettype($data).'): </h4>';
                if(method_exists($data, '__toString')){
                    $body .= '<article><p>&gt;&gt;&gt;PRINT AS STRING:'. PHP_EOL . htmlspecialchars(@$data->__toString()) . '</p></article>';
                }else{
                    $body .= '<article><pre language="json"><code>&gt;&gt;&gt;PRINT AS JSON:'. PHP_EOL . htmlspecialchars(json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)) . '</code></pre></article>';
                }
            }
        }
        $body .= '</section>';
        return $body;
    }

    private static function getTestFooter($testcount, $use){
        if($testcount>1){
            $foot = '<footer><span class="end-test">' .$testcount. ' Tests Finished!(';
        }else{
            $foot = '<footer><span class="end-test">Test Finished!(';
        }
        $foot .= date('M/d/Y H:i:s', $_SERVER['REQUEST_TIME']).')</span><span class="use-time">';
        $foot .= $use;
        $foot .= '</span></footer>';
        $foot .= '<canvas id="bganim-canvas"></canvas>';
        $foot .= '<script src="'.SSRC_PID.'tweenlite.min.js"></script>';
        $foot .= '<script src="'.SSRC_PID.'easepack.min.js"></script>';
        $foot .= '<script src="'.SSRC_PID.'testing.js"></script></body></html>';
        return $foot;
    }

    private static $instance = NULL;

    public static function instance(){
        if(defined('_TEST_MODE_')&&_TEST_MODE_&&(self::$instance===NULL)){
            return self::$instance = new Renderer();
        }
    }

    private function __construct(){}

    public function renderTestResult($testname, $cache, $testcount){
        ob_get_clean();
        $time = microtime(true);
        $mgpu = memory_get_peak_usage() / 1048576;
        $btt = (NIKTIME - BOOTTIME) * 1000;
        $kmt = (APPTIME - NIKTIME) * 1000;
        $sat = ($time - APPTIME) * 1000;
        header(HTTP.' 777 YangRAM Unit Test');
        header(sprintf("Content-Type: %s;charset=%s", 'text/html', 'UTF-8'));
        header(sprintf('%s: %.2f ms', 'Test-Use-Time',  $time - BOOTTIME));
        header(sprintf('%s: %.2f Mb', 'Test-Use-Memory', $mgpu));

        $use = sprintf('Use Time: %.2f ms (B) + %.2f ms (C) + %.2f ms (A)</span><span class="use-mmry">Peak Use Memory: %.2f Mb', $btt, $kmt, $sat, $mgpu);

        $GLOBALS['NEWIDEA']->log('tests', date('H:i:s', $_SERVER['REQUEST_TIME'])."\t".str_replace('</span><span class="use-mmry">', "\t", str_replace('+', "\t", str_replace(':', ":\t", $use))).PHP_EOL);

        echo self::getTestHead($testname);
        echo '<body><header>YangRAM Unit Test: ' . $testname.'</header>';
        echo self::getTestSections($cache);
        echo self::getTestFooter($testcount, $use);
        exit;
    }
}