<?php
namespace app\controllers;
use core\Autumn;
use core\Controller;
use core\Request;
//use core\View;

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
		$criteria = new \core\Criteria;
		$criteria->add('aa', 'vv', '>');
		echo $criteria->condition;
	}
}