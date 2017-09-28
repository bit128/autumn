<?php
/**
* Redis缓存封装快捷类
* ======
* @author 洪波
* @version 17.03.31
*/
namespace core\cache;
use core\Autumn;

class RedisExpress implements Cache {

    protected $_cache   = null;	//缓存对象实例
	protected $exp      = 60;	//缓存时间

    /**
	* 构造方法
	* ======
	* @author 洪波
	* @version 15.01.15
	*/
	public function __construct($config) {
        if($config) {
            if($this->_cache = new \Redis) {
                $this->_cache->connect($config['host'], $config['port']);
                $this->_cache->select($config['dbname']);
                $this->exp = $config['exp'];
            } else {
                Autumn::app()->exception->throws('Redis缓存连接错误：没有redis扩展或者服务未启动.');
            }
        } else {
            Autumn::app()->exception->throws('没有找到Redis缓存配置项.');
        }
	}

    /**
	* 析构方法中关闭缓存连接
	* ======
	* @author 洪波
	* @version 15.01.15
	*/
	public function __destruct() {
		@ $this->_cache->close();
	}

    /**
    * 判断缓存是否存在
    * ======
    * @param $key   键
    * ======
    * @author 洪波
    * @version 17.03.31
    */
    public function exist($key) {
        return $this->_cache->exists($key);
    }

    /**
    * 设置缓存
    * ======
    * @param $key   键
    * @param $value 值
    * @param $exp   过期时间
    * ======
    * @author 洪波
    * @version 17.03.31
    */
    public function set($key, $value, $exp = 0) {
        if ($exp == 0) {
            $exp = $this->exp;
        }
        if (is_array($value)) {
            //存储对象
            foreach ($value as $k => $v) {
                $this->_cache->hSet($key, $k, $v);
            }
            $this->_cache->setTimeout($key, $exp);
        } else {
            //存储简单类型
            $this->_cache->setex($key, $exp, $value);
        }
    }

    /**
    * 获取缓存
    * ======
    * @param $key   键
    * ======
    * @author 洪波
    * @version 17.03.31
    */
    public function get($key)
    {
        switch ($this->_cache->type($key)) {
            case \Redis::REDIS_STRING:
                return $this->_cache->get($key);
            case \Redis::REDIS_HASH:
                return $this->_cache->hGetAll($key);
        }
    }

    /**
    * 删除缓存
    * ======
    * @param $key   键
    * ======
    * @author 洪波
    * @version 17.03.31
    */
    public function delete($key) {
        return $this->_cache->delete($key);
    }
}