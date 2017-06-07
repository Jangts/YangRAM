<?php
namespace Packages\tplengines;

require_once('Smarty/Smarty.class.php');

class Smarty extends \Smarty {
    protected
    $template_dir = array('./templates/'),
    $config_dir = array('./configs/'),
    $compile_dir = './templates_c/',
    $cache_dir = './cache/';

    public
    $force_cache = false,
    $left_delimiter = "{",
    $right_delimiter = "}",
    $security_class = 'Smarty_Security';
}
