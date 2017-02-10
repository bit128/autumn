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
	const FRAMEWORK_VERSION = 1.4;

	//Autumn实例
	private static $_instance = null;
	//全局配置
	private $config_list = array();
	//默认控制器
	public $controller = '';
	//默认action
	public $action = '';
	//url参数表
	public $query_params = array();
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
	* 载入全局配置变量
	* ======
	* @author 洪波
	* @version 15.02.26
	*/
	public function __construct($config)
	{
		if($config)
		{
			$this->config_list = $config;
			$this->controller = $config['router']['controller'];
			$this->action = $config['router']['action'];
			//设置加载类路径
			spl_autoload_register(function($classname){
				$file = str_replace('\\', '/', $classname) . '.php';
				if(is_file($file))
				{
					require_once($file);
				}
				else
				{
					$this->exception($classname . ' 类没有找到，请检查命名空间或确认引入路径是否正确');
				}
			});
			//内部异常机制
			set_error_handler(function($level, $message, $file, $line, $context){
				if($this->config('debug'))
				{
					$content = '<p>异常等级：' . $level . '</p><p style="font-size:18px">'
						. $message . '</p><p>' . $file . ' (第 ' . $line . ' 行)</p>';
					$this->exception($content);
				}
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
		//路由控制器
		$this->start();
	}

	/**
	* 执行控制器action
	* ======
	* @author 洪波
	* @version 16.12.16
	*/
	private function start()
	{
		$class = 'app\controllers\\' . ucfirst($this->controller) . 'Controller';
		if($this->controller != '' && class_exists($class))
		{
			new $class($this->action);
		}
		else
		{
			Autumn::app()->exception('404.1 您访问的页面不见了，呜呜～～');
		}
	}

	/**
	* 请求转发
	* ======
	* @param $controller 	新控制器名称
	* @param $action 		动作名称
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function dispatch($controller, $action)
	{
		$this->controller = $controller;
		$this->action = $action;
		$this->start();
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
		//用户路由规则
		$custom_key = isset($rc['custom_route']) ? array_keys($rc['custom_route']) : null;

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
				if ($custom_key && in_array($v, $custom_key))
				{
					$dc = explode('/', $rc['custom_route'][$v]);
					if($dc)
					{
						$this->controller = $dc[0];
						if (count($dc) > 1)
						{
							$this->action = $dc[1];
						}
						else
						{
							$this->action = 'index';
						}
						$parse_count = 0;
					}
				}
				else
				{
					$this->controller = $v;
					-- $parse_count;
				}
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
	* 获取系统配置项
	* ======
	* @param $key 	配置键
	* ======
	* @author 洪波
	* @version 15.02.25
	*/
	public function config($key, $default = '')
	{
		if(isset($this->config_list[$key]))
		{
			return $this->config_list[$key];
		}
		else
		{
			return $default;
		}
	}

	/**
	* 扩展用户配置项
	* ======
	* @param $key 	配置键
	* @param $value 配置值
	* ======
	* @author 洪波
	* @version 17.01.01
	*/
	public function extendConfig($key, $value)
	{
		$this->config_list[$key] = $value;
	}

	/**
	* 系统异常处理
	* ======
	* @author 洪波
	* @version 17.01.10
	*/
	public function exception($content, $interrupt = true)
	{
		header("Content-Type:text/html; charset=utf-8");
		$view = 'app/views/exception.php';
		if(is_file($view))
		{
			extract(array(
				'error_detail' => $content,
				'system_version' => self::FRAMEWORK_VERSION
			), EXTR_PREFIX_SAME, 'data');
			ob_start();
			ob_implicit_flush(false);
			require($view);
			echo ob_get_clean();
		}
		else
		{
			echo '<div style="text-align:center;padding:10px;border:1px dashed #ccc;color:#ff4e00;background:#eee;">',
				'<p style="color:#666;"><strong style="font-size:20px;">警告：系统异常</strong></p>',
				'<div style="border-top:1px dashed #ccc; padding:20px;">',$content,'</div>',
				'<p style="color:#999;"><small>Autumn版本：',self::FRAMEWORK_VERSION,'</small></p></div>';
		}
		if($interrupt)
		{
			exit;
		}
	}
}