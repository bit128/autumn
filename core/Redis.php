<?php
/**
* Redis缓存类
* ======
* @author 洪波
* @version 17.02.10
*/
namespace core;

class Redis
{

    protected $_cache       = null;	//缓存对象实例
	protected $cache_host   = '';	//缓存服务器地址
	protected $cache_prot   = 0;	//缓存服务器端口
	protected $cache_limit  = 60;	//缓存时间
	protected $cache_db     = 0;	//正式数据库0 - 测试数据库1

    /**
	* 构造方法
	* ======
	* @author 洪波
	* @version 15.01.15
	*/
	public function __construct($cache_config = 'cache')
	{
		$config = Autumn::app()->config->get($cache_config);
        if($config)
        {
            if($this->_cache = new \Redis)
            {
                $this->_cache->connect($config['host'], $config['port']);
                $this->_cache->select($config['cache_db']);
            }
            else
            {
                Autumn::app()->exception->throws('Redis缓存连接错误：没有redis扩展或者服务未启动.');
            }
        }
		else
        {
            Autumn::app()->exception->throws('没有找到Redis缓存配置项.');
        }
	}

    /**
	* 析构方法中关闭缓存连接
	* ======
	* @author 洪波
	* @version 15.01.15
	*/
	public function __destruct()
	{
		@ $this->_cache->close();
	}

    /**
    * 获取原生redis对象实例
    * ======
    * @author 洪波
    * @version 17.02.10
    */
    public function getInstance()
    {
        return $this->_cache;
    }

    /**
    * 刷新缓存
    * ======
    * param $all 是否释放全库
    * ======
    * @author 洪波
    * @version 17.02.10
    */
    public function flush($all = false)
    {
        return $all ? $this->_cache->flushAll() : $this->_cache->flushDb();
    }

    /**
    * 判断缓存值是否存在
    * ======
    * @param $key 键
    * ======
    * @author 洪波
    * @version 17.02.10
    */
    public function exist($key)
    {
        return $this->_cache->exists($key);
    }

    /**
    * 获取缓存值
    * ======
    * @param $key 键
    * ======
    * @author 洪波
    * @version 17.02.10
    */
    public function get($key)
    {
        return $this->_cache->get($key);
    }

    /**
    * 获取缓存值
    * ======
    * @param $key           键
    * @param $value         值
    * @param $cache_limit   缓存时间 
    * ======
    * @author 洪波
    * @version 17.02.10
    */
    public function set($key, $value, $cache_limit = 0)
    {
        if ($cache_limit == 0)
        {
            $cache_limit = $this->cache_limit;
        }
        return $this->_cache->setex($key, $cache_limit, $value);
    }

    /**
    * 判断hset中是否包含字段
    * ======
    * @param $key       键
    * @param $field     字段
    * ======
    * @author 洪波
    * @version 17.02.10
    */
    public function hExist($key, $field)
    {
        return $this->_cache->hExists($key, $field);
    }

    /**
    * 获取hset中指定字段值
    * ======
    * @param $key       键
    * @param $field     字段
    * ======
    * @author 洪波
    * @version 17.02.10
    */
    public function hGet($key, $field)
    {
        if ($this->hExist($key, $field))
        {
            return $this->_cache->hGet($key, $field);
        }
        else
        {
            return '';
        }
    }

    /**
    * 获取hset全部值
    * ======
    * @param $key       键
    * ======
    * @author 洪波
    * @version 17.02.10
    */
    public function hGetAll($key)
    {
        return (object) $this->_cache->hGetAll($key);
    }

    /**
    * 设置hset中字段值
    * ======
    * @param $key       键
    * @param $field     字段
    * @param $value     值
    * ======
    * @author 洪波
    * @version 17.02.10
    */
    public function hSet($key, $field, $value)
    {
        return $this->_cache->hSet($key, $field, $value);
    }

    /**
    * 设置hset缓存值
    * ======
    * @param $key           键
    * @param $value         值
    * @param $cache_limit   有效期
    * ======
    * @author 洪波
    * @version 17.02.10
    */
    public function hSetAll($key, $value, $cache_limit = 0)
    {
        foreach ($value as $k => $v)
        {
            $this->hSet($key, $k, $v);
        }
        if ($cache_limit == 0)
        {
            $cache_limit = $this->cache_limit;
        }
        $this->_cache->setTimeout($key, $cache_limit);
    }

    /**
    * 删除缓存值
    * ======
    * @param $key 键
    * ======
    * @author 洪波
    * @version 17.02.10
    */
    public function delete($key)
    {
        return $this->_cache->delete($key);
    }
}