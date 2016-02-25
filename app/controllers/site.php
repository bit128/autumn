<?php

class Site extends Controller
{
	public function actionIndex()
	{
		echo 'ok';
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