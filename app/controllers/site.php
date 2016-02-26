<?php

class Site extends Controller
{
	public function actionIndex()
	{
		header("Content-Type:text/html;charset=UTF-8");
		//require ROOT . '/core/Mysql.php';
		//$rs = Mysql::inst()->queryAll("select * from t_user");
		//print_r($rs);
		//require ROOT . '/core/Orm.php';
		Autumn::app()->import('Orm');
		Autumn::app()->import('Criteria');
		//echo Orm::model('t_user')->count();
		//Orm::model('t_user')->haha();
		//$criteria = new Criteria;
		//$criteria->select = 'user_id,user_account,user_nick';
		//$criteria->addCondition("user_id=184734");
		//$rs = Orm::model('t_user')->find($criteria);
		//$criteria = new Criteria;
		//$criteria->addCondition('user_gender=0');
		//$rs = Orm::model('t_user')->findAll();
		//print_r($rs);
		/*
		$t_user = Orm::model('t_user', true);
		$t_user->user_id = uniqid();
		$t_user->user_name = '测试';
		$t_user->user_gender = 1;
		$t_user->user_remark = '来自Autumn框架';
		echo $t_user->save();*/
		//echo Orm::model('t_user')->updateAll(array('user_name'=>'Autumn'), "user_id='56cfb39f2948a'");
		//$user = Orm::model('t_user')->find("user_id='56cfb26c683db'", 'user_id');
		//print_r($user);
		//echo '<br>----开始删除----<br>';
		//echo $user->delete();
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