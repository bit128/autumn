<?php
namespace app\controllers;
use core\Autumn;
use core\Controller;

/**
* 站点控制器示例
* ======
* @author 洪波
* @version 16.07.06
*/
class SiteController extends Controller
{
	public function actionIndex()
	{
		header("Content-Type:text/html;charset=UTF-8");
		echo 'Welcome to ',
			Autumn::app()->config('app_name'), ' ',
			Autumn::FRAMEWORK_VERSION;
	}

	public function actionTest()
	{
		$data = array(
			'user_name' => 'hongbo',
			'age' => 30
		);
		//Autumn::app()->redis->hSetAll('user', $data);
		//echo Autumn::app()->redis->get('user_name');
		print_r(Autumn::app()->redis->hGetAll('user'));
	}
}