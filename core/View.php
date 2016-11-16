<?php
/**
* 视图解析器
* ======
* @author 洪波
* @version 16.03.29
*/
namespace core;

class View
{
	//布局模板
	private $layout_name = 'layout';

	//视图配置信息
	private $config;

	//控制器名称
	private $controller = '';

	//路由
	public $route = '';

	/**
	* 构造方法设置控制器名称
	* ======
	* @param $layout_name 控制器名称
	* ======
	* @author 洪波
	* @version 16.03.29
	*/
	public function __construct($layout_name = '')
	{
		if($layout_name != '')
		{
			$this->layout_name = $layout_name;
		}
		//视图配置
		$this->config = Autumn::app()->config('view');
		//控制器名称
		$this->controller = Autumn::app()->controller;
	}

	/**
	* 静态获取视图解析器实例
	* ======
	* @param $layout_name 	布局名称
	* ======
	* @author 洪波
	* @version 16.03.29
	*/
	public static function layout($layout_name = '')
	{
		return new self($layout_name);
	}

	/**
	* 渲染布局视图
	* ======
	* @param $view 	视图名称
	* @param $data 	参数列表
	* ======
	* @author 洪波
	* @version 15.02.25
	*/
	public function render($view, $data = array(), $output = true)
	{
		$layout = ROOT . $this->config['path'] . $this->layout_name . '.php';
		if(is_file($layout))
		{
			$content = $this->renderPartial($view, $data, false);
			//layout加载用户变量
			if(is_array($data))
			{
				extract($data, EXTR_PREFIX_SAME, 'data');
			}
			//加载layout视图
			ob_start();
			ob_implicit_flush(false);
			include($layout);
			if($output)
			{
				echo ob_get_clean();
			}
			else
			{
				return ob_get_clean();
			}
		}
		else
		{
			Autumn::app()->exception('视图模板未找到，请检查路径');
		}
	}

	/**
	* 渲染单视图
	* ======
	* @param $view 	视图名称
	* @param $data 	参数列表
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function renderPartial($view, $data = array(), $output = true)
	{
		$this->route = $this->controller . DIRECTORY_SEPARATOR . $view;
		$view = ROOT . $this->config['path'] . $this->route . '.php';
		if(is_file($view))
		{
			//载入用户变量
			if(is_array($data))
			{
				extract($data, EXTR_PREFIX_SAME, 'data');
			}
			//渲染视图内容
			header("Content-Type:text/html; charset=UTF-8");
			ob_start();
			ob_implicit_flush(false);
			require($view);
			if($output)
			{
				echo ob_get_clean();
			}
			else
			{
				return ob_get_clean();
			}
		}
		else
		{
			Autumn::app()->exception('视图文件未找到，请检查路径');
		}
	}

	/**
	* 渲染可缓存视图
	* ======
	* @param $view 		视图名称
	* @param $data 		参数列表
	* @param $partial 	是否单一视图
	* ======
	* @author 洪波
	* @version 16.03.29
	*/
	public function renderCache($view, $data = array(), $partial = false)
	{
		//如果视图通过post请求，则不使用缓存策略
		if(isset($_POST) && $_POST)
		{
			if($partial)
			{
				$this->renderPartial($view, $data);
			}
			else
			{
				$this->render($view, $data);
			}
		}
		//否则开启缓存机制
		else
		{
			$cache = $this->getCachePath($view);
			//判断缓存文件是否存在，是否过期
			if(is_file($cache)
				&& time() - filemtime($cache) < $this->config['cache_limit'])
			{
				header("Content-Type:text/html; charset=UTF-8");
				echo file_get_contents($cache);
			}
			else
			{
				$content = '';
				if($partial)
				{
					$content = $this->renderPartial($view, $data, false);
				}
				else
				{
					$content = $this->render($view, $data, false);
				}
				if($content)
				{
					//缓存视图文件
					file_put_contents($cache, $content);
					//输出视图
					echo $content;
				}
			}
		}
	}

	/**
	* 判断是否有有效缓存
	* ======
	* @param $view 	视图名称
	* ======
	* @author 洪波
	* @version 16.11.04
	*/
	public function hasCache($view)
	{
		$cache = $this->getCachePath($view);
		if(is_file($cache)
			&& time() - filemtime($cache) < $this->config['cache_limit'])
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	* 获取缓存绝对路径
	* ======
	* @param $view 	视图名称
	* ======
	* @author 洪波
	* @version 16.11.04
	*/
	private function getCachePath($view)
	{
		//尝试读取缓存
		$cache = ROOT . $this->config['cache_dir'] . $this->controller . '_' . $view;
		foreach (array_merge(Autumn::app()->query_params, $_GET) as $k => $v)
		{
			$cache .= '_' . $k . '_' . $v;
		}
		$cache .= '.html';
		return $cache;
	}

}