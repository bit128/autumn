<?php
/**
* 核心类
* ======
* @author 洪波
* @version 16.02.25
*/
namespace core;

class Autumn
{
	const FRAMEWORK_VERSION = 1.6;

	//Autumn实例
	private static $_instance = null;
	
	//核心对象实例栈
	private $core_instance = array();

	/**
	* [单例]获取Autumn托管的核心对象实例
	* ======
	* @author 洪波
	* @version 16.11.16
	*/
	public function __get($core_name)
	{
		if(isset($this->core_instance[$core_name]))
		{
			return $this->core_instance[$core_name];
		}
		else
		{
			$class = 'core\\' . ucfirst($core_name);
			if(class_exists($class))
			{
				$this->core_instance[$core_name] = new $class;
				return $this->core_instance[$core_name];
			}
		}
	}

	/**
	* Autumn构造方法
	* ======
	* @author 洪波
	* @version 16.02.26
	*/
	public function __construct()
	{
		spl_autoload_register(function($classname){
			$file = str_replace('\\', '/', $classname) . '.php';
			if(is_file($file))
			{
				require_once($file);
			}
			else
			{
				Autumn::app()->exception->throws($classname . ' 类没有找到，请检查命名空间或确认引入路径是否正确');
			}
		});
		//内部异常机制
		set_error_handler(function($level, $message, $file, $line, $context){
			if(AUTUMN_DEBUG)
			{
				$content = '<p>异常等级：' . $level . '</p><p style="font-size:18px">'
					. $message . '</p><p>' . $file . ' (第 ' . $line . ' 行)</p>';
				Autumn::app()->exception->throws($content);
			}
		});
	}

	/**
	* 静态获取Autumn实例
	* ======
	* @param $config 	全局配置文件
	* ======
	* @author 洪波
	* @version 16.02.26
	*/
	public static function app()
	{
		//如果实例不存在，则全新实例化
		if(! (self::$_instance instanceof self))
		{
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	/**
	* 运行application
	* ======
	* @author 洪波
	* @version 16.03.31
	*/
	public function run()
	{
		if(Autumn::app()->config->get('session_start'))
		{
			session_start();
		}
		Autumn::app()->route->start();
	}
}