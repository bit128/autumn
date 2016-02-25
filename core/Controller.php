<?php
/**
* 控制器
* ======
* @author 洪波
* @version 15.02.25
*/
class Controller
{
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
		else
		{
			print_r($_GET);
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

	public function render()
	{}
}