<?php
/**
* 数据库驱动接口
* ======
* @author 洪波
* @version 16.03.02
*/
namespace core\db;

interface Db {
	/**
	* 执行sql指令
	* ======
	* @author 洪波
	* @version 15.02.25
	*/
	public function query($sql);

	/**
	* 查询列数据
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function queryScalar($sql);

	/**
	* 查询行数据
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function queryRow($sql);

	/**
	* 查询二维行数据
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function queryAll($sql);
}