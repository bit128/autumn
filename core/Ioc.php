<?php
/**
* 控制反转（依赖注入）核心类
* ======
* @author 洪波
* @version 16.03.02
*/
class Ioc
{
	//类静态实例
	private static $_instance;

	private function __construct()
	{}

	/**
	* 静态单例获取缓存实例
	* ======
	* @author 洪波
	* @version 16.03.01
	*/
	public static function inst()
	{
		if(! (self::$_instance instanceof self))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}