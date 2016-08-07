<?php
/**
* 对象关系映射模型
* ======
* @author 洪波
* @version 16.07.06
*/
namespace core;

class Orm
{
	//静态化实例
	private static $_instance;
	//数据库驱动实例
	private $_db;
	//操作表名称
	private $table_name = '';
	//动态记录数组
	private $ar = array();
	//动态记录主键
	public $pk = '';
	//动态记录模式
	private $active = false;
	
	/**
	* 静态获取实例对象
	* ======
	* @param $table_name	操作表名称
	* @param $new 			是否全新创建
	* @param $db_config 	数据库配置
	* ======
	* @return object-self
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public static function model($table_name, $new = false, $db_config = 'database')
	{
		if(! (self::$_instance instanceof self) || $new)
		{
			if(self::$_instance)
			{
				self::$_instance->_db == null;
				self::$_instance = null;
			}	
			self::$_instance = new self($table_name, $db_config);
		}
		self::$_instance->setTable($table_name);
		return self::$_instance;
	}
	
	/**
	* 私有化构造方法
	* ======
	* @param $table_name	操作表名称
	* @param $db_config 	数据库配置
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	private function __construct($table_name, $db_config)
	{
		//设置表名称
		$this->table_name = $table_name;
		//初始化化数据库驱动
		$this->initDriver($db_config);
		//获取表结构
		$this->struct();
	}

	/**
	* 初始化化数据库驱动
	* ======
	* @param $db_config 	数据库配置
	* @param $new 			是否全新加载数据库驱动
	* ======
	* @author 洪波
	* @version 16.07.06
	*/
	public function initDriver($db_config)
	{
		//加载数据库依赖
		if(! Autumn::app()->config($db_config))
		{
			Autumn::app()->exception('缺少数据库配置文件');
		}
		$driver = '\core\\' . Autumn::app()->config($db_config)['driver'];
		//载入数据库驱动（需要实现Db接口）
		$this->_db = new $driver($db_config);
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
		$this->ar[$name] = htmlspecialchars(addslashes($value));
	}

	/**
	* 获取数据库驱动对象
	* ======
	* ======
	* @author 洪波
	* @version 16.04.25
	*/
	public function getDb()
	{
		return $this->_db;
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
	* 设置操作表名称
	* ======
	* @param $table_name 	操作表名称
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function setTable($table_name)
	{
		if($this->table_name != $table_name)
		{
			$this->table_name = $table_name;
			$this->struct();
		}
		
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
		$this->active = false;
	}

	/**
	* 获取表结构
	* ======
	* @author 洪波
	* @version 16.03.30
	*/
	private function struct()
	{
		$this->flush();
		//获取表结构
		$st = $this->_db->queryAll('desc ' . $this->table_name);
		if($st)
		{
			$number = array('int', 'tinyint', 'smallint', 'mediumint', 'bigint', 'float', 'double', 'decimal');
			foreach ($st as $v)
			{
				//判定字段类型
				$d = '';
				$t = explode('(', $v->Type)[0];
				if(in_array($t, $number))
				{
					$d = 0;
				}
				//判定主键
				if($v->Key == 'PRI')
				{
					$this->pk = $v->Field;
					#设置char(13)主键默认值为uniqid()
					if($v->Type == 'char(13)')
					{
						$d = uniqid();
					}
				}
				//设置默认值
				$this->ar[$v->Field] = $d;
			}
			unset($st);
		}
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
	* @param $is_active 	是否动态映射
	* ======
	* @return stdClass object
	* ======
	* @author 洪波
	* @version 16.02.26
	*/
	public function find($condition = null, $is_active = false)
	{
		//ActiveRecord模式
		$this->active = $is_active;
		
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
		
		if($this->active)
		{
			if($result)
			{
				$this->ar = (array) $result;
				return self::$_instance;
			}
			else
			{
				return false;
			}
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
		//如果不是ActiveRecord模式，则新插入数据
		if(! $this->active)
		{
			$field = '';
			$value = '';
			foreach ($this->ar as $k => $v)
			{
				$field .= ',' . $k;
				$value .= ",'" . $v . "'";
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
		//$this->flush();
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
		if($this->active)
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
		//$this->flush();
		return $this->_db->query($sql);
	}

}