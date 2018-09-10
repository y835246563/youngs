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

    /**
     * will  start modules when the modules not set
     * @param type $param
     * @return type
     */
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

    /**
     * add modules
     * @param type $name
     * @param type $class
     */
    public function setmodule($name, $class) {
        $this->$name = new $class();
    }

    /**
     * add modules
     *  
     * @param type $modulesArr
     */
    public function setModules($modulesArr) {
        foreach ($modulesArr as $moduleName => $className) {
            $this->setmodule($moduleName, $className);
        }
    }

    /**
     * get all files in the path 
     * @param type $path
     * @return type
     */
    public function get_all_files($path = SITE_CLASS_PATH) {
        $list = [];
        $filepath = trim($path, '/');
        $fileArr = glob($filepath . '/*');
        foreach ($fileArr as $filename) {
            if (is_dir($filename)) {
                $list = array_merge($list, $this->get_all_files($filename));
            } else {
                $list[] = $filename;
            }
        }
        return $list;
    }

    /**
     * load files
     * @param type $fileArr
     * @return type
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
     * load the file
     * @param type $filename
     * @return boolean
     */
    public function loadFile($filename) {
        if (is_file($filename)) {
            include_once $filename;
        } else {
            return false;
        }
        return true;
    }

}
