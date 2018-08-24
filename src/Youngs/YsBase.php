<?php

namespace Youngs;

/**
 * @Author youngshuo@qq.com
 * @Create Time:   2018-08-15 11:21:25
 * @Description:
 */
use Youngs\Core\Route;
use Youngs\Core\Request;
use Youngs\Youngs;

abstract class YsBase {

    public $config = [];
    public $request = '';

    function __construct($config) {
        Youngs::setApp($this);
        $this->autoLoadFile($config);
    }

    /**
     * 
     * @param type $config
     */
    abstract public function autoLoadFile($config);

    public function setmodule($name, $class) {
        $this->$name = new $class();
    }

    public function setModules($modulesArr) {
        foreach ($modulesArr as $moduleName => $className) {
            $this->setmodule($moduleName, $className);
        }
    }

    /**
     * 获取所有类文件
     * @param type $path
     * @return type
     */
    public function get_all_files($path = SITE_CLASS_PATH) {
        $list = [];
        $path = trim($path, '/');
        $fileArr = glob($path . '/*');
        foreach ($fileArr as $filename) {
            if (is_dir($filename)) {
                $list = array_merge($list, self::get_all_files($filename));
            } else {
                $list[] = $filename;
            }
        }
        return $list;
    }

    /**
     * 
     */
    public function loadFiles($fileArr) {
        $result = false;
        if (is_array($fileArr)) {
            $reArr = [];
            foreach ($fileArr as $filename) {
                if ($this->loadFile($filename) === false)
                    $reArr[] = $filename;
            }
            $result = true;
        }
        $result = $result ? (empty($reArr) ? $result : $reArr) : $result;
        return $result;
    }

    /**
     * 
     * @param type $filename
     * @return boolean
     */
    public function loadFile($filename) {
        if (is_file($filename)) {
            include $filename;
        } else {
            return false;
        }
        return true;
    }

}