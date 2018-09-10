<?php

namespace Youngs;

/**
 * @Author youngshuo@qq.com
 * @Create Time:2018-08-14 15:10:01
 * @Description:
 */
define('YS_PATH', __DIR__ . '/');
require YS_PATH . 'YsWeb.php';

class Youngs {

    protected static $app;

    public static function app($module = null) {
        if (null !== $module) {
            return $this->app->$module;
        }
        return $this->app;
    }

    public static function setApp($app) {
        if (empty($this->app)) {
            $this->app = $app;
        } else {
            echo 'app only can set once';
        }
    }

    /**
     * 设置web应用
     * @param type $config
     * @return type
     */
    public function setYsWeb($config = null) {
        return $this->newApp('Youngs\YsWeb', $config);
    }

    /**
     * 实例化应用
     * @param type $class
     * @param type $config
     * @return \Youngs\class
     */
    public function newApp($class, $config = null) {
        return new $class($config);
    }

}
