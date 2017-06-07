<?php
namespace Library\graphics;
use Status;
use Library\graphics\QRCode\Input;
use Library\graphics\QRCode\RawCode;
use Library\graphics\QRCode\Tools;
use Library\graphics\QRCode\Spec;
use Library\graphics\QRCode\FrameFiller;
use Library\graphics\QRCode\Mask;
use Library\graphics\QRCode\Encode;

// Encoding modes
 
define('QR_MODE_NUL', -1);
define('QR_MODE_NUM', 0);
define('QR_MODE_AN', 1);
define('QR_MODE_8', 2);
define('QR_MODE_KANJI', 3);
define('QR_MODE_STRUCTURE', 4);

// Levels of error correction.

define('QR_ECLEVEL_L', 0);
define('QR_ECLEVEL_M', 1);
define('QR_ECLEVEL_Q', 2);
define('QR_ECLEVEL_H', 3);

// Supported output formats

define('QR_FORMAT_TEXT', 0);
define('QR_FORMAT_PNG',  1);

define('QR_CACHEABLE', false);          // use cache - more disk reads but less CPU power, masks and format templates are stored there
define('QR_CACHE_DIR', false);          // used when QR_CACHEABLE === true
define('QR_LOG_DIR', false);            // default error logs dir     
define('QR_FIND_BEST_MASK', true);      // if true, estimates best mask (spec. default, but extremally slow; set to false to significant performance boost but (propably) worst quality code
define('QR_FIND_FROM_RANDOM', 2);       // if false, checks all masks available, otherwise value tells count of masks need to be checked, mask id are got randomly
define('QR_DEFAULT_MASK', 2);           // when QR_FIND_BEST_MASK === false                                                
define('QR_PNG_MAXIMUM_SIZE',  1024);   // maximum allowed png image width (in pixels), tune to make sure GD and PHP can handle such big images

class QRCode {
    public $version;
    public $width;
    public $data; 
    
    public function encodeMask(Input $input, $mask){
        if($input->getVersion() < 0 || $input->getVersion() > SPEC_VERSION_MAX){
            new Status('wrong version', true);
        }
        if($input->getErrorCorrectionLevel() > QR_ECLEVEL_H){
            new Status('wrong level', true);
        }

        $raw = new RawCode($input);
        
        Tools::markTime('after_raw');
        
        $version = $raw->version;
        $width = Spec::getWidth($version);
        $frame = Spec::newFrame($version);
        
        $filler = new FrameFiller($width, $frame);
        if(is_null($filler)){
            return NULL;
        }

        // inteleaved data and ecc codes
        for($i=0; $i<$raw->dataLength + $raw->eccLength; $i++){
            $code = $raw->getCode();
            $bit = 0x80;
            for($j=0; $j<8; $j++){
                $addr = $filler->next();
                $filler->setFrameAt($addr, 0x02 | (($bit & $code) != 0));
                $bit = $bit >> 1;
            }
        }
        
        Tools::markTime('after_filler');
        
        unset($raw);
        
        // remainder bits
        $j = Spec::getRemainder($version);
        for($i=0; $i<$j; $i++){
            $addr = $filler->next();
            $filler->setFrameAt($addr, 0x02);
        }
        
        $frame = $filler->frame;
        unset($filler);
        
        
        // masking
        $maskObj = new Mask();
        if($mask < 0){
        
            if (QR_FIND_BEST_MASK){
                $masked = $maskObj->mask($width, $frame, $input->getErrorCorrectionLevel());
            } else {
                $masked = $maskObj->makeMask($width, $frame, (intval(QR_DEFAULT_MASK) % 8), $input->getErrorCorrectionLevel());
            }
        } else {
            $masked = $maskObj->makeMask($width, $frame, $mask, $input->getErrorCorrectionLevel());
        }
        
        if($masked == NULL){
            return NULL;
        }
        
        Tools::markTime('after_mask');
        
        $this->version = $version;
        $this->width = $width;
        $this->data = $masked;
        
        return $this;
    }
    
    public function encodeInput(Input $input){
        return $this->encodeMask($input, -1);
    }
    
    public function encodeString8bit($string, $version, $level){
        if(string == NULL){
            throw new Exception('empty string!');
            return NULL;
        }

        $input = new Input($version, $level);
        if($input == NULL) return NULL;

        $ret = $input->append($input, QR_MODE_8, strlen($string), str_split($string));
        if($ret < 0){
            unset($input);
            return NULL;
        }
        return $this->encodeInput($input);
    }

    public function encodeString($string, $version, $level, $hint, $casesensitive){

        if($hint != QR_MODE_8 && $hint != QR_MODE_KANJI){
            throw new Exception('bad hint');
            return NULL;
        }

        $input = new Input($version, $level);
        if($input == NULL) return NULL;

        $ret = Split::splitStringToInput($string, $input, $hint, $casesensitive);
        if($ret < 0){
            return NULL;
        }

        return $this->encodeInput($input);
    }
    
    public static function png($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4, $saveandprint=false){
        $enc = Encode::factory($level, $size, $margin);
        return $enc->encodePNG($text, $outfile, $saveandprint=false);
    }

    public static function text($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4){
        $enc = Encode::factory($level, $size, $margin);
        return $enc->encode($text, $outfile);
    }

    public static function raw($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4){
        $enc = Encode::factory($level, $size, $margin);
        return $enc->encodeRAW($text, $outfile);
    }

    public static function set(&$srctab, $x, $y, $repl, $replLen = false) {
        $srctab[$y] = substr_replace($srctab[$y], ($replLen !== false)?substr($repl,0,$replLen):$repl, $x, ($replLen !== false)?$replLen:strlen($repl));
    }
}