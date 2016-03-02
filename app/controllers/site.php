<?php

class Site extends Controller
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
		Autumn::app()->model('user')->user_name = 'haha';
		Autumn::app()->model('user')->say();
	}
}