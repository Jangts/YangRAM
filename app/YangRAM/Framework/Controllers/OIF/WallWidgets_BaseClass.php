<?php
namespace OIC;
use Response;
use Library\graphics\ImagePrinter;

abstract class WallWidgets_BaseClass extends \AF\Controllers\BaseCtrller {
    protected function get_dynamic_image_source(){
        return 'dynamic.png';
    }
    
    final public function dynamic_image(){
        $imgsrc = $this->app->Path.'Sources/'.$this->get_dynamic_image_source();
        if(is_file($imgsrc)){
			ImagePrinter::PNG($imgsrc);
		}else{
			ImagePrinter::PNG(PATH_I4S.'Sources/dynamic.png');
		}
    }

    final public function renderHTML($html){
        Response::instance(200, Response::HTML)->send($html);
    }

    public function messages(){
        $this->renderHTML('<body style="margin:0; line-height:118px; text-align: center; font-size:13px; color:white;"><p>该应用并未发送任何消息</p></body>');
    }

    public function embed(){
        $this->renderHTML('<body style="margin:0; line-height:234px; text-align: center; font-size:13px; color:white;"><p>该应用并未提供任何数据</p></body>');
    }
}