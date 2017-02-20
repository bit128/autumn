<?php
/**
* 路由
* ======
* @author 洪波
* @version 17.02.20
*/
namespace core;

class Route
{
    //默认控制器
	public $controller = '';
	//默认action
	public $action = '';
	//url参数表
	public $query_params = array();

    /**
    * 构造方法加载配置
    * ======
    * @author 洪波
    * @version 17.02.20
    */
    public function __construct()
    {
        $this->controller = Autumn::app()->config->get('router.controller');
        $this->action = Autumn::app()->config->get('router.action');
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
        else
        {
            $this->controller = $controller;
        }
        //载入控制器类
		$class = 'app\controllers\\' . ucfirst($this->controller) . 'Controller';
		if($this->controller != '' && class_exists($class))
		{
			new $class($this->action);
		}
		else
		{
			Autumn::app()->exception->throws('404.1 您访问的页面不见了，呜呜～～');
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
		$rc = Autumn::app()->config->get('router');
		//用户路由规则
		$custom_key = isset($rc['route_alias']) ? array_keys($rc['route_alias']) : null;

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
					$dc = explode('/', $rc['route_alias'][$v]);
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
}