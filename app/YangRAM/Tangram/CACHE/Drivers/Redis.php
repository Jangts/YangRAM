<?php
namespace Tangram\CACHE\Drivers;

class Redis {
    private static $instance = null;

    public static function init(){
        if(self::$instance===null){
            self::$instance = new self();
            return self::$instance;
        }
        return false;
    }

    private $handler;

    private function __construct(){}

    public function connect($options){
        $handler = new Redis();
    }
}