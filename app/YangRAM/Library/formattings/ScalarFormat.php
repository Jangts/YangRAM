<?php
namespace Library\formattings;

class ScalarFormat {    
    public static function subString($str, $len = null, $start = 0) {
        $len = $len or mb_strlen($str) - $start;
        $result = mb_substr($str, $start, $len, 'utf-8');
        if ($len < mb_strlen($str, 'utf-8')) {
            $result .= '...';
        }
        return $result;
    }
    
    public static function uprString($str) {
        $arr = str_split($str, 1);
        $result = '';
        foreach($arr as $letter) {
            $letter = ord($letter);
            if ($letter >= 97 && $letter <= 122) {
                $letter -= 32;
            }
            $result .= chr($letter);
        }
        return $result;
    }
    
    public static function lwrString($str) {
        $arr = str_split($str, 1);
        $result = '';
        foreach($arr as $letter) {
            $letter = ord($letter);
            if ($letter >= 65 && $letter <= 90) {
                $letter += 32;
            }
            $result .= chr($letter);
        }
        return $result;
    }
    
    public static function ucVar($str){
        $str = trim($str);
        $str = str_replace('_', ' ', $str);
        $str = ucwords($str);
        return str_replace(' ', '_', $str);
    }
    
    public static function chrEscape($str) {
        preg_match_all("/[\x80-\xff].|[\x01-\x7f]+/", $str, $newstr);
        $ar = $newstr[0];
        foreach($ar as $k => $v) {
            if (ord($ar[$k]) >= 127) {
                $tmpString = bin2hex(iconv("UTF-8", "ucs-2", $v));
                if (!eregi("WIN", PHP_OS)) {
                    $tmpString = substr($tmpString, 2, 2).substr($tmpString, 0, 2);
                }
                $reString .= "%u".$tmpString;
            } else {
                $reString .= rawurlencode($v);
            }
        }
        return $reString;
    }
    
    public static function chrUnescape($str, $type = 1) {
        if ($type === 1) {
            $str = rawurldecode($str);
            preg_match_all("/%u.{4}|&#x.{4};|&#\d+;|.+/U", $str, $r);
            $ar = $r[0];
            foreach($ar as $k => $v) {
                if (substr($v, 0, 2) == "%u") {
                    $ar[$k] = iconv("UCS-2", "UTF-8", pack("H4", substr($v, -4)));
                }
                elseif(substr($v, 0, 3) == "&#x") {
                    $ar[$k] = iconv("UCS-2", "UTF-8", pack("H4", substr($v, 3, -1)));
                }
                elseif(substr($v, 0, 2) == "&#") {
                    $ar[$k] = iconv("UCS-2", "UTF-8", pack("n", substr($v, 2, -1)));
                }
            }
            return join("", $ar);
            
        }
        elseif($type === 2) {
            $decodedStr = "";
            $pos = 0;
            $len = strlen($str);
            while ($pos < $len) {
                $charAt = substr($str, $pos, 1);
                if ($charAt == '%') {
                    $pos++;
                    $charAt = substr($str, $pos, 1);
                    if ($charAt == 'u') {
                        // we got a unicode character
                        $pos++;
                        $unicodeHexVal = substr($str, $pos, 4);
                        $unicode = hexdec($unicodeHexVal);
                        $entity = "&#".$unicode.';';
                        $decodedStr .= utf8_encode($entity);
                        $pos += 4;
                    } else {
                        // we have an escaped ascii character
                        $hexVal = substr($str, $pos, 2);
                        $decodedStr .= chr(hexdec($hexVal));
                        $pos += 2;
                    }
                } else {
                    $decodedStr .= $charAt;
                    $pos++;
                }
            }
            return $decodedStr;
        } else if ($type === 3) {
            preg_match_all("/%u[0-9A-Za-z]{4}|%.{2}|[0-9a-zA-Z.+-_]+/", $str, $matches);
            $ar = &$matches[0];
            $c = "";
            foreach($ar as $val) {
                if (substr($val, 0, 1) != "%") {
                    //如果是字母数字+-_.的ascii码
                    $c .= $val;
                }
                elseif(substr($val, 1, 1) != "u") {
                    //如果是非字母数字+-_.的ascii码
                    $x = hexdec(substr($val, 1, 2));
                    $c .= chr($x);
                } else {
                    //如果是大于0xFF的码
                    $val = intval(substr($val, 2), 16);
                    if ($val < 0x7F) {
                        // 0000-007F
                        $c .= chr($val);
                    }
                    elseif($val < 0x800) {
                        // 0080-0800
                        $c .= chr(0xC0 | ($val / 64));
                        $c .= chr(0x80 | ($val % 64));
                    } else {
                        // 0800-FFFF
                        $c .= chr(0xE0 | (($val / 64) / 64));
                        $c .= chr(0x80 | (($val / 64) % 64));
                        $c .= chr(0x80 | ($val % 64));
                    }
                }
            }
            return $c;
        } else if ($type === 4) {
            $ret = '';
            $len = strlen($str);
            for ($i = 0; $i < $len; $i++) {
                if ($str[$i] == '%' && $str[$i + 1] == 'u') {
                    $val = hexdec(substr($str, $i + 2, 4));
                    if ($val < 0x7f) $ret .= chr($val);
                    else if ($val < 0x800) $ret .= chr(0xc0 | ($val >> 6)).chr(0x80 | ($val & 0x3f));
                    else $ret .= chr(0xe0 | ($val >> 12)).chr(0x80 | (($val >> 6) & 0x3f)).chr(0x80 | ($val & 0x3f));
                        $i += 5;
                } else if ($str[$i] == '%') {
                    $ret .= urldecode(substr($str, $i, 3));
                    $i += 2;
                } else {
                    $ret .= $str[$i];
                }
            }
            return $ret;
        }
        return $str;
    }

    public static function fmtSizeUnit($size) {
        if ($size >= 1024 * 1024 * 1204 * 1204) {
            return sprintf("%.2f", $size / (1024 * 1024 * 1204 * 1204)).'T';
        }
        if ($size >= 1024 * 1024 * 1204) {
            return sprintf("%.2f", $size / (1024 * 1024 * 1204)).'G';
        }
        if ($size >= 1024 * 1024) {
            return sprintf("%.2f", $size / (1024 * 1024)).'M';
        }
        if ($size >= 1024) {
            return sprintf("%.2f", $size / (1024)).'K';
        }
        return $size.'B';
    }
    
    public static function fmtTimeDuration($dura) {
        if ($dura > 60 * 60) {
            $h = $dura / 3600;
            $m = ($dura % 3600) / 60 > 9 ? ($dura % 3600) / 60 : '0'. (($dura % 3600) / 60);
            $s = ($dura % 3600) % 60 > 9 ? ($dura % 3600) % 60 : '0'. (($dura % 3600) % 60);
            return $h.':'.$m.':'.$s;
        }
        if ($dura > 60) {
            $m = $dura / 60;
            $s = $dura % 60 > 9 ? $dura % 60 : '0'. ($dura % 60);
            return $m.':'.$s;
        }
        if ($dura > 0) {
            return $dura.'s';
        }
        return '-';
    }
}