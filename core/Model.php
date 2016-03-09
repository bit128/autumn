<?php
/**
* 模型基础类
* ======
* @author 洪波
* @version 16.03.02
*/
abstract class Model
{
	//模型属性
	private $attributes = array();

	/**
	* 设置属性值
	* ======
	* @author 洪波
	* @version 16.03.09
	*/
	public function __set($key, $value)
	{
		$this->$attributes[$key] = $value;
	}

	/**
	* 设置属性值
	* ======
	* @author 洪波
	* @version 16.03.09
	*/
	public function __get($key)
	{
		if(isset($this->attributes[$key]))
		{
			return $this->attributes[$key];
		}
	}

	/**
	* 设置模型属性
	* ======
	* @param $attributes 	模型属性
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	public function setAttributes($attributes)
	{
		if(is_array($attributes))
			$this->attributes = $attributes;
	}

	/**
	* 获取模型属性
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	* 动态模型添加
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	abstract function add();

	/**
	* 动态模型获取
	* ======
	* @param $id 模型id
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	abstract function get($id);

	/**
	* 动态模型获取列表
	* ======
	* @param $offset 	游标位置
	* @param $limit 	偏移量
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	abstract function getList($offset, $limit);

	/**
	* 动态模型修改
	* ======
	* @param $id 模型id
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	abstract function update($id);

	/**
	* 动态模型删除
	* ======
	* @param $id 模型id
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	abstract function delete($id);

}