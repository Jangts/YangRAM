<?php
namespace AF\Controllers;

/**
 *	Common Application Data Submitter
 *	通用应用数据提交器
 *  用于提交数据的控制器抽象，提供了提交器的基本属性和方法
 */
abstract class Submitter_BC extends Controller_BC {
    protected
    $rdo = NULL,
    $table = NULL,
    $lastInsertId = 0,
    $fields = ['' => 0, true];

    protected function conn(){
        $this->rdo = new RDO;
        $this->rdo->useTables($this->table);
    }

    public function __construct(){
        $this->request = $request;
        $this->app = $app;
        $this->conn();
    }

    public function post($data){

    }

    public function lastInsertId(){

    }

    protected function fill_a_new_form($data){

    }

    public function put($data, $origin = false){

    }

    protected function filter_a_update_form($data){

    }

    protected function diff_a_update_form($data, $origin){

    }
}