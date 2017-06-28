<?php

trait methods {
    use methods;
    
    final protected static function checkRequestToken(Request $request, $tokenname){
        if(_TASKER_ENABLE_){
            $addr = $request->ADDR;
            $args = $request->PARAMS;
            if($addr['FROM']===$addr['TO']){
                if(isset($args->$tokenname)){
                    if(self::$storage->setBefore('tokens/')->check($args->$tokenname)){
                        self::$storage->store($args->$tokenname);
                        return true;
                    }
                }   
            }
        }
        return false;
    }
}