<?php

namespace Youngs\Core;

/**
 * @Author youngshuo@qq.com
 * @Create Time:   2018-08-16 14:49:35
 * @Description:
 */
class YsView {

//    public $data = [];

    function __construct() {
        
    }

    public function render($viewName, $data = []) {
//        extract($this->data);
        if (is_array($data)) {
            extract($data);
        }
        $filename = SITE_VIEW_PATH . $viewName . '.php';
        if (is_file($filename)) {
            include $filename;
        }
    }

}