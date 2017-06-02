<?php
/**
* Mysql数据库驱动实现类
* ======
* @author 洪波
* @version 16.07.15
*/
namespace core\db;
use core\Autumn;

class Mysqli implements Db
{
	//连接对象实例
	private $connect = null;

	/**
	* 构造方法创建数据库连接
	* ======
	* @author 洪波
	* @version 16.07.15
	*/
	public function __construct($config)
	{
		if($this->connect = mysqli_connect($config['host'], $config['user'], $config['password'], $config['dbname']))
		{
			mysqli_set_charset($this->connect, 'utf8');
		}
		else
		{
			Autumn::app()->exception->throws('数据库连接错误：' . mysqli_connect_error());
		}
	}

	/**
	* 析构方法 - 关闭数据库连接
	* ======
	* @author 洪波
	* @version 16.07.15
	*/
	public function __destruct()
	{
		$this->close();
	}

	/**
	* 返回最后一次查询Id
	* ======
	* @author 洪波
	* @version 16.07.15
	*/
	public function insertId()
	{
		return mysqli_insert_id($this->connect);
	}

	/**
	* 执行sql指令
	* ======
	* @author 洪波
	* @version 15.07.15
	*/
	public function query($sql)
	{
		return mysqli_query($this->connect, $sql);
	}

	/**
	* 查询列数据
	* ======
	* @author 洪波
	* @version 16.07.15
	*/
	public function queryScalar($sql)
	{
		$result = mysqli_query($this->connect, $sql);
		if($result)
		{
			$set = mysqli_fetch_array($result);
			mysqli_free_result($result);
			return $set[0];
		}
		else
		{
			Autumn::app()->exception->throws('数据库操作异常：' . mysqli_error($this->connect));
		}
	}

	/**
	* 查询行数据
	* ======
	* @author 洪波
	* @version 16.07.15
	*/
	public function queryRow($sql)
	{
		$result = mysqli_query($this->connect, $sql);
		if($result)
		{
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			mysqli_free_result($result);
			return $row;
		}
		else
		{
			Autumn::app()->exception->throws('数据库操作异常：' . mysqli_error($this->connect));
		}
	}

	/**
	* 查询二维行数据
	* ======
	* @author 洪波
	* @version 16.07.15
	*/
	public function queryAll($sql)
	{
		$result = mysqli_query($this->connect, $sql);
		if($result)
		{
			$set = [];
			while ($item = mysqli_fetch_object($result))
			{
				$set[] = $item;
			}
			mysqli_free_result($result);
			return $set;
		}
		else
		{
			Autumn::app()->exception->throws('数据库操作异常：' . mysqli_error($this->connect));
		}
	}

	/**
	* 开启事务处理
	* ======
	* @author 洪波
	* @version 16.07.15
	*/
	public function beginTransaction()
	{
		mysqli_autocommit($this->connect, false);
	}

	/**
	* 提交事务
	* ======
	* @author 洪波
	* @version 16.07.15
	*/
	public function commit()
	{
		mysqli_commit($this->connect);
	}

	/**
	* 回滚事务
	* ======
	* @author 洪波
	* @version 16.07.15
	*/
	public function rollBack()
	{
		mysqli_rollback($this->connect);
	}

	/**
	* 关闭数据库连接
	* ======
	* @author 洪波
	* @version 16.11.16
	*/
	public function close()
	{
		if($this->connect != null)
		{
			mysqli_close($this->connect);
			$this->connect = null;
		}
	}
}