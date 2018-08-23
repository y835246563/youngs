<?php

namespace Youngs\Core;

/**
 * @Author youngshuo@qq.com
 * @Create Time:   2018-08-15 11:21:25
 * @Description:
 */
use Youngs\Youngs;

class Route {

    private $_currentRoute;
    private $_routeConfig;
    private $_pathParams;

    public function __construct() {
        
    }

    /**
     * 
     */
    public function run() {
        $routeCfg = $this->getRouteConfig();
        $route = $this->getRoute();

        if (isset($routeCfg[$route])) {
            $classArr = explode('@', $routeCfg[$route], 2);
            $className = 'App\Http\Controllers\\' . $classArr[0];
            if (!isset($classArr[1])) {
                $classArr[1] = DEFAULT_ACTION;
            }
            $this->callMethod($className, $classArr[1]);
        } else {
            echo ' there should redirect to 404 page';
        }
    }

    /**
     *  get the route 
     * @param type $urlPath
     * @return type
     */
    public function getRoute($urlPath = null) {
        if ($this->_currentRoute === null) {
            $routeCfg = $this->getRouteConfig();
            if ($urlPath === null) {
                $urlPath = Youngs::app()->request->getUrlPath();
            }
            if (isset($this->_routeConfig[$urlPath])) {
                $this->_currentRoute = $urlPath;
            } else {
                $this->matchRoute($urlPath, $routeCfg);
            }
        }
        return $this->_currentRoute;
    }

    /**
     * match the custom route,only use by Route::getRoute()
     * @param type $urlPath
     * @param type $routeCfg
     */
    protected function matchRoute($urlPath, $routeCfg) {
        $filterRouteCfg = array_filter($routeCfg, array($this, 'filterCustomRoute'));
        foreach ($filterRouteCfg as $route => $value) {
            $pattern = preg_replace_callback('/<([\w]*):?([^>]*)>/', array($this, 'setParamName'), $route);
            $pattern = '/' . preg_quote($pattern) . '/';
            $path = preg_replace_callback($pattern, array($this, 'setParamValue'), $urlPath);
            if ($path === '') {
                $this->_currentRoute = $urlPath === '' ? '' : $route;
                Youngs::app()->request->setPathParams($this->_currentRoute);
                break;
            }
        }
    }

    /**
     * used to filter custom route 
     * @param type $route
     * @return boolean
     */
    protected function filterCustomRoute($route) {
        if (strpos($route, '<') !== false) {
            return true;
        }
    }

    /**
     * set the param's name in the urlpath
     * @param type $matches
     * @return type
     */
    protected function setParamName($matches) {
        if ($matches[1] != '') {
            $this->_pathParams[$matches[1]] = null;
        }
        return '(' . $matches[2] . ')';
    }

    /**
     * set the param's value in the urlpath
     * @param type $matches
     * @return string
     */
    protected function setParamValue($matches) {
        for ($i = 1; $i < count($matches); $i++) {
            $this->_pathParams[array_search(NULL, $this->_pathParams)] = $matches[$i];
        }
        return '';
    }

    /**
     *  call the controller action
     * @param type $className
     * @param type $methodName
     * @param type $params Description
     * @return boolean
     */
    public function callMethod($className, $methodName = '', $params = []) {
        if (empty($className) || empty($methodName)) {
            return false;
        }
        if (class_exists($className)) {
            $classObj = new $className();
        } else {
            echo $className . ' is not exists';
        }
        if (method_exists($classObj, $methodName)) {
            $method = new \ReflectionMethod($className, $methodName);
            $parameters = $method->getParameters();
            $allParam = Youngs::app()->request->all();
            foreach ($parameters as $parameter) {
                if (isset($params[$parameter->name]) === false) {
                    $params[] = isset($allParam[$parameter->name]) ? $allParam[$parameter->name] : null;
                }
            }
            call_user_func_array(array($classObj, $methodName), $params);
        }
    }

    public function getRouteConfig() {
        $this->setRouteConfig();
        return $this->_routeConfig;
    }

    public function setRouteConfig($config = null) {
        if (empty($this->_routeConfig)) {
            $this->_routeConfig = empty($config) ? Youngs::app()->config['routeConfig'] : $config;
        } elseif (!empty($config)) {
            echo 'routeConfig has been set';
        }
    }

}