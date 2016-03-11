<?php
/**
* 控制器
* ======
* @author 洪波
* @version 15.02.25
*/
class Controller
{
	//布局模板
	public $layout_name = 'layout';
	//路由名
	public $route;

	/**
	* 构造方法
	* ======
	* @param $controller_name 	控制器名称
	* @param $action_name 		动作名称
	* ======
	* @author 洪波
	* @version 16.03.09
	*/
	public function __construct($controller_name, $action_name)
	{
		$this->route = $controller_name . '/' . $action_name;
		//初始化控制器
		$this->init();
		//执行动作
		$action = 'action' . ucfirst($action_name);
		$this->$action();
	}

	/**
	* 控制器初始化方法
	* 子类重写用来替代构造方法
	* ======
	* @author 洪波
	* @version 16.03.09
	*/
	public function init(){}

	/**
	* 判断是否是post请求
	* ======
	* @author 洪波
	* @version 16.03.10
	*/
	public function isPostRequest()
	{
		return isset($_POST);
	}
	
	/**
	* 获取get请求参数
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function getQuery($key, $default = '')
	{
		$value = $default;
		if (isset(Autumn::app()->query_params[$key]))
		{
			$value = Autumn::app()->query_params[$key];
		}
		else if (isset($_GET) && isset($_GET[$key]))
		{
			$value = $_GET[$key];
		}
		return $value;
	}

	/**
	* 获取post请求参数
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function getPost($key, $default = '')
	{
		if(isset($_POST) && isset($_POST[$key]))
		{
			return $_POST[$key];
		}
		else
		{
			return $default;
		}
	}

	/**
	* 获取请求参数
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function getParam($key, $default = '')
	{
		$value = $this->getQuery($key);
		if($value == '')
		{
			$value = $this->getPost($key);
		}

		return $value != '' ? $value : $default;
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
	public function render($view, $data = array())
	{
		$layout = ROOT . Autumn::app()->config('view_path') . $this->layout_name . '.php';
		if(file_exists($layout))
		{
			$content = $this->renderPartial($view, $data, false);
			//加载layout视图
			ob_start();
			ob_implicit_flush(false);
			include($layout);
			echo ob_get_clean();
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
		$view = ROOT . Autumn::app()->config('view_path') . Autumn::app()->controller . '/' . $view . '.php';
		if(file_exists($view))
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
	}

}