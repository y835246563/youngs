<?php

namespace Youngs;

/**
 * @Author youngshuo@qq.com
 * @Create Time:   2018-08-15 11:21:25
 * @Description:
 */
use Youngs\Youngs;

abstract class YsBase {

    public $config = [];

    function __construct($customConfig) {
        Youngs::setApp($this);
        $config = array_merge(config\YsConfig::getYsConfig(), $customConfig);
        $this->autoLoadFile($config);
    }

    
    function __get($param) {
        if (isset($this->$param) === false) {
            if (isset($this->config['modulesArr'][$param])) {
                $class = $this->config['modulesArr'][$param];
            } else {
                echo $param . " dont exist ";
            }
            $this->setModule($param, $class);
        }
        return $this->$param;
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
        $filepath = trim($path, '/');
        $fileArr = glob($filepath . '/*');
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
        $loadFlag = false;
        if (is_array($fileArr)) {
            $reArr = [];
            foreach ($fileArr as $filename) {
                if ($this->loadFile($filename) === false) {
                    $reArr[] = $filename;
                }
            }
            $loadFlag = true;
        }
        if ($loadFlag === false || empty($reArr) == true) {
            $result = $loadFlag;
        } else {
            $result = $reArr;
        }
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
