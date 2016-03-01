<?php
/**
* memcache缓存类
* ======
* @author 洪波
* @version 16.03.01
*/
class Memcaches extends Cache
{
	//类静态实例
	private static $_instance;
	//memcahce 缓存实例
	private $_cache = null;
	//缓存服务器
	private $host = '127.0.0.1';
	//缓存端口号
	private $port = 11211;
	//默认缓存时间
	private $limit = 7200;
	//是否启用压缩
	private $compress = false;

	/**
	* 构造方法
	* ======
	* @author 洪波
	* @version 16.03.01
	*/
	private function __construct(){
		$config = Autumn::app()->config('memcache');
		if($config)
		{
			$this->host = $config['host'];
			$this->port = $config['port'];
			$this->limit = $config['limit'];
			$this->compress = $config['compress'];
		}
		//初始化、连接缓存
		$this->_cache = new Memcache;
		$this->_cache->connect($this->host, $this->port);
	}

	private function __clone(){}

	/**
	* 析构方法
	* 关闭memcache，释放资源
	* ======
	* @author 洪波
	* @version 16.03.01
	*/
	public function __destruct()
	{
		if($this->_cache != null)
		{
			$this->_cache->close();
		}
	}

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
	* 设置缓存
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
		return $this->_cache->set($key, $value, $this->compress, $limit);
	}

	/**
	* 获取缓存
	* ======
	* @param $key 		键
	* @param $default 	默认值
	* ======
	* @author 洪波
	* @version 16.03.01
	*/
	public function get($key, $default = '')
	{
		$value = $this->_cache->get($key);
		if(strlen($value) > 0)
		{
			return $value;
		}
		else
		{
			return $default;
		}
	}

	/**
	* 删除缓存
	* ======
	* @param $key 	键
	* ======
	* @author 洪波
	* @version 16.03.01
	*/
	public function delete($key)
	{
		return $this->_cache->delete($key);
	}

}