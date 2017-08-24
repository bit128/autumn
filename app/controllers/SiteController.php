<?php
namespace app\controllers;
use core\Autumn;

/**
* 站点控制器示例
* ======
* @author 洪波
* @version 16.07.06
*/
class SiteController extends \core\web\Controller
{
	public function actionIndex()
	{
		header("Content-Type:text/html;charset=UTF-8");
		echo 'Welcome to ',
			Autumn::app()->config->get('app_name'), ' ',
			Autumn::FRAMEWORK_VERSION;
	}

	public function actionTest()
	{
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($socket, '192.168.0.100', 3008);
		$data = '{"code":100, "path":"", "name":"default2.png"}';
		socket_write($socket, $data, strlen($data));
		echo socket_read($socket, 2048);
		socket_close($socket);
		exit;
	}
}