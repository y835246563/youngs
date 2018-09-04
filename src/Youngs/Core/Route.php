<?php

namespace Youngs\Core;

/**
 * @Author youngshuo@qq.com
 * @Create Time:   2018-08-15 11:21:25
 * @Description:
 */
use Youngs\Youngs;

class Route {

    privateRoute;
    privateConfig;
    privateParams;

    public function __construct() {
        
    }

    /**
     * 
     */
    public function run() {
        $this->dealRoute();
    }

    /**
     * 
     */
    public function dealRoute() {
        $routeCfg = $this->getRouteConfig();
        $route = $this->getCurrentRoute();
        if (isset($routeCfg[$route])) {
            $classArr = explode('@', $routeCfg[$route], 2);
            $className = 'App\Http\Controllers\\' . $classArr[0];
            if (!isset($classArr[1])) {
                $classArr[1] = DEFAULT_ACTION;
            }
            $this->callMethod($className, $classArr[1]);
        } else {
            echo 'the page not found; there should redirect to 404 page';
        }
    }

    /**
     *  get the route 
     * @param type $urlPath
     * @return type
     */
    public function getCurrentRoute($urlPath = null) {
        if ($thisRoute === null) {
            $thisRoute = $this->getRoute($urlPath);
            Youngs::app()->request->setPathParams($thisParams);
        }
        return $thisRoute;
    }

    /**
     *  get the route 
     * @param type $urlPath
     * @return type
     */
    public function getRoute($urlPath = null) {
        $routeCfg = $this->getRouteConfig();
        if ($urlPath === null) {
            $urlPath = Youngs::app()->request->getUrlPath();
        }
        if (isset($thisConfig[$urlPath])) {
            $currentRoute = $urlPath;
        } else {
            $currentRoute = $this->matchRoute($urlPath, $routeCfg);
        }
        return $currentRoute;
    }

    /**
     * match the custom route
     * @param type $urlPath
     * @param type $routeCfg
     * @return type $currentRoute
     */
    protected function matchRoute($urlPath, $routeCfg) {
        $route = null;
        $filterRouteCfg = array_filter($routeCfg, array($this, 'filterCustomRoute'), ARRAY_FILTER_USE_KEY);
        foreach ($filterRouteCfg as $route => $value) {
            $thisParams = [];
            $pattern = preg_replace_callback('/<([\w]*):?([^>]*)>/', array($this, 'setParamName'), $route);
            $pattern = '/' . preg_quote($pattern) . '/';
            $path = preg_replace_callback($pattern, array($this, 'setParamValue'), $urlPath);
            if ($path === '') {
                $route = ($urlPath === '') ? '' : $route;
                break;
            }
        }
        return $route;
    }

    /**
     * used to filter custom route 
     * @param type $route
     * @return boolean
     */
    protected function filterCustomRoute($route) {
        if (strpos($route, '<') !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * set the param's name in the urlpath
     * @param type $matches
     * @return type
     */
    protected function setParamName($matches) {
        if ($matches[1] != '') {
            $thisParams[$matches[1]] = null;
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
            $thisParams[array_search(NULL, $thisParams)] = $matches[$i];
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
    public function callMethod($className, $methodName = null, $params = []) {
        if (class_exists($className)) {
            $classObj = new $className();
        } else {
            echo $className . ' is not exists';
        }
        $params = $this->dealParameters($className, $classObj, $methodName, $params);
        var_dump($className);
        call_user_func_array(array($classObj, $methodName), $params);
    }

    private function dealParameters($className, $classObj, $methodName, $params) {
        if (method_exists($classObj, $methodName)) {

            $method = new \ReflectionMethod($className, $methodName);
            $parameters = $method->getParameters();
            $allParam = Youngs::app()->request->all();
            foreach ($parameters as $parameter) {
                $paramName = $parameter->name;
                $defaultValue = null;
                if (isset($params[$paramName]) === false) {
                    if (null !== $parameter->getClass()) {
                        $class = $parameter->getClass()->name;
                        $defaultValue = new $class();
                    }
                    if ($parameter->isDefaultValueAvailable()) {
                        $defaultValue = $parameter->getDefaultValue();
                    }
                    $params[] = isset($allParam[$paramName]) ? $allParam[$paramName] : $defaultValue;
                }
            }
            return $params;
        } else {
            echo $methodName . 'not exists';
            exit();
        }
    }

    public function getRouteConfig() {
        $this->setRouteConfig();
        return $thisConfig;
    }

    /**
     * set the routeconfig
     * @param type $config
     */
    public function setRouteConfig($config = null) {
        if (empty($thisConfig)) {
            $thisConfig = ($config === null) ? Youngs::app()->config['routeConfig'] : $config;
        } elseif (!empty($config)) {
            echo 'routeConfig has been set';
        }
    }

}
