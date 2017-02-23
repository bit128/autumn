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
			Autumn::app()->config->get('app_name'), ' ',
			Autumn::FRAMEWORK_VERSION;
	}

	public function actionTest()
	{
		//echo \core\Orm::model('t_user')->find()->user_name;
		//Autumn::app()->view->render('page');
		//\core\View::layout('layout2')->render('page');
		//Autumn::app()->cache->set('user_name', 'baba');
		//echo Autumn::app()->cache->get('user_name');
		//Autumn::app()->route->start('site', 'test');
		echo Autumn::app()->request->getQuery('name');
	}
}