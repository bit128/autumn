<?php
/**
* 对象关系映射模型
* ======
* @author 洪波
* @version 16.02.25
*/
class Orm
{
	//静态化实例
	private static $_instance;
	//数据库驱动实例
	private $_db;
	//操作表名称
	private $table_name;
	//动态记录数组
	private $ar = array();
	//动态记录主键
	private $pk = '';
	
	/**
	* 静态获取实例对象
	* ======
	* @param $table_name	操作表名称
	* @param $new 			是否全新创建
	* ======
	* @return object-self
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public static function model($table_name, $new = false)
	{
		if(! (self::$_instance instanceof self) || $new)
		{
			self::$_instance = new self($table_name);
		}
		self::$_instance->setTable($table_name);
		return self::$_instance;
	}
	
	/**
	* 私有化构造方法
	* ======
	* @param $table_name	操作表名称
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	private function __construct($table_name)
	{
		//设置表名称
		$this->table_name = $table_name;
		//加载数据库依赖
		$driver = Autumn::app()->config('database')['driver'];
		Autumn::app()->imports(array('Db', $driver));
		//载入数据库驱动（需要实现Db接口）
		$this->_db = $driver::inst();
	}
	
	/**
	* 获取动态记录值
	* ======
	* @param $name 记录健
	* ======
	* @return mixed
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function __get($name)
	{
		if(isset($this->ar[$name]))
		{
			return $this->ar[$name];
		}
		else
		{
			return '';
		}
	}
	
	/**
	* 设置动态记录值
	* ======
	* @param $name 	记录健
	* @param $value 记录值
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function __set($name, $value)
	{
		$this->ar[$name] = $value;
	}
	
	/**
	* 获取动态记录数组
	* ======
	* @return array
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function getAttributes()
	{
		return $this->ar;
	}
	
	/**
	* 刷新动态记录值
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function flush()
	{
		$this->ar = array();
		$this->pk = '';
	}
	
	/**
	* 设置操作表名称
	* ======
	* @param $table_name 	操作表名称
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function setTable($table_name)
	{
		$this->table_name = $table_name;
	}

	/**
	* 统计表记录行数
	* ======
	* @param $condition 	criteria对象 | string查询条件
	* ======
	* @return integer
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function count($condition = null)
	{
		$sql = 'select count(*) from ' . $this->table_name;
		if($condition)
		{
			if($condition instanceof Criteria)
			{
				if($condition->condition)
					$sql .= ' where ' . $condition->condition;
			}
			else
			{
				$sql .= ' where ' . $condition;
			}
		}
		return $this->_db->queryScalar($sql);
	}

	/**
	* 查找单行记录
	* ======
	* @param $condition 	criteria对象 | string查询条件
	* ======
	* @return stdClass object | object-self(仅当包含主键查询)
	* ======
	* @author 洪波
	* @version 16.02.26
	*/
	public function find($condition = null, $pk = '')
	{
		$this->flush();
		$this->pk = $pk;
		
		$sql = "select * from " . $this->table_name;
		if($condition)
		{
			if($condition instanceof Criteria)
			{
				$sql = "select " . $condition->select . " from " . $this->table_name;
				if($condition->condition)
					$sql .= ' where ' . $condition->condition;
			}
			else
			{
				$sql .= ' where ' . $condition;
			}
		}

		$result = $this->_db->queryRow($sql);
	
		if($pk != '')
		{
			$this->ar = (array) $result;
			return self::$_instance;
		}
		else
		{
			return $result;
		}
	}

	/**
	* 查找多行记录
	* ======
	* @param $condition 	criteria对象 | string查询条件
	* ======
	* @param array<stdClass object>
	* ======
	* @author 洪波
	* @version 16.02.26
	*/
	public function findAll($condition = null)
	{
		$this->flush();
		
		$sql = "select * from " . $this->table_name;
		if($condition)
		{
			if($condition instanceof Criteria)
			{
				$sql = "select " . $condition->select . " from " . $this->table_name;
				if($condition->condition)
					$sql .= ' where ' . $condition->condition;
				if($condition->order)
					$sql .= ' order by ' . $condition->order;
				if($condition->offset != -1)
					$sql .= ' limit ' . $condition->offset;
				if($condition->limit != -1)
					$sql .= ',' . $condition->limit;
			}
			else
			{
				$sql .= ' where ' . $condition;
			}
		}
		return $this->_db->queryAll($sql);
	}

	/**
	* 保存记录
	* ======
	* @return boolean
	* ======
	* @author 洪波
	* @version 16.02.26
	*/
	public function save()
	{
		if($this->ar)
		{
			//空主键则插入数据
			if($this->pk == '')
			{
				$field = '';
				$value = '';
				foreach ($this->ar as $k => $v)
				{
					$field .= ',' . $k;
					$value .= ",'" . addslashes($v) . "'";
				}
				$sql = "insert into " . $this->table_name . " (" . substr($field, 1) . ") values (" . substr($value, 1) . ")";

				return $this->_db->query($sql);
			}
			//包含主键则更新数据
			else
			{
				$pk_val = $this->ar[$this->pk];
				unset($this->ar[$this->pk]);
				return $this->updateAll($this->ar, "{$this->pk} = '{$pk_val}'");
			}
		}
		else
		{
			return 0;
		}
	}

	/**
	* 按条件修改记录
	* ======
	* @param $data			修改数据数组
	* @param $condition 	criteria对象 | string查询条件
	* ======
	* @return boolean
	* ======
	* @author 洪波
	* @version 16.02.26
	*/
	public function updateAll($data, $condition)
	{	
		$sql = "update " . $this->table_name . " set ";
		$set = "";
		foreach ($data as $k => $v)
		{
			$set .= "," . $k . "='" . addslashes($v) . "'";
		}
		$sql .= substr($set, 1);
		if($condition)
		{
			if($condition instanceof Criteria)
			{
				if($condition->condition)
					$sql .= ' where ' . $condition->condition;
			}
			else
			{
				$sql .= ' where ' . $condition;
			}
		}
		$this->flush();
		return $this->_db->query($sql);
	}

	/**
	* 删除当前动态记录
	* ======
	* @return boolean
	* ======
	* @author 洪波
	* @version 16.02.26
	*/
	public function delete()
	{
		if($this->ar && $this->pk != '')
		{
			$pk_val = $this->ar[$this->pk];
			return $this->deleteAll("{$this->pk} = '{$pk_val}'");
		}
		else
		{
			return 0;
		}
	}
	
	/**
	* 按条件删除记录
	* ======
	* @param $condition 	criteria对象 | string查询条件
	* ======
	* @return boolean
	* ======
	* @author 洪波
	* @version 16.02.26
	*/
	public function deleteAll($condition)
	{
		$sql = "delete from " . $this->table_name;
		if($condition)
		{
			if($condition instanceof Criteria)
			{
				if($condition->condition)
					$sql .= ' where ' . $condition->condition;
			}
			else
			{
				$sql .= ' where ' . $condition;
			}
		}
		$this->flush();
		return $this->_db->query($sql);
	}

}