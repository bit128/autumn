<?php

class SiteController extends Controller
{
	public function actionIndex()
	{
		header("Content-Type:text/html;charset=UTF-8");
		echo 'Welcome to ',
			Autumn::app()->config('app_name'),
			Autumn::app()->config('version');
	}

	public function actionTest()
	{
		header("Content-Type:text/html;charset=UTF-8");
		//$res = Mysql::inst()->queryAll('desc t_user');
		//print_r($res);
		//$rs = Orm::model('t_user')->find("user_id = '56cfb39f2948a'", true);
		//echo $rs->delete();
		echo Orm::model("t_user")->deleteAll("user_id = '56fb507ee47f7'");
	}
}