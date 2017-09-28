<?php
namespace app\controllers;
use core\Autumn;

/**
* 站点控制器示例
* ======
* @author 洪波
* @version 16.07.06
*/
class SiteController extends \core\web\Controller {
	/**
	* 测试action
	* ======
	* @author 洪波
	* @version 17.09.28
	*/
	public function actionIndex() {
		header("Content-Type:text/html;charset=UTF-8");
		echo 'Welcome to ',
			Autumn::app()->config->get('app_name'), ' ',
			Autumn::FRAMEWORK_VERSION;
	}
}