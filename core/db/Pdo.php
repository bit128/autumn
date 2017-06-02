<?php
/**
* PDO数据库驱动实现类
* ======
* @author 洪波
* @version 17.03.27
*/
namespace core\db;
use core\Autumn;

class Pdo implements Db
{
	//连接对象实例
	private $connect = null;

	/**
	* 构造方法创建数据库连接
	* ======
	* @author 洪波
	* @version 17.03.27
	*/
	public function __construct($config)
	{
		try
		{
            $this->connect = new \PDO($config['link'], $config['user'], $config['password']);
			$this->connect->exec('set names utf8');
		}
        catch (PDOException $e)
        {
            Autumn::app()->exception->throws('数据库连接错误：' . $e->getMessage());
        }
	}

	/**
	* 析构方法 - 关闭数据库连接
	* ======
	* @author 洪波
	* @version 17.03.27
	*/
	public function __destruct()
	{
		$this->close();
	}

	/**
	* 返回最后一次查询Id
	* ======
	* @author 洪波
	* @version 17.03.27
	*/
	public function insertId()
	{
		return $this->connect->lastInsertId();
	}

	/**
	* 执行sql指令
	* ======
	* @author 洪波
	* @version 15.07.15
	*/
	public function query($sql, $params = [])
	{
		$stmt =$this->connect->prepare($sql);
		$stmt->execute($params);
		$result = $stmt->rowCount();
		$stmt->closeCursor();
		return $result;
	}

	/**
	* 查询列数据
	* ======
	* @author 洪波
	* @version 17.03.27
	*/
	public function queryScalar($sql, $params = [])
	{
		$stmt =$this->connect->prepare($sql);
		$stmt->execute($params);
		$result = $stmt->fetchColumn();
		$stmt->closeCursor();
		return $result;
	}

	/**
	* 查询行数据
	* ======
	* @author 洪波
	* @version 17.03.27
	*/
	public function queryRow($sql, $params = [])
	{
		$stmt =$this->connect->prepare($sql);
		$stmt->execute($params);
		$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		return $result;
	}

	/**
	* 查询二维行数据
	* ======
	* @author 洪波
	* @version 17.03.27
	*/
	public function queryAll($sql, $params = [])
	{
        $stmt =$this->connect->prepare($sql);
		$stmt->execute($params);
		$result = $stmt->fetchAll(\PDO::FETCH_OBJ);
		$stmt->closeCursor();
		return $result;
	}

	/**
	* 开启事务处理
	* ======
	* @author 洪波
	* @version 17.03.27
	*/
	public function beginTransaction()
	{
		return $this->connect->beginTransaction();
	}

	/**
	* 提交事务
	* ======
	* @author 洪波
	* @version 17.03.27
	*/
	public function commit()
	{
		return $this->connect->commit();
	}

	/**
	* 回滚事务
	* ======
	* @author 洪波
	* @version 17.03.27
	*/
	public function rollBack()
	{
		return $this->connect->rollBack();
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
			$this->connect = null;
		}
	}
}