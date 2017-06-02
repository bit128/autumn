<?php
/**
* 对象关系映射模型
* ======
* @author 洪波
* @version 16.07.06
*/
namespace core\db;
use core\Autumn;

class Orm
{
	//模型表名称
	public $table_name;
    //表主键
    public $pk = '';
	//模型字段值
	public $ar = [];
    //是否有动态记录
    protected $has_record = false;

	/**
	* 构造方法 - 获取表结构
	* ======
	* @param $table_name 表名称
	* ======
	* @author 洪波
	* @version 17.03.02
	*/
    public function __construct($table_name)
	{
		$this->table_name = $table_name;
		$this->struct();
	}

	/**
	* 获取字段
	* ======
	* @param $key 	键
	* ======
	* @author 洪波
	* @version 17.02.21
	*/
	public function __get($key)
	{
		if (isset($this->ar[$key]))
		{
			return $this->ar[$key];
		}
	}

	/**
	* 设置字段
	* ======
	* @param $key 	键
	* @param $key 	值
	* ======
	* @author 洪波
	* @version 17.02.21
	*/
	public function __set($key, $value)
	{
		$this->ar[$key] = htmlspecialchars(addslashes($value));
	}

    /**
    * 获取数据库连接对象
    * ======
    * @author 洪波
    * @version 17.02.21
    */
    public function getDb()
    {
        return Autumn::app()->db;
    }

    /**
    * 获取表结构
    * ======
    * @author 洪波
    * @version 17.02.21
    */
    public function struct()
    {
        //获取表结构
		$st = $this->getDb()->queryAll('desc ' . $this->table_name);
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
		return $this->getDb()->queryScalar($sql);
	}

	/**
	* 构建查询条件
	* ======
	* @param $condition 查询条件 string | Criteria
	* @param $sql 		sql语句
	* ======
	* @author 洪波
	* @version 17.06.02
	*/
	private function buildCondition($condition, &$sql)
	{
		if($condition instanceof Criteria)
		{
			$sql = "select " . $condition->select . " from " . $this->table_name;
			if($condition->union)
				$sql .= ' ' . $condition->union;
			if($condition->condition)
				$sql .= ' where ' . $condition->condition;
			if($condition->group)
				$sql .= ' group by ' . $condition->group;
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

    /**
	* 查找单行记录
	* ======
	* @param $condition 	criteria对象 | string查询条件
	* ======
	* @return stdClass object
	* ======
	* @author 洪波
	* @version 16.02.26
	*/
	public function find($condition = null)
	{
		$sql = "select * from " . $this->table_name;
		if($condition)
		{
			$this->buildCondition($condition, $sql);
		}
		if($result = $this->getDb()->queryRow($sql))
        {
            $this->has_record = true;
            $this->ar = $result;
            return $this;
        }
        else
        {
            return false;
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
		$sql = "select * from " . $this->table_name;
		if($condition)
		{
			$this->buildCondition($condition, $sql);
		}
		return $this->getDb()->queryAll($sql);
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
		//如果有动态记录，则更新
		if($this->has_record)
		{
            $pk_val = $this->ar[$this->pk];
			//unset($this->ar[$this->pk]);
			return $this->updateAll($this->ar, "{$this->pk} = '{$pk_val}'");
		}
		//否则全新插入
		else
		{
			$field = '';
			$value = '';
			foreach ($this->ar as $k => $v)
			{
				$field .= ',' . $k;
				$value .= ",'" . $v . "'";
			}
			$sql = "insert into " . $this->table_name . " (" . substr($field, 1) . ") values (" . substr($value, 1) . ")";

			return $this->getDb()->query($sql);
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
		return $this->getDb()->query($sql);
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
		if($this->has_record)
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
		return $this->getDb()->query($sql);
	}

	/**
	* 以数组形式返回模型字段
	* ======
	* @author 洪波
	* @version 17.02.21
	*/
	public function toArray()
	{
		return $this->ar;
	}

}