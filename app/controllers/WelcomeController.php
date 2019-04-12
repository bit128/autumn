<?php
namespace app\controllers;
use core\Autumn;
use core\http\Response;

/**
* 提供测试列表
* ======
* @author 洪波
* @version 16.07.06
*/
class WelComeController extends \core\web\Controller {

    public function actionTestPost() {
        if (Autumn::app()->request->isPost()) {
            echo 'welcome:', Autumn::app()->request->getPost('username');
        }
    }

    public function actionTestJson() {
        Autumn::app()->response->setResult(Response::RES_OK);
        Autumn::app()->response->json();
    }

    public function actionTestNumber() {
        echo 18;
    }

    public function actionTestString() {
        echo 'hello';
    }
}