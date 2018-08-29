<?php

namespace Youngs;

/**
 * @Author youngshuo@qq.com
 * @Create Time:2018-08-14 15:10:01
 * @Description:
 */
use Youngs\Core\Route;

define('YS_PATH', __DIR__ . '/');
require YS_PATH . 'YsWeb.php';

class Youngs {

    protected static $_app;

    public static function app($module = null) {
        if ($module !== null) {
            if (isset(self::$_app->$module) === false) {
                $class = $config['modulesArr'][$module];
                self::$_app->setModule($module,$class);
            }
            return self::$_app->$module;
        }
        return self::$_app;
    }

    public static function setApp($app) {
        if (empty(self::$_app)) {
            self::$_app = $app;
        } else {
            echo 'app only can set once';
        }
    }

    public function setYsWeb($config = null) {
        return self::setClass('Youngs\YsWeb', $config);
    }

    public function setClass($class, $config = null) {
        return new $class($config);
    }

}
