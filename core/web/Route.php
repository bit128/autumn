<?php
/**
* 路由
* ======
* @author 洪波
* @version 17.02.20
*/
namespace core\web;
use core\Autumn;

class Route
{
    //默认控制器
	public $controller = '';
	//默认action
	public $action = '';
	//url参数表
	public $query_params = [];
	//配置项
	private $config;

    /**
    * 构造方法加载配置
    * ======
    * @author 洪波
    * @version 17.02.20
    */
    public function __construct($config)
    {
        $this->controller = $config['controller'];
        $this->action = $config['action'];
		$this->config = $config;
    }

    /**
	* 执行控制器action
	* ======
	* @author 洪波
	* @version 16.12.16
	*/
	public function start($controller = '', $action = '')
	{
        if ($controller == '')
        {
            $this->parseUrl();
        }
		else if ($this->controller == $controller && $this->action == $action)
		{
			Autumn::app()->exception->throws('不能转发请求到相同的controller/action.');
		}
        else
        {
            $this->controller = $controller;
			$this->action = $action;
        }
        //加载控制器
		$controller_path = $this->config['path'] . ucfirst($this->controller) . 'Controller';
		if(is_file('./' . $controller_path . '.php'))
		{
			$action_name = 'action' . ucfirst($this->action);
			//反射控制器
			$controller_ref = new \ReflectionClass(str_replace('/', '\\', $controller_path));
			if ($controller_ref->hasMethod($action_name))
			{
				//反射action
				$action_ref = $controller_ref->getMethod($action_name);
				//反射参数列表，并绑定参数
				$param_list = [];
				foreach ($action_ref->getParameters() as $p)
				{
					$d = $p->isDefaultValueAvailable() ? $p->getDefaultValue() : '';
					$v = Autumn::app()->request->getQuery($p->getName(), $d);
					$param_list[] = $v;
				}
				$action_ref->invokeArgs($controller_ref->newInstance(), $param_list);
				unset($controller_ref);
				unset($action_ref);
			}
			else
			{
				$this->start($this->controller, 'notFound');
			}
		}
		else
		{
			$this->start($this->config['controller'], 'notFound');
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
		//用户路由规则
		$custom_key = isset($this->config['route_alias']) ? array_keys($this->config['route_alias']) : null;
		$url = str_replace($this->config['index'], '', $_SERVER['REQUEST_URI']);
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
					$dc = explode('/', $this->config['route_alias'][$v]);
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
	* 返回替换过键值的url
	* ======
	* @param $kvpair 键值对（如果值为null，表示移除对应的键值）
	* ======
	* @author 洪波
	* @version 17.09.25
	*/
	public function reUrl(array $kvpair)
	{
		$url = '/' . $this->controller . '/' . $this->action;
		foreach ($this->query_params + $_GET as $k => $v)
		{
			if (array_key_exists($k, $kvpair))
			{
				if ($kvpair[$k] !== NULL && $kvpair[$k] !== '')
				{
					$url .= '/' . $k . '/' . $kvpair[$k];
				}
				unset($kvpair[$k]);
			}
			else
			{
				if ($v != '')
					$url .= '/' . $k . '/' . $v;
			}
		}
		foreach ($kvpair as $k => $v)
		{
			if ($v !== NULL && $v !== '')
				$url .= '/' . $k . '/' . $v;
		}
		return $url;
	}
}