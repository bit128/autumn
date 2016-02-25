<?php

class Site extends Controller
{
	public function actionIndex()
	{
		echo 'ok';
	}

	public function actionHome()
	{
		echo 'home action.<br>';
		echo $this->getQuery('page');
	}
}