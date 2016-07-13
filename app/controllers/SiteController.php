<?php
namespace app\controllers;
use core\Autumn;
use core\Controller;
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
			Autumn::app()->config('version');
	}

	public function actionTest()
	{
		$a = (object) array('user_name'=>'hongbo', 'user_gender'=>2);
		$b = (object) array('face'=>'aaa.jpg', 'title'=>'good mac');


		$result = array(
			'user' => $a,
			'guide' => $b,
			'count' => [1, 2, 3, 4]
			);

		//$response = new \core\Response;
		$this->response->setCode(1000);
		$this->response->setResult($result);
		$this->response->xml(true);
	}
}