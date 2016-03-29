<?php
/**
* 控制器
* ======
* @author 洪波
* @version 15.02.25
*/
class Controller
{
	//路由名
	public $route = '';

	/**
	* 构造方法
	* ======
	* @param $controller_name 	控制器名称
	* @param $action_name 		动作名称
	* ======
	* @author 洪波
	* @version 16.03.09
	*/
	public function __construct($controller_name, $action_name)
	{
		$this->route = $controller_name . '/' . $action_name;
		//初始化控制器
		$this->init();
		//执行动作
		$action = 'action' . ucfirst($action_name);
		$this->$action();
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
	* 判断是否是post请求
	* ======
	* @author 洪波
	* @version 16.03.10
	*/
	public function isPostRequest()
	{
		return isset($_POST) && $_POST;
	}
	
	/**
	* 获取get请求参数
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function getQuery($key, $default = '')
	{
		$value = $default;
		if (isset(Autumn::app()->query_params[$key]))
		{
			$value = Autumn::app()->query_params[$key];
		}
		else if (isset($_GET) && isset($_GET[$key]))
		{
			$value = $_GET[$key];
		}
		return $value;
	}

	/**
	* 获取post请求参数
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function getPost($key, $default = '')
	{
		if(isset($_POST) && isset($_POST[$key]))
		{
			return $_POST[$key];
		}
		else
		{
			return $default;
		}
	}

	/**
	* 获取请求参数
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function getParam($key, $default = '')
	{
		$value = $this->getQuery($key);
		if($value == '')
		{
			$value = $this->getPost($key);
		}

		return $value != '' ? $value : $default;
	}
}