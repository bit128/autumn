<?php
/**
* cookie应用类
* ======
* @author 洪波
* @version 16.11.30
*/
class Cookie
{
	//类静态实例
	private static $_instance;
	//默认缓存时间
	protected $limit = 7200;

	private function __construct(){}

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
	* 批量设置会话
	* ======
	* @param $array 	键值对
	* @param $limit 	有效期
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	public function sets($array, $limit = 0)
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
	* @version 16.02.29
	*/
	public function set($key, $value, $limit = 0)
	{
		if($limit <= 0)
		{
			$limit = $this->limit;
		}
		$value = base64_encode($value);
		setcookie($key, $value, time() + $limit, '/');
		$_COOKIE[$key] = $value;
	}

	/**
	* 获取持久会话
	* ======
	* @param $key 		键
	* @param $default 	默认值
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	public function get($key, $default = '')
	{
		if(isset($_COOKIE[$key]))
		{
			return base64_decode($_COOKIE[$key]);
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
	* @version 16.02.29
	*/
	public function delete($key)
	{
		setcookie($key, '', 0, '/');
		$_COOKIE[$key] = '';
	}
}