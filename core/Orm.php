<?php
/**
* 对象关系映射模型
* ======
* @author 洪波
* @version 16.07.06
*/
namespace core;

class Orm extends Model
{
    //静态化实例
	private static $_instance;
    //表主键
    protected $pk = '';
    //是否有动态记录
    protected $has_record = false;

    public function __construct()
    {
        if ($this->table_name != '')
        {
            $this->struct();
        }
    }

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
	* @version 16.02.25 - 16.12.07
	*/
	public static function model($table_name = '')
	{
		if ($table_name != '')
        {
            self::$_instance = new self;
            self::$_instance->table_name = $table_name;
            self::$_instance->struct();
        }
        else
        {
            $sub_class = get_called_class();
            self::$_instance = new $sub_class;
        }
        return self::$_instance;
	}

    /**
    * 获取数据看连接对象
    * ======
    * @author 洪波
    * @version 17.02.21
    */
    public function getDb()
    {
        return Autumn::app()->database;
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
	* 从数组加载键值到模型中
	* ======
	* @param $data 键值
	* ======
	* @author 洪波
	* @version 17.02.21
	*/
	public function load($data)
	{
		foreach ($this->ar as $key => $value)
		{
			if (isset($data[$key]))
			{
				$this->ar[$key] = $data[$key];
			}
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
			if($condition instanceof Criteria)
			{
				$sql = "select " . $condition->select . " from " . $this->table_name;
				if($condition->union)
					$sql .= ' ' . $condition->union;
				if($condition->condition)
					$sql .= ' where ' . $condition->condition;
			}
			else
			{
				$sql .= ' where ' . $condition;
			}
		}

		$result = $this->getDb()->queryRow($sql);
		
		if($result)
        {
            $this->has_record = true;
            $this->ar = (array) $result;
            return self::$_instance;
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
			if($condition instanceof Criteria)
			{
				$sql = "select " . $condition->select . " from " . $this->table_name;
				if($condition->union)
					$sql .= ' ' . $condition->union;
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
			unset($this->ar[$this->pk]);
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

}