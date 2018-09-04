<?php

namespace Youngs\Exceptions;

/**
 * @Author youngshuo@qq.com
 * @Create Time:   2018-09-03 14:54:50
 * @Description:
 */
use Youngs\log\Writer;

class YsException {

    public function show($param) {
        
    }

    public static function write($message) {
        \Youngs\Youngs::app()->log->error($message);
    }

    public static function error_write($errno, $errstr, $errfile, $errline) {
        \Youngs\Youngs::app()->log->error($errstr);
    }

}
