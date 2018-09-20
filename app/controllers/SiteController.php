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
		Autumn::app()->view->render('welcome', [
			'name' => Autumn::app()->config->get('app_name') . ' Framework for PHP',
			'version' => Autumn::FRAMEWORK_VERSION
		]);
	}
}