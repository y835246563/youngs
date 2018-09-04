<?php

namespace Youngs\Core;

/**
 * @Author youngshuo@qq.com
 * @Create Time:   2018-08-17 11:01:19
 * @Description:
 */
use Youngs\Youngs;

class Request {

    privateUri;
    privatePath;
    privateParams = [];

//    privateInfo;
//    privateFile;
//    privateUrl;
//    privateInfo;
//    privateUrl;
//    private;
//    privateAcceptTypes;
//    privateLanguages;
//    privateToken;
//    privateParams;

    public function all($name = null) {
        switch ($name) {
            case 'get':
                return $_GET;
                break;

            case 'post':
                return $_POST;
                break;

            case 'all':
                return array_merge($_GET, $_POST, $thisParams);
                break;

            default:
                return array_merge($_GET, $_POST);
                break;
        }
    }

    public function get($name, $defaultValue = null) {
        return isset($_GET[$name]) ? $_GET[$name] : $defaultValue;
    }

    public function post($name, $defaultValue = null) {
        return isset($_POST[$name]) ? $_POST[$name] : $defaultValue;
    }

    public function any($name, $defaultValue = null) {
        return isset($thisParams[$name]) ? $thisParams[$name] : (isset($_POST[$name]) ? $_POST[$name] : (isset($_GET[$name]) ? $_GET[$name] : $defaultValue));
//        return isset($_POST[$name]) ? $_POST[$name] : (isset($_GET[$name]) ? $_GET[$name] : $defaultValue);
    }

    public function getPathParam($name, $defaultValue = null) {
        return isset($thisParams[$name]) ? $thisParams[$name] : $defaultValue;
    }

    public function getUri() {
        if ($thisUri === null) {
            if (isset($_SERVER['REQUEST_URI'])) {
                $thisUri = $_SERVER['REQUEST_URI'];
            } else {
                $thisUri = "can't get REQUEST_URI,\$_SERVER['REQUEST_URI'] is not set,please check this method";
            }
        }

        return $thisUri;
    }

    public function getBaseUrl() {
//        if (isset($_SERVER['HTTP_HOST'])) {
//            $thisUrl = '';
//        }
//        return $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function getUrlPath() {
        if ($thisPath === null) {
            $uri = $this->getUri();
            $path = explode('?', $uri, 2)[0];
            $path = str_replace('\\', '/', $path);
            $path = preg_replace('/\/+/', '/', $path);
            $thisPath = trim($path, '/');
        }
        return $thisPath;
    }

    public function getUrlInfo($types = ['uri', 'baseUrl', 'urlPath']) {
        if (is_string($types)) {
            $method = 'get' . ucfirst($types);
            if (method_exists($this, $method)) {
                $urlInfo = $this->$method();
            } else {
                $urlInfo = "this message can't get, no method to get it ";
            }
        } elseif (is_array($types)) {
            $urlInfo = [];
            foreach ($types as $type) {
                $method = 'get' . ucfirst($type);
                if (method_exists($this, $method)) {
                    $urlInfo[$type] = $this->$method();
                } else {
                    $urlInfo[$type] = "this message can't get, no method to get it ";
                }
            }
        }
        return $urlInfo;
    }

    public function setPathParams($params) {
        if (is_array($params))
            $thisParams = $params;
    }

}
