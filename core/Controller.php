<?php
namespace core;
/**
* 控制器
* ======
* @author 洪波
* @version 15.02.25
*/
class Controller
{
	const CODE_SUCCESS	= 1000; //响应码 - 成功
	const CODE_FAIL		= 1001; //响应码 - 失败

	/**
	* 构造方法
	* ======
	* @param $action_name 		动作名称
	* ======
	* @author 洪波
	* @version 16.03.09
	*/
	public function __construct($action_name)
	{
		//初始化控制器
		$this->init();
		//执行动作
		$action = 'action' . ucfirst($action_name);
		if(method_exists($this, $action))
		{
			$this->$action();
		}
		else
		{
			Autumn::app()->exception('当前Controller不存在此Action，请检查URL是否正确');
		}
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
	* @version 16.07.06
	*/
	public function isPostRequest()
	{
		if(isset($_SERVER['REQUEST_METHOD']))
		{
			return strtoupper($_SERVER['REQUEST_METHOD']) == 'POST' ? true : false;
		}
		return false;
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
	* 响应json结果
	* ======
	* @param $result 	结果内容
	* @param $code 		响应码
	* @param $error 	报错信息
	* ======
	* @author 洪波
	* @version 16.07.06
	*/
	public function resultJSON($result, $code = self::CODE_SUCCESS, $error = '')
	{
		header('content-type:application/json');
		echo json_encode(array(
			'code' => $code,
			'result' => $result,
			'error' => $error,
			'date' => date('Y-m-d H:i:s', time())
			));
		exit;
	}

	/**
	* 页面重定向
	* ======
	* @param $path 	定向路径
	* ======
	* @author 洪波
	* @version 16.07.06
	*/
	public function redirect($path)
	{
		header('Location:' . $path);
	}
}