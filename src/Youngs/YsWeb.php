<?php

namespace Youngs;

/**
 * @Author youngshuo@qq.com
 * @Create Time:   2018-08-15 11:21:25
 * @Description:
 */
use Youngs\Core\Route;
use Youngs\Youngs;

class YsWeb extends YsBase {

	public $config = [];

	function __construct($config) {
		parent::__construct($config);
	}

	/**
	 * 
	 */
	public function run() {
		$ysException = new \Youngs\Exceptions\YsException;
		set_exception_handler([$ysException, 'write']);
		set_error_handler([$ysException, 'errorWrite']);
		Youngs::app()->route->run();
                var_dump(site_url('/333'));
//        var_dump([
//            $_SERVER, //服务器和执行环境信息
//            $_GET, //HTTP GET 变量
//            $_POST, //HTTP POST 变量
//            $_FILES, //HTTP 文件上传变量
//            $_REQUEST, //HTTP Request 变量
////            $_SESSION, //Session 变量
//            $_ENV, //环境变量
//            $_COOKIE, //HTTP Cookies
////            $php_errormsg, //前一个错误信息
////            $HTTP_RAW_POST_DATA, //原生POST数据
////            $http_response_header, //HTTP 响应头
////            $argc, //传递给脚本的参数数目
////            $argv, //传递给脚本的参数数组
//        ]);
	}

	public function autoLoadFile($config) {
		if (!class_exists('Youngs/Core/Route')) {
			$coreArr = $this->get_all_files(YS_PATH . '');
			$this->loadFiles($coreArr);
		}
		$controllerArr = $this->get_all_files(SITE_CLASS_PATH . 'Controllers/');
		$this->loadFiles($controllerArr);

		$config[] = ['controllerArr' => $controllerArr];
		$this->config = $config;
	}

}
