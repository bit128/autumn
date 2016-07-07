<?php
/**
* 查询规则类
* ======
* @author 洪波
* @version 15.02.25
*/
namespace core;

class Criteria
{
	//查询字段
	public $select = '*';
	//条件
	public $condition = '';
	//排序
	public $order = '';
	//分组
	public $group = '';
	//游标位置
	public $offset = -1;
	//偏移量
	public $limit = -1;

	/**
	* 增加查询条件[过滤单引号]
	* ======
	* @author 洪波
	* @version 16.04.12
	*/
	public function add($key, $value, $operator = 'AND')
	{
		$this->addCondition($key . "='" . addslashes($value) . "'", $operator);
	}

	/**
	* 增加查询条件[不推荐直接使用]
	* ======
	* @param $condition 	新条件
	* @param $operator 		连接符号
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function addCondition($condition, $operator = 'AND')
	{
		if($this->condition != '')
		{

			$this->condition = '(' . $this->condition . ') ' . $operator . ' (' . $condition . ')';
		}
		else
		{
			$this->condition = $condition;
		}
	}

	/**
	* 增加in查询条件
	* ======
	* @param $column 	字段名
	* @param $values 	值集合
	* @param $operator 	连接符号
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function addInCondition($column, $valuses, $operator = 'AND')
	{
		$condition = $column . " IN ('" . implode("','", $valuses) . "')";
		if($this->condition != '')
		{
			$this->condition = '(' . $this->condition . ') ' . $operator . ' (' . $condition . ')';
		}
		else
		{
			$this->condition = $condition;
		}
	}
}