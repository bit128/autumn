<?php
namespace core;
/**
* 核心类
* ======
* @author 洪波
* @version 16.02.25
*/
class Autumn
{
	const FRAMEWORK_VERSION = 1.10;

	//Autumn实例
	private static $_instance = null;
	//全局配置
	private $_config = array();

	//默认控制器
	public $controller = '';
	//默认action
	public $action = '';
	//url参数表
	public $query_params = array();

	/**
	* Autumn构造方法
	* 载入全局配置变量
	* ======
	* @author 洪波
	* @version 15.02.26
	*/
	public function __construct($config)
	{
		if($config)
		{
			$this->_config = $config;
			$this->controller = $config['router']['controller'];
			$this->action = $config['router']['action'];
			//设置加载类路径
			spl_autoload_register(function($classname){
				$file = str_replace('\\', '/', $classname) . '.php';
				if(is_file($file))
				{
					require_once($file);
				}
				/*
				foreach (Autumn::app()->config('import') as $v)
				{
					$file = $v . $classname . '.php';
					if(is_file($file))
					{
						require_once($file);
					}
				}*/
			});
		}
	}

	/**
	* 静态获取Autumn实例
	* ======
	* @param $config 	全局配置文件
	* ======
	* @author 洪波
	* @version 16.02.26
	*/
	public static function app($config = array())
	{
		//如果实例不存在，则全新实例化
		if(! (self::$_instance instanceof self))
		{
			self::$_instance = new self($config);
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
		if($this->config('session_start'))
		{
			session_start();
		}
		//解析url
		$this->parseUrl();
		//url路由
		$class = 'app\controllers\\' . ucfirst($this->controller) . 'Controller';
		if($this->controller != '' && class_exists($class))
		{
			//记录访问日志
			$this->log(Log::LEVEL_DEVLOPER, 'New UV');
			$this->log(Log::LEVEL_DEBUG, 'View:' . $this->controller . '/' . $this->action);
			//实例化控制器
			new $class($this->controller, $this->action);
		}
		else
		{
			Autumn::app()->exception('Controller不存在，请检查URL是否正确');
		}
	}

	/**
	* 解析url
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	private function parseUrl()
	{
		$rc = $this->config('router');
		$url = str_replace($rc['index'], '', $_SERVER['REQUEST_URI']);
		//去除query string
		if($c = strpos($url, '?'))
		{
			$url = substr($url, 0, $c);
		}
		$url_param = explode('/', $url);
		$parse_count = 2;
		$query = '';
		foreach ($url_param as $v)
		{
			if($v == '')
			{
				continue;
			}
			//获取请求参数
			if($parse_count == 0)
			{
				if($query == '')
				{
					$query = $v; 
				}
				else
				{
					$this->query_params[$query] = $v;
					$query = '';
				}
			}
			//获取controller名称
			else if ($parse_count == 2)
			{
				$this->controller = $v;
				-- $parse_count;
			}
			//获取action名称
			else if ($parse_count == 1)
			{
				$this->action = $v;
				-- $parse_count;
			}
		}
	}

	/**
	* 记录系统日志
	* ======
	* @param $level 	过滤级别
	* @param $content 	内容
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	public function log($level, $content)
	{
		if($c = $this->config('log'))
		{
			if($c['enable'])
			{
				Log::inst()->systemRecord($level, $content);
			}
		}
	}

	/**
	* 获取系统配置项
	* ======
	* @param $key 	配置键
	* ======
	* @author 洪波
	* @version 15.02.25
	*/
	public function config($key, $default = '')
	{
		if(isset($this->_config[$key]))
		{
			return $this->_config[$key];
		}
		else
		{
			return $default;
		}
	}

	/**
	* 系统异常处理
	* ======
	* @author 洪波
	* @version 16.03.30
	*/
	public function exception($content, $interrupt = true)
	{
		header("Content-Type:text/html; charset=utf-8");
		echo '<div style="text-align:center;padding:10px;border:1px dashed #ccc;color:#ff4e00;background:#eee;">',
			'<p style="color:#666;"><strong style="font-size:20px;">警告：系统异常</strong></p>',
			'<div style="border-top:1px dashed #ccc; padding:20px;">',$content,'</div>',
			'<p style="color:#999;"><small>Autumn版本：',self::FRAMEWORK_VERSION,'</small></p></div>';
		//记录异常日志
		Log::inst()->record($content, '0001', Log::TAG_SYSTEM);
		if($interrupt)
		{
			exit;
		}
	}
}