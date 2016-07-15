<?php
/**
* Mysql数据库操作基础类
* [不推荐使用]
* ======
* @author 洪波
* @version 16.07.06
*/
namespace core;

class Mysql implements Db
{
	
	private $connect;

	/**
	* 构造方法创建数据库连接
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function __construct($db_config)
	{
		$config = Autumn::app()->config($db_config);
		if($this->connect = @ mysql_connect($config['host'], $config['user'], $config['password']))
		{
			mysql_query("set names 'utf8'");
			mysql_select_db($config['dbname']);
		}
		else
		{
			Autumn::app()->exception('数据库连接错误，请检查Mysql相关配置');
		}
	}

	/**
	* 析构方法 - 关闭数据库连接
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function __destruct()
	{
		if($this->connect != null)
		{
			mysql_close($this->connect);
			$this->connect = null;
		}
	}

	/**
	* 静态化获取数据库对象实例
	* ======
	* @param $b_config 	数据库配置字段
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public static function inst($db_config)
	{
		if(! (self::$_instance instanceof self))
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
		return mysql_query($sql, $this->connect);
	}

	/**
	* 查询列数据
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function queryScalar($sql)
	{
		$result = mysql_query($sql, $this->connect);
		if($result)
		{
			$set = mysql_fetch_array($result);
			return $set[0];
		}
		else
		{
			Autumn::app()->exception('数据库配置错误，或表名称不存在');
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
		$result = mysql_query($sql, $this->connect);
		if($result)
		{
			return mysql_fetch_object($result);
		}
		else
		{
			Autumn::app()->exception('数据库配置错误，或表名称不存在');
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
		$result = mysql_query($sql, $this->connect);
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
			Autumn::app()->exception('数据库配置错误，或表名称不存在');
		}
	}
}