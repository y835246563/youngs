<?php

namespace Youngs\Core;

/**
 * @Author youngshuo@qq.com
 * @Create Time:   2018-08-15 12:33:23
 * @Description:
 */
use Youngs\Core\YsView;
use Youngs\Youngs;
class YsController {

    public function __construct() {
        
    }

    public function view($viewName, $data = null) {
        $view = new YsView;
        $view->render($viewName, $data);
    }
    
    
}