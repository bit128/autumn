<?php
namespace app\controllers;

/**
* 站点控制器示例
* ======
* @author 洪波
* @version 16.07.06
*/
class SiteController extends \core\web\Controller {

	public function actionTest() {
		$orm = new \core\db\Orm('t_user', \core\Autumn::app()->db);
		$criteria = new \core\db\Criteria;
		$criteria->select(['user_id,user_name']);
		$criteria->add('user_phone', '', '!=');
		$criteria->pageLimit(3);
		$result = $orm->findAll($criteria);
		//$result = $orm->find("user_phone != ''");
		\print_r($result);
	}

	/**
	* 测试action
	* ======
	* @author 洪波
	* @version 19.11.22
	*/
	public function actionIndex() {
		$this->renderView('welcome', [
			'name' => \core\Autumn::app()->config->get('app_name') . ' Framework for PHP',
			'version' => \core\Autumn::FRAMEWORK_VERSION
		]);
	}

	/**
	 * 测试POST传参
	 * ======
	 * @author 洪波
	 * @version 19.11.22
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
	 * @version 19.11.22
	 */
	public function actionTestBug() {
		echo 1 / 0;
	}
}