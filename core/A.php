<?php
/**
* 核心别名类
* ======
* @author 洪波
* @version 16.02.25
*/
namespace core;

class A {

	/**
	 * 获取Autumn静态实例简化方法
	 * ======
	 * @author 洪波
	 * @version 19.11.21
	 */
	public static function i () {
		return Autumn::app();
	}

	/**
	 * 获取配置项简化方法
	 * ======
	 * @author 洪波
	 * @version 19.11.21
	 */
	public static function c() {
		return Autumn::app()->config;
	}

	/**
	 * 获取日志简化方法
	 * ======
	 * @author 洪波
	 * @version 19.11.21
	 */
	public static function l() {
		return Autumn::app()->log;
	}

	/**
	 * 获取异常简化方法
	 * ======
	 * @author 洪波
	 * @version 19.11.21
	 */
	public static function e() {
		return Autumn::app()->exception;
	}

	/**
	 * 获取请求简化方法
	 * ======
	 * @author 洪波
	 * @version 19.11.21
	 */
	public static function req() {
		return Autumn::app()->request;
	}

	/**
	 * 获取响应简化方法
	 * ======
	 * @author 洪波
	 * @version 19.11.21
	 */
	public static function res() {
		return Autumn::app()->response;
	}
}