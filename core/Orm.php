<?php
/**
* 对象关系映射模型
* ======
* @author 洪波
* @version 15.02.25
*/
class Orm
{
	//静态化实例
	public static $_instance;
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
	* @version 15.07.22
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
	* @version 15.07.22
	*/
	private function __construct($table_name)
	{
		//设置表名称
		$this->table_name = $table_name;
		//加载数据库依赖
		Autumn::app()->import('Mysql');
		//加载查询规则依赖
		Autumn::app()->import('Criteria');
	}
	
	/**
	* 获取动态记录值
	* ======
	* @param $name 记录健
	* ======
	* @return mixed
	* ======
	* @author 洪波
	* @version 15.07.22
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
	* @version 15.07.22
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
	* @version 15.07.23
	*/
	public function getAttributes()
	{
		return $this->ar;
	}
	
	/**
	* 刷新动态记录值
	* ======
	* @author 洪波
	* @version 15.07.22
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
	* @version 15.07.22
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
	* @version 15.07.22
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
		return Mysql::inst()->queryScalar($sql);
	}

	public function haha()
	{
		$criteria = new Criteria;
		$criteria->addCondition("name='hongbo'");
		$criteria->addCondition("age=12 OR gender=2");
		$criteria->addInCondition('age', array(11, 12, 13));

		echo $criteria->condition;
	}
}