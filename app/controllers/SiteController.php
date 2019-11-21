<?php
namespace app\controllers;
use core\A;

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
		$this->renderView('welcome', [
			'name' => A::c()->get('app_name') . ' Framework for PHP',
			'version' => \core\Autumn::FRAMEWORK_VERSION
		]);
	}

	/**
	 * 测试POST传参
	 * ======
	 * @author 洪波
	 * @version 19.05.22
	 */
	public function actionTestPost() {
        if ($this->isPost()) {
			$result = 'welcome: ' . $this->getParam('username');
			$this->respSuccess($result)->json();
        }
	}
	
	/**
	 * 测试异常情况
	 * ======
	 * @author 洪波
	 * @version 19.05.21
	 */
	public function actionTestBug() {
		echo 1 / 0;
	}
}