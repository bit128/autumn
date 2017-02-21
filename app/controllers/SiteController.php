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
		if (Autumn::app()->request->isPostRequest())
		{
			$m_user = new \app\models\M_user;
			$m_user->load($_POST);
			//$m_user->save();
			//print_r($m_user->toArray());
			if (Autumn::app()->request->checkToken())
			{
				echo 'success';
			}
			else
			{
				echo 'fail';
			}
		}
		\core\View::layout()->render('page');
	}
}