<?php
/**
* Redis缓存类
* ======
* @author 洪波
* @version 16.03.01
*/
class Redic implements Cache
{
	//类静态实例
	private static $_instance;
	//memcahce 缓存实例
	private $_cache = null;
	//缓存服务器
	private $host = '127.0.0.1';
	//缓存端口号
	private $port = 6379;
	//数据库编号
	private $db = 0;
	//默认缓存时间
	private $limit = 7200;

	const ORIENTATION_LEFT	= 'left';
	const ORIENTATION_RIGHT	= 'right';

	/**
	* 构造方法
	* ======
	* @author 洪波
	* @version 16.03.01
	*/
	private function __construct(){
		$config = Autumn::app()->config('redis');
		if($config)
		{
			$this->host = $config['host'];
			$this->port = $config['port'];
			$this->db = $config['db'];
			$this->limit = $config['limit'];
		}
		//初始化、连接缓存
		$this->_cache = new Redis;
		$this->_cache->connect($this->host, $this->port);
		$this->_cache->select($this->db);
	}

	private function __clone(){}

	/**
	* 析构方法
	* 关闭redis，释放资源
	* ======
	* @author 洪波
	* @version 16.03.01
	*/
	public function __destruct()
	{
		if($this->_cache != null)
		{
			//$this->_cache->close();
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
		return $this->_cache->setex($key, $limit, $value);
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
	* 存储hash对象
	* ======
	* @param $key 		存储键
	* @param $object 	键值对
	* @param $limit 	有效期
	* ======
	* @author 洪波
	* @version 16.03.01
	*/
	public function setHash($key, $object, $limit = 0)
	{
		foreach ($object as $k => $v)
		{
			$this->_cache->hSet($key, $k, $v);
		}
		if($limit <= 0)
		{
			$limit = $this->limit;
		}
		$this->_cache->setTimeout($key, $limit);
	}

	/**
	* 获取hash对象
	* ======
	* @param $key 	键
	* ======
	* @author 洪波
	* @version 16.03.01
	*/
	public function getHash($key)
	{
		return $this->_cache->hGetAll($key);
	}

	/**
	* 推值入队列
	* ======
	* @param $key 	键
	* @param $value 值
	* @param $orien 推入方向 right | left
	* @param $limit 有效期
	* ======
	* @author 洪波
	* @version 16.03.01
	*/
	public function pushStack($key, $value, $orien = 'right', $limit = 0)
	{
		if($orien == self::ORIENTATION_RIGHT)
		{
			$this->_cache->rPush($key, $value);
		}
		else
		{
			$this->_cache->lPush($key, $value);
		}
		if($limit <= 0)
		{
			$limit = $this->limit;
		}
		$this->_cache->setTimeout($key, $limit);
	}

	/**
	* 推值出队列
	* ======
	* @param $key	键
	* @param $orien 推出方向 left | right
	* ======
	* @author 洪波
	* @version 16.03.01
	*/
	public function popStack($key, $orien = 'left')
	{
		if($orien == self::ORIENTATION_LEFT)
		{
			return $this->_cache->lPop($key);
		}
		else
		{
			return $this->_cache->rPop($key);
		}
	}

	/**
	* 获取队列列表
	* ======
	* @param $key 	键
	* ======
	* @author 洪波
	* @version 16.03.01
	*/
	public function getStack($key)
	{
		return $this->_cache->lRange($key, 0, -1);
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