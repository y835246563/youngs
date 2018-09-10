<?php

namespace Youngs\Core;

/**
 * @Author youngshuo@qq.com
 * @Create Time:   2018-08-17 11:01:19
 * @Description:
 */
use Youngs\Youngs;

class Request {

    private $requestUri;
    private $urlPath;
    private $pathParams = [];
    private $basUrl;

    public function all($name = null) {
        switch ($name) {
            case 'get':
                return $_GET;
                break;

            case 'post':
                return $_POST;
                break;

            case 'all':
                return array_merge($_GET, $_POST, $this->pathParams);
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
        return isset($this->pathParams[$name]) ? $this->pathParams[$name] : (isset($_POST[$name]) ? $_POST[$name] : (isset($_GET[$name]) ? $_GET[$name] : $defaultValue));
//        return isset($_POST[$name]) ? $_POST[$name] : (isset($_GET[$name]) ? $_GET[$name] : $defaultValue);
    }

    public function getPathParam($name, $defaultValue = null) {
        return isset($this->pathParams[$name]) ? $this->pathParams[$name] : $defaultValue;
    }

    public function getUri() {
        if ($this->requestUri === null) {
            if (isset($_SERVER['REQUEST_URI'])) {
                $this->requestUri = $_SERVER['REQUEST_URI'];
            } else {
                $this->requestUri = "can't get REQUEST_URI,\$_SERVER['REQUEST_URI'] is not set,please check this method";
            }
        }

        return $this->requestUri;
    }

    public function getBaseUrl() {

        if (null === $this->basUrl) {
            if (isset($_SERVER['HTTP_HOST'])) {
                $this->baseUrl = $_SERVER['HTTP_HOST'];
            }
        }
        return $this->baseUrl;
    }

    public function getUrlPath() {
        if ($this->urlPath === null) {
            $uri = $this->getUri();
            $path = explode('?', $uri, 2)[0];
            $path = str_replace('\\', '/', $path);
            $path = preg_replace('/\/+/', '/', $path);
            $this->urlPath = trim($path, '/');
        }
        return $this->urlPath;
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
            $this->pathParams = $params;
    }

}
