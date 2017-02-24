<?php
/**
* 控制器
* ======
* @author 洪波
* @version 16.02.25
*/
namespace core\web;
use core\Autumn;

class Controller
{
	/**
	* 构造方法
	* ======
	* @param $action_name 		动作名称
	* ======
	* @author 洪波
	* @version 16.03.09
	*/
	public function __construct($action_name)
	{
		//初始化控制器
		$this->init();
		//执行动作
		$action = 'action' . ucfirst($action_name);
		if(method_exists($this, $action))
		{
			$this->$action();
		}
		else
		{
			Autumn::app()->exception->throws('404.2 您访问的页面不见了，呜呜～～');
		}
	}

	/**
	* 控制器初始化方法
	* 子类重写用来替代构造方法
	* ======
	* @author 洪波
	* @version 16.03.09
	*/
	public function init(){}

	/**
	* 页面重定向
	* ======
	* @param $path 	定向路径
	* ======
	* @author 洪波
	* @version 16.07.06
	*/
	public function redirect($path)
	{
		header('Location:' . $path);
	}
}