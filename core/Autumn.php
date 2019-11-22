<?php
/**
* 核心类
* ======
* @author 洪波
* @version 16.02.25
*/
namespace core;

class Autumn {
	
	const FRAMEWORK_VERSION = '1.9.4';

	//Autumn实例
	private static $_instance = null;
	
	//核心对象实例栈
	private $core_instance = [];

	//核心对象实例包
	private $core_class = [
		'config' => 'core\Config',
		'exception' => 'core\Exception',
		'request' => 'core\http\Request',
		'response' => 'core\http\Response'
	];

	/**
	* [单例]获取Autumn托管的核心对象实例
	* ======
	* @author 洪波
	* @version 16.11.16
	*/
	public function __get($module_name) {
		if(isset($this->core_instance[$module_name])) {
			return $this->core_instance[$module_name];
		} else {
			if ($module_name != 'config' && $c = Autumn::app()->config->get('module.' . $module_name)) {
				$this->core_instance[$module_name] = new $c['class']($c);
				return $this->core_instance[$module_name];
			} else {
				if(isset($this->core_class[$module_name]) && class_exists($this->core_class[$module_name])) {
					$this->core_instance[$module_name] = new $this->core_class[$module_name];
					return $this->core_instance[$module_name];
				}
			}
		}
	}

	/**
	* Autumn构造方法
	* ======
	* @author 洪波
	* @version 16.02.26
	*/
	public function __construct() {
		spl_autoload_register(function($classname) {
			$file = str_replace('\\', '/', $classname) . '.php';
			if(is_file($file)) {
				require_once($file);
			} else {
				Autumn::app()->exception->throws($classname . ' 类没有找到，请检查命名空间或确认引入路径是否正确');
			}
		});
		//内部异常机制
		set_error_handler(function($level, $message, $file, $line, $context){
			$content = '<p>异常等级：' . $level . '</p><p style="font-size:18px">'
				. $message . '</p><p>' . $file . ' (第 ' . $line . ' 行)</p>';
			Autumn::app()->exception->throws($content);
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
	public static function app() {
		//如果实例不存在，则全新实例化
		if(! (self::$_instance instanceof self)) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	/**
	* 运行application
	* ======
	* @author 洪波
	* @version 19.11.21
	*/
	public function run() {
		if (Autumn::app()->config->isEnabled) {
			if (Autumn::app()->config->get('ip_filter.enabled')) {
				if (\in_array(Autumn::app()->request->getIp(), require_once(Autumn::app()->config->get('ip_filter.ip_list')))) {
					Autumn::app()->exception->throws('服务器繁忙，请稍后重试~');
				}
			}
			if(Autumn::app()->config->get('session_start')) {
				session_start();
			}
			Autumn::app()->route->start();
		} else {
			Autumn::app()->config->add('debug', true);
			Autumn::app()->exception->throws('未找到默认配置文件：' . Config::DEFAULT_CONFIG);
		}
	}
}