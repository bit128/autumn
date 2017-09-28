<?php
/**
* 查询规则类
* ======
* @author 洪波
* @version 16.02.25
*/
namespace core\db;

class Criteria {
	//查询字段
	public $select = '*';
	//联合
	public $union = '';
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
	* @param $key 		字段
	* @param $value 	条件值
	* @param $symbol 	连接符
	* @param $operator 	操作符
	* ======
	* @author 洪波
	* @version 16.04.12
	*/
	public function add($key, $value, $symbol = '=', $operator = 'AND') {
		$this->addCondition($key . $symbol . "'" . addslashes($value) . "'", $operator);
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
	public function addIn($column, Array $valuses, $operator = 'AND') {
		$condition = $column . " IN ('" . implode("','", $valuses) . "')";
		$this->addCondition($condition, $operator);
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
	public function addCondition($condition, $operator = 'AND') {
		if($this->condition != '') {

			$this->condition = '(' . $this->condition . ') ' . $operator . ' (' . $condition . ')';
		} else {
			$this->condition = $condition;
		}
	}

	/**
	* 联合表操作
	* ======
	* @param $table_name 	联合表名称
	* @param $foreign 		外键关系
	* @param $type 			联合方式 - inner | left | right
	* ======
	* @author 洪波
	* @version 16.08.18
	*/
	public function union($table_name, $foreign, $type = 'inner') {
		$this->union = $type . ' join ' . $table_name . ' on ' . $foreign;
	}

}