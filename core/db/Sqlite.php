<?php
/**
* SQLite数据库驱动实现类
* ======
* @author 洪波
* @version 17.05.04
*/
namespace core\db;
use core\Autumn;

class Sqlite implements Db {
    //连接对象实例
	private $connect = NULL;

    public function __construct($config) {
		$this->connect = new \SQLite3($config['source']);
    }

    /**
	* 执行sql指令
	* ======
	* @author 洪波
	* @version 15.05.05
	*/
	public function query($sql) {
		return $this->connect->exec($sql);
	}

	/**
	* 查询列数据
	* ======
	* @author 洪波
	* @version 16.05.05
	*/
	public function queryScalar($sql) {
		return $this->connect->querySingle($sql);
	}

	/**
	* 查询行数据
	* ======
	* @author 洪波
	* @version 16.05.05
	*/
	public function queryRow($sql) {
		return $this->connect->querySingle($sql, true);
	}

	/**
	* 查询二维行数据
	* ======
	* @author 洪波
	* @version 16.05.05
	*/
	public function queryAll($sql) {
		$result = $this->connect->query($sql);
		if ($result) {
			$set = [];
			while ($item = $result->fetchArray(SQLITE3_ASSOC)) {
				$set[] = (object) $item;
			}
			unset($result);
			return $set;
		} else {
			Autumn::app()->exception->throws('数据库操作异常：'.$sql);
		}
	}
}