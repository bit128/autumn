<?php
/**
* 请求处理类
* ======
* @author 洪波
* @version 16.07.13
*/
namespace core;

class Request
{
	//类静态实例
	private static $_instance;

	//私有化构造方法，保持实例唯一
	private function __construct(){}

	//私有化克隆方法，保持实例唯一
	private function __clone(){}

	/**
	* 静态单例获取缓存实例
	* ======
	* @author 洪波
	* @version 16.03.01
	*/
	public static function inst()
	{
		if(! (self::$_instance instanceof self))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	* 判断是否是post请求
	* ======
	* @author 洪波
	* @version 16.07.06
	*/
	public function isPostRequest()
	{
		if(isset($_SERVER['REQUEST_METHOD']))
		{
			return strtoupper($_SERVER['REQUEST_METHOD']) == 'POST' ? true : false;
		}
		return false;
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

	/**
	* 获取客户端名称
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function getName()
	{
		return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	}

	/**
	* 获取客户端IP
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function getIp()
	{
		return $_SERVER['REMOTE_ADDR'];
	}

	/**
	* 获取请求时间
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function getTime()
	{
		return $_SERVER['REQUEST_TIME'];
	}
}