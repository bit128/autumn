<?php
/**
* 数据库操作基础类
* ======
* @author 洪波
* @version 15.02.25
*/
class Mysql implements Db
{
	private static $_instance;
	protected $_db;

	private function __clone(){}

	/**
	* 私有化构造方法
	* 单例模式创建数据库连接
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	private function __construct($db_config)
	{
		$config = Autumn::app()->config($db_config);
		$this->_db = mysql_connect($config['host'], $config['user'], $config['password']);
		mysql_query("set names 'utf8'");
		mysql_select_db($config['dbname']);
	}

	/**
	* 析构方法 - 关闭数据库连接
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function __destruct()
	{
		if($this->_db != null)
		{
			mysql_close($this->_db);
			$this->_db = null;
		}
	}

	/**
	* 静态化获取数据库对象实例
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public static function inst($db_config, $new = false)
	{
		if(! (self::$_instance instanceof self) || $new)
		{
			if(self::$_instance)
				self::$_instance = null;
			self::$_instance = new self($db_config);
		}
		return self::$_instance;
	}

	/**
	* 执行sql指令
	* ======
	* @author 洪波
	* @version 15.02.25
	*/
	public function query($sql)
	{
		return mysql_query($sql, $this->_db);
	}

	/**
	* 查询列数据
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function queryScalar($sql)
	{
		$result = mysql_query($sql, $this->_db);
		if($result)
		{
			$set = mysql_fetch_array($result);
			return $set[0];
		}
		else
		{
			Autumn::app()->exception('数据库表名称不存在');
		}
	}

	/**
	* 查询行数据
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function queryRow($sql)
	{
		$result = mysql_query($sql, $this->_db);
		if($result)
		{
			return mysql_fetch_object($result);
		}
		else
		{
			Autumn::app()->exception('数据库表名称不存在');
		}
	}

	/**
	* 查询二维行数据
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function queryAll($sql)
	{
		$result = mysql_query($sql, $this->_db);
		if($result)
		{
			$set = array();
			while ($item = mysql_fetch_object($result))
			{
				$set[] = $item;
			}
			return $set;
		}
		else
		{
			Autumn::app()->exception('数据库表名称不存在');
		}
	}
}