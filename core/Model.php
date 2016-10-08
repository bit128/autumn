<?php
/**
* 模型基础类
* ======
* @author 洪波
* @version 16.03.02
*/
namespace core;

abstract class Model
{
	//模型属性
	public $table_name;

	/**
	* 统计行记录数量
	* ======
	* @param $criteria 	查询条件对象
	* ======
	* @author 洪波
	* @version 16.06.28
	*/
	public function count($criteria = null)
	{
		return Orm::model($this->table_name)->count($criteria);
	}

	/**
	* 自动插入表记录
	* ======
	* @param $data 	表字段数据
	* @param $post 	是否接受post表单数据
	* ======
	* @author 洪波
	* @version 16.03.30
	*/
	public function insert($data = array(), $post = true)
	{
		$orm = Orm::model($this->table_name, true);

		foreach($orm->getAttributes() as $k => $v)
		{
			//处理来自POST请求参数
			if($post && isset($_POST[$k]))
			{
				$orm->$k = $_POST[$k];
			}
			//处理data数据
			if($data && isset($data[$k]))
			{
				$orm->$k = $data[$k];
			}
		}
		if($orm->save())
		{
			$pk = $orm->pk;
			return $orm->$pk;
		}
		else
		{
			return 0;
		}
	}

	/**
	* （通过主键）获取单行记录
	* ======
	* @param $pk_id 	主键id
	* ======
	* @author 洪波
	* @version 16.03.30
	*/
	public function get($pk_id)
	{
		$orm = Orm::model($this->table_name);
		$pk = $orm->pk;
		return $orm->find($pk . "='" . $pk_id . "'");
	}

	/**
	* 获取记录列表
	* ======
	* @param $offset 	游标位置
	* @param $limit 	偏移量
	* @param $criteria 	查询条件对象
	* ======
	* @author 洪波
	* @version 16.03.30
	*/
	public function getList($offset, $limit, $criteria = null)
	{
		//统计数量
		$count = $this->count($criteria);
		//分页
		if(!($criteria instanceof Criteria))
		{
			$criteria = new Criteria;
		}
		$criteria->offset = $offset;
		$criteria->limit = $limit;
		//获取列表
		$list = Orm::model($this->table_name)->findAll($criteria);
		//返回结果
		return array(
			'count' => $count,
			'result' => $list
			);
	}

	/**
	* （通过主键）更新记录
	* ======
	* @param $pk_id 	主键id
	* ======
	* @author 洪波
	* @version 16.03.30
	*/
	public function update($pk_id, $data)
	{
		$orm = Orm::model($this->table_name);
		$pk = $orm->pk;
		return $orm->UpdateAll($data, $pk . "='" . $pk_id . "'");
	}

	/**
	* （通过主键）删除记录
	* ======
	* @param $pk_id 	主键id
	* ======
	* @author 洪波
	* @version 16.03.30
	*/
	public function delete($pk_id)
	{
		$orm = Orm::model($this->table_name);
		$pk = $orm->pk;
		return $orm->deleteAll($pk . "='" . $pk_id . "'");
	}

}