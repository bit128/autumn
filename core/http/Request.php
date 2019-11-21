<?php
/**
* 请求处理类
* ======
* @author 洪波
* @version 16.07.13
*/
namespace core\http;
use core\Autumn;

class Request {
	
	//cookie生存时间
	private $cookie_limit = 7200;
	//类静态实例
	private static $_instance;

	/**
	* 静态单例获取缓存实例
	* ======
	* @author 洪波
	* @version 16.03.01
	*/
	public static function inst() {
		if(! (self::$_instance instanceof self)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	* 判断是否是post请求
	* ======
	* @author 洪波
	* @version 17.04.18
	*/
	public function isPost() {
		if(isset($_SERVER['REQUEST_METHOD'])) {
			return strtoupper($_SERVER['REQUEST_METHOD']) == 'POST' ? true : false;
		}
		return false;
	}

	/**
	* [兼容Yii]判断是否是post请求
	* ======
	* @author 洪波
	* @version 16.07.06
	*/
	public function isPostRequest() {
		return $this->isPost();
	}

	/**
	* 获取get请求参数
	* ======
	* @param $key 		参数名称
	* @param $default 	默认值
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function getQuery($key, $default = '') {
		$value = $default;
		if (isset(Autumn::app()->route->query_params[$key])) {
			$value = Autumn::app()->route->query_params[$key];
		} else if (isset($_GET) && isset($_GET[$key])) {
			$value = htmlspecialchars($_GET[$key]);
		}
		return urldecode($value);
	}

	/**
	* 获取post请求参数
	* ======
	* @param $key 		参数名称
	* @param $default 	默认值
	* ======
	* @author 洪波
	* @version 16.05.11
	*/
	public function getPost($key, $default = '') {
		if(isset($_POST)) {
			if (! is_array($key)) {
				if (isset($_POST[$key]) && $_POST[$key] != '') {
					return $_POST[$key];
				} else {
					return $default;
				}
			} else {
				$params = [];
				foreach ($key as $k) {
					if (isset($_POST[$k])) {
						$params[$k] = $_POST[$k];
					}
				}
				return $params;
			}
		}
	}

	/**
	* 获取请求参数
	* ======
	* @param $key 		参数名称
	* @param $default 	默认值
	* ======
	* @author 洪波
	* @version 16.02.25
	*/
	public function getParam($key, $default = '') {
		$value = $this->getQuery($key);
		if($value == '') {
			$value = $this->getPost($key);
		}
		return $value != '' ? $value : $default;
	}

	/**
	* 设置请求参数
	* ======
	* @param $key 		参数名称
	* @param $value 	值
	* ======
	* @author 洪波
	* @version 16.12.16
	*/
	public function setParam($key, $value) {
		Autumn::app()->route->query_params[$key] = $value;
	}

	/**
	* 获取客户端名称
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function getAgent() {
		return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	}

	/**
	* 获取客户端IP
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function getIp() {
		return isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER["HTTP_X_REAL_IP"] : $_SERVER['REMOTE_ADDR'];
	}

	/**
	* 获取请求时间
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function getTime() {
		return $_SERVER['REQUEST_TIME'];
	}

	/**
	* 设置session
	* ======
	* @param $key 		键
	* @param $value 	值
	* ======
	* @author 洪波
	* @version 16.07.15
	*/
	public function setSession($key, $value) {
		$_SESSION[$key] = $value;
	}

	/**
	* 获取session
	* ======
	* @param $key 		键
	* @param $default 	默认值
	* ======
	* @author 洪波
	* @version 16.07.15
	*/
	public function getSession($key, $default = '') {
		if(isset($_SESSION[$key])) {
			return $_SESSION[$key];
		} else {
			return $default;
		}
	}

	/**
	* 销毁全部会话
	* ======
	* @author 洪波
	* @version 16.07.15
	*/
	public function destorySession() {
		session_destroy();
	}

	/**
	* 设置cookie
	* ======
	* @param $key 		键
	* @param $value 	值
	* @param $limit 	有效期
	* ======
	* @author 洪波
	* @version 16.07.15
	*/
	public function setCookie($key, $value, $limit = 0) {
		if($limit <= 0) {
			$limit = $this->cookie_limit;
		}
		$value = base64_encode($value);
		setcookie($key, $value, time() + $limit, '/');
		$_COOKIE[$key] = $value;
	}

	/**
	* 获取cookie
	* ======
	* @param $key 		键
	* @param $default 	默认值
	* ======
	* @author 洪波
	* @version 16.07.15
	*/
	public function getCookie($key, $default = '') {
		if(isset($_COOKIE[$key])) {
			return base64_decode($_COOKIE[$key]);
		} else {
			return $default;
		}
	}

	/**
	* 删除cookie
	* ======
	* @param $key 	键
	* ======
	* @author 洪波
	* @version 16.07.15
	*/
	public function deleteCookie($key) {
		setcookie($key, '', 0, '/');
		$_COOKIE[$key] = '';
	}

	/**
	* 生成表单令牌
	* ======
	* @author 洪波
	* @version 17.02.21
	*/
	public function createToken() {
		$str = md5($this->getIp() . $this->getTime() . rand(1000, 9999));
		$this->setCookie('csrf_token', $str, 3600);
		echo '<input type="hidden" value="', $str, '" name="csrf_token" />';
	}

	/**
	* 验证表单令牌
	* ======
	* @author 洪波
	* @version 17.02.21
	*/
	public function checkToken() {
		$flag = false;
		$form_token = $this->getPost('csrf_token');
		$csrf_token = $this->getCookie('csrf_token');
		if ($csrf_token != '' && $csrf_token == $form_token) {
			$flag = true;
		}
		$this->deleteCookie('csrf_token');
		return $flag;
	}

}