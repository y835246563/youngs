<?php
/**
 * @Author youngshuo@qq.com
 * @Create Time:   2018-09-06 10:24:39
 * @Description:
 */
/**
 * array_helper
 */
if (!function_exists('shuffle_assoc')) {

    /**
     * 随机排序保留键名
     * @param	array
     * @return	mixed	depends on what the array contains
     */
    function shuffle_assoc(array $array) {
        $keys_arr = array_keys($array);
        shuffle($keys_arr);
        foreach ($keys_arr as $key) {
            $ran_arr[$key] = $array[$key];
        }
        return $ran_arr;
    }

}


if (!function_exists('array_multi_to_single')) {

    /**
     * 多维转一维数组
     * @param	array
     * @return	mixed	depends on what the array contains
     */
    function array_multi_to_single(array $array, $pattern = null, $resultArr = [], $parentKey = null) {
        array_walk($array, function ($value, $key )use( &$resultArr, $parentKey) {
            if (null !== $pattern && null !== $parentKey) {
                $key = $parentKey . $pattern . $key;
            }
            if (is_array($value)) {
                $resultArr = array_multi_to_single($value, $pattern, $resultArr, $key);
            } else {
                $resultArr[$key] = $value;
            }
        });
        return $resultArr;
    }

}


if (!function_exists('array_multi_search')) {

    /**
     * 多维数组查询
     * @param type $needle
     * @param array $array
     * @param type $pattern
     * @param type $strict
     * @param type $parentKey
     * @return string
     */
    function array_multi_search($needle, array $array, $pattern = null, $strict = false, $parentKey = null) {
        $position = array_search($needle, $array, $strict);
        if (false === $position) {
            /*
             * filter array
             */
            $filterArr = array_filter($array, function ($array) {
                return is_array($array) ? true : false;
            });
            /*
             * foreach array
             */
            foreach ($filterArr as $key => $value) {
                $position = array_multi_search($needle, $value, $pattern, $strict, $key);
                if (false !== $position) {
                    break;
                }
            }
        }
        if (false !== $position) {
            if (null !== $pattern && null !== $parentKey) {
                $position = $parentKey . $pattern . $position;
            }
        }
        return $position;
    }

}

/**
 * url helper
 */
if (!function_exists('site_url')) {

    function site_url($uri) {
        
    }

}
if (!function_exists('redirect')) {

    /**
     * Header Redirect
     *
     * Header redirect in two flavors
     * For very fine grained control over headers, you could use the Output
     * Library's set_header() function.
     *
     * @param	string	$uri	URL
     * @param	string	$method	Redirect method
     * 			'auto', 'location' or 'refresh'
     * @param	int	$code	HTTP Response status code
     * @return	void
     */
    function redirect($uri = '', $method = 'auto', $code = NULL) {
        if (!preg_match('#^(\w+:)?//#i', $uri)) {
            $uri = site_url($uri);
        }

        // IIS environment likely? Use 'refresh' for better compatibility
        if ($method === 'auto' && isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== FALSE) {
            $method = 'refresh';
        } elseif ($method !== 'refresh' && (empty($code) OR ! is_numeric($code))) {
            if (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1') {
                $code = ($_SERVER['REQUEST_METHOD'] !== 'GET') ? 303 // reference: http://en.wikipedia.org/wiki/Post/Redirect/Get
                        : 307;
            } else {
                $code = 302;
            }
        }

        switch ($method) {
            case 'refresh':
                header('Refresh:0;url=' . $uri);
                break;
            default:
                header('Location: ' . $uri, TRUE, $code);
                break;
        }
        exit;
    }

}

if (!function_exists('app')) {

    function app($module = null) {
        return Youngs\Youngs::app($module);
    }

}