<?php
class M_user extends Model
{
	/**
	* 动态模型添加
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	public function add()
	{
		return 'hello,world!';
	}

	/**
	* 动态模型获取
	* ======
	* @param $id 模型id
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	public function get($id){}

	/**
	* 动态模型获取列表
	* ======
	* @param $offset 	游标位置
	* @param $limit 	偏移量
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	public function getList($offset, $limit){}

	/**
	* 动态模型修改
	* ======
	* @param $id 模型id
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	public function update($id){}

	/**
	* 动态模型删除
	* ======
	* @param $id 模型id
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	public function delete($id){}
}