<?php
/**
* 核心类
* ======
* @author 洪波
* @version 16.02.25
*/
class Autumn
{
	const FRAMEWORK_VERSION = 1.01;

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
	//单例对象数组
	private $models = array();

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
			spl_autoload_register('Loader::autoload');
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
	* @version 16.02.26
	*/
	public function run()
	{
		$this->log(2, 'New UV');
		$this->parseUrl();
		$this->router();
	}

	/**
	* 路由器
	* ======
	* @author 洪波
	* @version 15.02.25
	*/
	private function router()
	{
		if($this->controller != '')
		{
			$class = ucfirst($this->controller) . 'Controller';
			$obj = new $class($this->controller, $this->action);
			//记录访问日志
			$this->log(3, 'View:' . $this->controller . '/' . $this->action);
		}
	}

	/**
	* url解析解析
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	private function parseUrl()
	{
		$index = $this->config('router')['index'];
		$url = str_replace($index, '', $_SERVER['REQUEST_URI']);
		//去除query string
		if($c = strpos($url, '?'))
		{
			$url = substr($url, 0, $c);
		}
		$url_param = array_filter(explode('/', $url));
		$parse_count = 2;
		$query = '';
		foreach ($url_param as $v)
		{
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
}

/**
* 类加载器
* ======
* @author 洪波
* @version 16.03.11
*/
class Loader
{
	public static function autoload($class)
	{
		foreach (Autumn::app()->config('import') as $v)
		{
			$file = $v . $class . '.php';
			if(is_file($file))
			{
				require_once($file);
			}
		}
	}
}