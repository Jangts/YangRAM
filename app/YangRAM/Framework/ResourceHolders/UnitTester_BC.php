<?php
namespace AF\ResourceHolders;

use Status;
use Tangram\ClassLoader;
use Tangram\NIDO\DataObject;
use Request;
use AF\Controllers\UnitTestResultsRenderer;
use AF\Models\Certificates\StdPassport;
use Application;

/**
 *	Uniform Tester Router Abstract
 *	统一测试员端路由器抽象
 *  子应用测试员端（如果有）路由器的基类
 */
abstract class UnitTester_BC extends \Tangram\R5\NI_ResourceHolder_BC {
    protected
    $renderer = NULL,
    $testname = 'Unnamed Test',
    $testcount = 1,
    $classalias = NULL,
	$methodoptions = NULL,
	$controllers = [],
    $test_type = 'Customer',
    $cache = [],
    $arguments = [];

    public function record($var){
        $this->cache[] = $var;
        return $this;
    }

    final public function __construct(Application $app, Request $request, Renderer $renderer){
        $this->renderer = $renderer;
        if(isset($request->uri_path[3])){
            switch ($request->uri_path[3]) {
                case 'm':
                $this->test_type = 'Modol';
                return $this->testModel($app, $request);

                case 's':
                $this->test_type = 'SQLModol';
                return $this->testRow($app, $request);

                case 'v':
                $this->test_type = 'View';
                return $this->testView($app, $request);

                case 'c':
                $this->test_type = 'Controller';
                return $this->testController($app, $request);

                default:
                $this->test_type = 'Customer';
                return $this->testCustomer($app, $request);
            }
        }else{
            $test_type = 'Batch';
            $methods = get_class_methods ($this);
            $n = 0;
            foreach ($methods as $methodname) {
                if(strpos($methodname, 'myTest') === 0){
                    $n++;
                    $this->record('# SUBTEST ' . DataObject::decToRoman($n) . ' call ' . $methodname);
                    $this->record($this->$methodname(true, $app, $request));
                }
            }
            $this->testcount = $n;
            $this->testname = 'Batch Testing Of '.$n. ' ResourceHolders';
        }
        $this->renderer->renderTestResult($this->testname, $this->cache, $this->testcount);
    }
    
    final private function testModel(Request $request, Application $app){

    }

    final private function testRow(Request $request, Application $app){
        
    }


    final private function testView(Request $request, Application $app){
        
    }

    final private function testController(Request $request, Application $app){
        if(isset($request->uri_path[4])){
            $classalias = isset($this->classnames[$request->uri_path[4]]) ? $this->classnames[$request->uri_path[4]] : $request->uri_path[4];
            $classname = '\\'.$app->Props['Namespace'].'\\Controllers\\'.$classalias;
            $filename = $app->Path.'Controllers/'.$classalias;
            ClassLoader::execute($filename);
            if(class_exists($classname)){
                $controller = new $classname($app, $request);
                $methodname = $request->PARAMS->m;
                if(!$methodname){
                    $methodname = 'main';
                }
                if(method_exists($controller, $methodname)){
                    $arguments = $this->getParameters($request);
                    @$this->record(call_user_func_array([$controller, $methodname], $arguments));
                }else{
                    $this->record('Controller method not found');
                }
            }else{
                $this->record('Controller not found');
            }
        }else{
            $this->record('Controller alias unspecified');
        }
        $this->testname = 'Test For Controller '.$classname;
        $this->renderer->renderTestResult($this->testname, $this->cache, $this->testcount);
    }

    final protected function getParameters(Request $request){
        if(is_string($request->PARAMS->_a)){
            return explode('/', $request->PARAMS->_a);
        }
        if(is_string($request->PARAMS->_t)){
            if(is_array($this->arguments[$request->PARAMS->_t])){
                return $this->arguments[$request->PARAMS->_t];
            }
        }
        if(is_string($request->PARAMS->_f)){
            if(is_file(PATH_CAC_TEST.$request->PARAMS->_f.'.json')){
                return json_decode(file_get_contents(PATH_CAC_TEST.$request->PARAMS->_f.'.json'), true);
            }
        }
        return [];
    }

    final private function testCustomer(Request $request, Application $app){
        $methodname = 'myTest'.$request->uri_path[3];
        if(method_exists($this, $methodname)){
            $this->record($this->$methodname(false, $app, $request));
        }else{
            $this->record('No Such Testing!');
        }
        $this->renderer->renderTestResult($this->testname, $this->cache, $this->testcount);
    }
}
