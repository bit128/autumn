<?php
/**
* 查询规则类
* ======
* @author 洪波
* @version 20.04.11
*/
namespace core\db;

class Criteria {

	//条件连接方式
	const OPT_AND		= 'AND';
	const OPT_OR		= 'OR';

	//like查询匹配方式
	const MATCH_LEFT	= 1;
	const MATCH_RIGHT	= 2;
	const MATCH_ALL		= 4;

	//排序方式
	const ORDER_ASC		= 'asc';
	const ORDER_DESC	= 'desc';

	//联合查询方式
	const JOIN_INNER	= 'inner';
	const JOIN_LEFT		= 'left';
	const JOIN_RIGHT	= 'right';

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
	 * 查询字段
	 * ======
	 * @param $fields	字段列表
	 * ======
	 * @author 洪波
	 * @version 20.04.11
	 */
	public function select(Array $fields) {
		if ($fields) {
			$field = '';
			foreach ($fields as $f) {
				$field .= ',' . $f;
			}
		}
		$this->select = \substr($field, 1);
	}

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
	public function add($key, $value, $symbol = '=', $operator = self::OPT_AND) {
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
	public function addIn($column, Array $valuses, $operator = self::OPT_OR) {
		$condition = $column . " IN ('" . implode("','", $valuses) . "')";
		$this->addCondition($condition, $operator);
	}

	/**
	 * 模糊查询
	 * @param $key 		字段
	 * @param $value 	条件值
	 * @param $match 	匹配方式
	 * ======
	 * @author 洪波
	 * @version 20.04.11
	 */
	public function addLike($key, $value, $match = self::MATCH_ALL) {
		switch ($match) {
			case self::MATCH_ALL:
				$this->addCondition($key . " like '%" . $value . "%'");
				break;
			case self::MATCH_LEFT:
				$this->addCondition($key . " like '%" . $value . "'");
				break;
			case self::MATCH_RIGHT:
				$this->addCondition($key . " like '" . $value . "%'");
				break;
		}
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
	public function addCondition($condition, $operator = self::OPT_AND) {
		if($this->condition != '') {
			$this->condition = '(' . $this->condition . ') ' . $operator . ' (' . $condition . ')';
		} else {
			$this->condition = $condition;
		}
	}

	/**
	 * 分页查询
	 * ======
	 * @param $limit	分页偏移量
	 * @param $offset	分页位置
	 * ======
	 * @author 洪波
	 * @version 20.04.11
	 */
	public function pageLimit($limit, $offset = -1) {
		$this->limit = $limit;
		$this->offset = $offset;
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
	public function union($table_name, $foreign, $type = self::JOIN_INNER) {
		$this->union = $type . ' join ' . $table_name . ' on ' . $foreign;
	}

	/**
	 * 排序
	 * ======
	 * @param $field	排序字段
	 * @param $sort		排序方式
	 * ======
	 * @author 洪波
	 * @version 20.04.11
	 */
	public function orderBy($field, $sort = self::ORDER_ASC) {
		$this->order = $field . ' ' . $sort;
	}

	/**
	 * 构建表查询条件
	 * ======
	 * @param $table_name	表名称
	 * ======
	 * @author 洪波
	 * @version 20.04.11
	 */
	public function build($table_name) {
		$sql = "select " . $this->select . " from " . $table_name;
		return $sql . $this->buildWhere();
	}

	/**
	 * 构建查询条件
	 * ======
	 * @author 洪波
	 * @version 20.04.11
	 */
	public function buildWhere() {
		$where = '';
		if($this->union) {
			$where .= ' ' . $this->union;
		}
		if($this->condition) {
			$where .= ' where ' . $this->condition;
		}
		if($this->group) {
			$where .= ' group by ' . $this->group;
		}
		if($this->order) {
			$where .= ' order by ' . $this->order;
		}
		if($this->limit != -1) {
			$where .= ' limit ' . $this->limit;
		}
		if($this->offset != -1) {
			$where .= ',' . $this->offset;
		}
		return $where;
	}

}