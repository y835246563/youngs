<?php
namespace Youngs\Core;

/**
 * @Author youngshuo@qq.com
 * @Create Time:   2018-08-17 11:01:19
 * @Description:
 */
use Youngs\Youngs;

class Request {

    private $_requestUri;
    private $_urlPath;
    private $_pathParams = [];

//    private $_pathInfo;
//    private $_scriptFile;
//    private $_scriptUrl;
//    private $_hostInfo;
//    private $_baseUrl;
//    private $_cookies;
//    private $_preferredAcceptTypes;
//    private $_preferredLanguages;
//    private $_csrfToken;
//    private $_restParams;




    public function all($name = null) {
        switch ($name) {
            case 'get':
                return $_GET;
                break;

            case 'post':
                return $_POST;
                break;

            case 'all':
                return array_merge($_GET, $_POST, $this->_pathParams);
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
        return isset($this->_pathParams[$name]) ? $this->_pathParams[$name] : (isset($_POST[$name]) ? $_POST[$name] : (isset($_GET[$name]) ? $_GET[$name] : $defaultValue));
//        return isset($_POST[$name]) ? $_POST[$name] : (isset($_GET[$name]) ? $_GET[$name] : $defaultValue);
    }

    public function getPathParam($name, $defaultValue = null) {
        return isset($this->_pathParams[$name]) ? $this->_pathParams[$name] : $defaultValue;
    }

    public function getUri() {
        if ($this->_requestUri === null) {
            if (isset($_SERVER['REQUEST_URI'])) {
                $this->_requestUri = $_SERVER['REQUEST_URI'];
            } else {
                $this->_requestUri = "can't get REQUEST_URI,\$_SERVER['REQUEST_URI'] is not set,please check this method";
            }
        }

        return $this->_requestUri;
    }

    public function getBaseUrl() {
//        if (isset($_SERVER['HTTP_HOST'])) {
//            $this->_baseUrl = '';
//        }
//        return $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function getUrlPath() {
        if ($this->_urlPath === null) {
            $uri = $this->getUri();
            $path = explode('?', $uri, 2)[0];
            $path = str_replace('\\', '/', $path);
            $path = preg_replace('/\/+/', '/', $path);
            $this->_urlPath = trim($path, '/');
        }
        return $this->_urlPath;
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
            $this->_pathParams = $param;
    }

}
