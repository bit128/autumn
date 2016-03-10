<?php
/**
* 核心类
* ======
* @author 洪波
* @version 16.02.25
*/
class Autumn
{
	const FRAMEWORK_VERSION = 1.0;

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
			//载入核心类
			if($config['import'])
			{
				$this->imports($config['import']);
			}
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
	* 注入对象实例
	* ======
	* @param $model 	模块名
	* @param $single 	默认单例模式
	* ======
	* @author 洪波
	* @version 16.02.26
	*/
	public function model($model, $single = true)
	{
		$file = ROOT . $this->config('path')['model'] . strtolower($model) . '.php';
		//单例复用
		if($single && isset($this->models[$model]))
		{
			return $this->models[$model];
		}
		else if(file_exists($file))
		{
			if(! isset($this->models[$model]))
			{
				require_once($file);
			}
			$class = ucfirst($model);
			$this->models[$model] = new $class;
			return $this->models[$model];
		}
	}

	/**
	* 模块加载器
	* ======
	* @param $package 类名
	* ======
	* @author 洪波
	* @version 15.02.25
	*/
	public function import($package)
	{
		$package = ucfirst($package);
		$paths = array(
			ROOT . '/core/' . $package . '.php',
			ROOT . '/library/' . $package . '.php'
			);
		foreach ($paths as $p)
		{
			if(file_exists($p))
			{
				require_once($p);
			}
		}
	}

	/**
	* 模块加载器组
	* ======
	* @param $packages 	类名数组
	* ======
	* @author 洪波
	* @version 15.02.29
	*/
	public function imports($packages)
	{
		if(is_array($packages))
		{
			foreach ($packages as $v)
			{
				$this->import($v);
			}
		}
	}

	/**
	* 加载模型
	* ======
	* @param $mode 	模型 string | array
	* ======
	* @author 洪波
	* @version 16.03.10
	*/
	public function importModel($mode)
	{
		$path = ROOT . $this->config('path')['model'];
		if(is_array($mode))
		{
			foreach ($mode as $v)
			{
				if(file_exists($path . $v . '.php'))
				{
					require_once($path . $v . '.php');
				}
			}
		}
		else if(file_exists($path . $mode . '.php'))
		{
			require_once($path . $mode . '.php');
		}
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
			$cf = ROOT . $this->config('path')['controller'] . ucfirst($this->controller) . 'Controller.php';
			if(file_exists($cf))
			{
				require_once $cf;
				$class = ucfirst($this->controller) . 'Controller';
				$obj = new $class($this->controller, $this->action);
				//记录访问日志
				$this->log(3, 'View:' . $this->controller . '/' . $this->action);
			}
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
		//获取query string
		$index = $this->config('router')['index'];
		$url = str_replace($index, '', $_SERVER['REQUEST_URI']);
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