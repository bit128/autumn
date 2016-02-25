<?php
/**
* 数据库操作基础类
* ======
* @author 洪波
* @version 15.02.25
*/
class Mysql
{
	private static $_instance = null;
	protected $_db;

	private function __clone(){}

	/**
	* 私有化构造方法
	* 单例模式创建数据库连接
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	private function __construct()
	{
		$config = Autumn::app()->config('mysql');
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
		mysql_close($this->_db);
	}

	/**
	* 静态化获取数据库对象实例
	* ======
	* @author 洪波
	* @version 16.02.25
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
		$set = mysql_fetch_array($result);
		return $set[0];
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
		return mysql_fetch_object($result);
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
		$set = array();
		while ($item = mysql_fetch_object($result))
		{
			$set[] = $item;
		}
		return $set;
	}
}