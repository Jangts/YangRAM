<?php
namespace Installer\Controllers;

use Status;
use Tangram\NIDO\RemoteData ;
use RDO;

class Starter extends Common {
    private $record, $error;
    public function l($l){
        $post = $this->request->FORM->toArray();
        if(defined('L')&&$l===L){
            if(is_file($this->record = AP_CURR.'Data/InstallationStatus.ni')){
                $record = file_get_contents($this->record);
                if(isset($_GET['segment_type'])){
                    switch($_GET['segment_type']){
                        case 'inpus_of_mysql':
                        case 'inpus_of_access':
                        case 'inpus_of_cubrid':
                        case 'inpus_of_db2':
                        case 'inpus_of_firebird':
                        case 'inpus_of_informix':
                        case 'inpus_of_oracle':
                        case 'inpus_of_postgresql':
                        case 'inpus_of_sqlite':
                        case 'inpus_of_sqlite2':
                        case 'inpus_of_sqlserver':
                        case 'inpus_of_mssqlserver':
                        case 'inpus_of_sybase':
                        if($record=='2'){
                            return $this->inputs();
                        }

                        case 'check_db_write':
                        if(is_file($this->record)&&in_array(file_get_contents($this->record), array('3', '5', '7', '40', '70', '100'))){
                        return $this->checkWriting();
                        }
                        die('0');

                        case 'import_data_into_database':
                        return $this->importDB($_GET['dbdriver']);

                        case 'import_appdata_into_database':
                        return $this->importAppDB($_GET['dbdriver']);
                    }
                }elseif(isset($post['step'])){
                    switch($post['step']){
                        case -1:
                        return $this->thanks();

                        case 2:
                        if(in_array($record, array('1', '2', '3'))){
                            return $this->step2();
                        }
                        break;

                        case 3:
                        if($record=='2'){
                            return $this->step3();
                        }
                        if(in_array($record, array('3', '40', '70', '100'))){
                            return $this->step3(true);
                        }
                        break;

                        case 4:
                        if($record=='100'||$record=='4'){
                            return $this->step4();
                        }
                        break;

                        case 5:
                        if($record=='4'){
                            return $this->step5();
                        }
                        if(in_array($record, array('5', '6', '7'))){
                            return $this->step5(true);
                        }
                        break;
                    }
                }elseif($record<3){
                    return $this->step1();
                }
            }else{
                return $this->step1();
            }
            return new Status(404, true);//Status::notFound();
        }
    }

    private function step1(){
        global $RUNTIME;
        $template = AP_CURR.'Views/FirstInstallStep.php';
        if(is_file($template)){
            $clause = file_get_contents(AP_CURR.'Views/'.$RUNTIME->LANGUAGE.'/CLAUSE.htm');
            file_put_contents($this->record, '1');
            Response::instance()->sendHeaders();
            include $template;
        }else{
            # 701
        }
    }

    private function thanks(){
        global $RUNTIME;
        $template = AP_CURR.'Views/StopInstallation.php';
        if(is_file($template)){
            unlink($this->record);
            Response::instance()->sendHeaders();
            include $template;
        }else{
            # 701
        }
    }

    private function step2(){
        global $RUNTIME;
        $template = AP_CURR.'Views/SecondInstallStep.php';
        if(is_file($template)){
            file_put_contents($this->record, '2');
            Response::instance()->sendHeaders();
            include $template;
        }else{
            # 701
        }
    }

    private function inputs(){
        global $RUNTIME;
        $filename = AP_CURR.'Views/inputs/'.$_GET['segment_type'].'.php';
        if(is_file($filename)){
            echo file_get_contents($filename);
        }else{
            echo '';
        }
    }

    private function step3($refresh=false){
        global $RUNTIME;
        if($refresh){
            $template = AP_CURR.'Views/ThirdInstallStep.php';
            if(is_file($template)){
                Response::instance()->sendHeaders();
                include $template;
            }
        }else{
            $filename = AP_CURR.'Configurations/KernelCONF';
            if(is_file($filename)){
                $config = json_decode(file_get_contents($filename));
                $connection = $post;
                $config->constants->_DBPRE_ = $post['_DBPRE_'];
                $config->constants->_DOMAIN_ = _DOMAIN_;
                unset($connection['step']);
                unset($connection['_DBPRE_']);
                if($this->checkConn($connection)){
                    $configuration = PATH_TNI.'configuration.ni';
                    $contents = json_encode(array('constants' => $config->constants, 'connections' => array($connection)));
                    file_put_contents($configuration, $contents);
                    $template = AP_CURR.'Views/ThirdInstallStep.php';
                    if(is_file($template)){
                        file_put_contents($this->record, '3');
                        Response::instance()->sendHeaders();
                        include $template;
                        $rdata = new RemoteData('/', array(
                            'segment_type'  =>  'import_data_into_database',
                            'dbdriver'      =>  $connection['driver']
                        ));
                        $rdata->setAgent(RemoteData::UA_MOZ_WIN)->setTimeout(1)->read();
                    }else{
                        # 701
                    }
                }else{
                    # connection_failed
                    echo 'bar';
                }
            }else{
            # 701
            }
        }
    }

    private function checkConn($options){
        if(is_array($options)&&$options['driver']&&is_file(PATH_TNI.'DBAL/Drivers/'.$options['driver'].'.php')){
            include_once(PATH_TNI.'DBAL/Drivers/'.$options['driver'].'.php');
			$class = 'Tangram\DBAL\Drivers\\'.$options['driver'];
            return $class::instance($options);
        }
        return false;
    }

    private function importDB($driver){
        if(is_file($this->record)||file_get_contents($this->record)==='3'){
            ignore_user_abort(true);
            set_time_limit(0);
            file_put_contents($this->record, '10');
            $pdox = RDO::getPDOX();
            $pdox->beginTransaction();
            $filename = AP_CURR.'Data/'.$driver.'/sys.ni';
            if($this->importDBSegment($pdox, $filename)){
                file_put_contents($this->record, '40');
                $filename = AP_CURR.'Data/'.$driver.'/reg.ni';
                if($this->importDBSegment($pdox, $filename)){
                    file_put_contents($this->record, '70');
                    $filename = AP_CURR.'Data/'.$driver.'/cmf.ni';
                    if($this->importDBSegment($pdox, $filename)){
                        file_put_contents($this->record, '100');
                        $pdox->commit();
                        exit;
                    }else{
                        $pdox->rollBack();
                        #
                    }
                }else{
                    $pdox->rollBack();
                   #
                }
            }else{
                $pdox->rollBack();
                #
            }
        }else{
            #
        }
    }

    private function importDBSegment($pdox, $filename){
        if(is_file($filename)){
            $txt = file_get_contents($filename);
            $sql = str_replace('<%_dbp_%>', _DBPRE_.'_', $txt);
            if($pdox->exec($sql)!==false){
                return true;
            }else{
                #
            }
        }
        return false;
    }

    private function checkWriting(){
        $value = file_get_contents($this->record);
        if($value=='7'){
            $filename = AP_CURR.'Configurations/BeforeBOOT';
            if(is_file($filename)){
                if(file_put_contents(ROOT.'/.BEFOREBOOT', file_get_contents($filename))){
                    echo 7;
                }else{
                    # Unkonw
                    echo '0';
                }
            }else{
                # 701
                echo '0';
            }
        }else{
             echo $value;
        }
    }

    private function step4(){
        global $RUNTIME;
        $template = AP_CURR.'Views/FourthInstallStep.php';
        if(is_file($template)){
            file_put_contents($this->record, '4');
            Response::instance()->sendHeaders();
            $ntvOICode = strtoupper(uniqid());
            include $template;
        }else{
            # 701
        }
    }

    private function step5($refresh=false){
        global $RUNTIME;
        if($refresh){
            $template = AP_CURR.'Views/FifthInstallStep.php';
            if(is_file($template)){
                Response::instance()->sendHeaders();
                $ntvOICode = strtoupper(uniqid());
                include $template;
            }
        }else{
            $filename = PATH_TNI.'configuration.ni';
            $configuration = json_decode(file_get_contents($filename));
            $configuration->constants->_OWNER_ = $post['_OWNER_'];
            $configuration->constants->_DOMAIN_ = $post['_DOMAIN_'];
            $configuration->constants->_LANG_ = $post['_LANG_'];
            $configuration->constants->_SPACE_ = $post['_SPACE_'] * 1024 * 1024;
            $configuration->constants->_HOME_ = $post['_HOME_'];
            $configuration->constants->_DOI_TOKEN_ = $post['_DOI_TOKEN_'];
            $configuration->constants->_WEBUOI_ENABLE_ = $post['_WEBUOI_ENABLE_'];
            $configuration->constants->_TASKER_ENABLE_ = 1;
            file_put_contents($filename, json_encode($configuration));
            $template = AP_CURR.'Views/FifthInstallStep.php';
            if(is_file($template)){
                file_put_contents($this->record, '5');
                $ntvOICode = strtoupper(uniqid());
                Response::instance()->sendHeaders();
                include $template;
                $rdata = new RemoteData('/', array(
                    'segment_type'  =>  'import_appdata_into_database',
                    'dbdriver'      =>  $configuration->connections[0]->driver
                ));
                $rdata->setAgent(RemoteData::UA_MOZ_WIN)->setTimeout(1)->read();
            }else{
                # 701
            }
        }
    }

    private function importAppDB($driver){
        if(is_file($this->record)||file_get_contents($this->record)==='5'){
            ignore_user_abort(true);
            set_time_limit(0);
            file_put_contents($this->record, '6');
            $pdox = RDO::getPDOX();
            $filename = AP_CURR.'Data/'.$driver.'/app.ni';
            if($this->importDBSegment($pdox, $filename)){
                file_put_contents($this->record, '7');
            }else{
                #
            }
        }else{
            #
        }
    }
}