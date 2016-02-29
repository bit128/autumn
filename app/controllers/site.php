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
		header("Content-Type:text/html;charset=UTF-8");
		Autumn::app()->import('Pagination');
		$page = new Pagination(23, 5, 1, '/site/home');
		echo $page->build();
	}
}