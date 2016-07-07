<?php
/**
* session应用类
* ======
* @author 洪波
* @version 16.11.30
*/
namespace library;

use core\Autumn;

class Session
{
	//类静态实例
	private static $_instance;

	private function __construct(){
		if(! Autumn::app()->config('session_start'))
		{
			Autumn::app()->exception('session没有开启，请先配置config/main.php');
		}
	}

	private function __clone(){}

	/**
	* 静态单例获取缓存实例
	* ======
	* @author 洪波
	* @version 16.05.04
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
	* 批量设置会话
	* ======
	* @param $array 	键值对
	* ======
	* @author 洪波
	* @version 16.05.04
	*/
	public function sets($array)
	{
		if(is_array($array))
		{
			foreach ($array as $key => $value)
			{
				$this->set($key, $value, $limit);
			}
		}
	}

	/**
	* 设置持久会话
	* ======
	* @param $key 		键
	* @param $value 	值
	* @param $limit 	有效期
	* ======
	* @author 洪波
	* @version 16.05.04
	*/
	public function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	/**
	* 获取持久会话
	* ======
	* @param $key 		键
	* @param $default 	默认值
	* ======
	* @author 洪波
	* @version 16.05.04
	*/
	public function get($key, $default = '')
	{
		if(isset($_SESSION[$key]))
		{
			return $_SESSION[$key];
		}
		else
		{
			return $default;
		}
	}

	/**
	* 删除持久会话
	* ======
	* @param $key 	键
	* ======
	* @author 洪波
	* @version 16.05.04
	*/
	public function delete($key)
	{
		unset($_SESSION[$key]);
	}

	/**
	* 销毁全部会话
	* ======
	* @author 洪波
	* @version 16.05.04
	*/
	public function destory()
	{
		session_destroy();
	}
}