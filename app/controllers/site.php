<?php

class Site extends Controller
{
	public function actionIndex()
	{
		//require ROOT . '/core/Mysql.php';
		//$rs = Mysql::inst()->queryAll("select * from t_user");
		//print_r($rs);
		//require ROOT . '/core/Orm.php';
		Autumn::app()->import('Orm');
		//echo Orm::model('t_user')->count();
		Orm::model('t_user')->haha();
	}

	public function actionHome()
	{
		//echo 'home action.<br>';
		//echo $this->getQuery('page');
		$data = array(
			'hello' => 'hello,world',
			'title' => 'very good'
			);
		$this->render('page', $data);
		//$this->renderPartial('page', $data);
	}
}