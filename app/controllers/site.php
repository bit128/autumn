<?php

class Site extends Controller
{
	public function actionIndex()
	{
		header("Content-Type:text/html;charset=UTF-8");
		echo 'Welcome to ',
			Autumn::app()->config('app_name'),
			Autumn::app()->config('version');
		echo '<a href="/index.php/site/test">click</a>';
	}

	public function actionTest()
	{
		header("Content-Type:text/html;charset=UTF-8");
		//Autumn::app()->import('Memcaches');
		//Memcaches::inst()->set('user_name', 'hongbo');
		//echo Memcaches::inst()->get('user_name');
		Log::inst()->record('Test log.');
	}
}