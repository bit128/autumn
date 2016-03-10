<?php

class SiteController extends Controller
{
	public function actionIndex()
	{
		header("Content-Type:text/html;charset=UTF-8");
		echo 'Welcome to ',
			Autumn::app()->config('app_name'),
			Autumn::app()->config('version');

		echo '<br>';

		echo Autumn::app()->model('user')->add();
	}
}