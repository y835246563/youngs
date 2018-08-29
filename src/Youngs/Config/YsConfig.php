<?php

namespace Youngs\Config;

/**
 * @Author youngshuo@qq.com
 * @Create Time:   2018-08-29 15:57:07
 * @Description:
 */

class YsConfig {

    public static function getYsConfig() {
        $ysConfig['modulesArr'] = array(
            'request' => \Youngs\Core\Request::class,
            'route' => \Youngs\Core\Route::class,
        );
        return $ysConfig;
    }

}
