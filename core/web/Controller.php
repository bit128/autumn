<?php
/**
* 控制器
* ======
* @author 洪波
* @version 16.02.25
*/
namespace core\web;
use core\Autumn;

class Controller {

	//业务模型实例栈
	protected $service_instance = [];

	/**
	* 构造方法
	* ======
	* @param $action_name 		动作名称
	* ======
	* @author 洪波
	* @version 16.03.09
	*/
	public function __construct() {
		//初始化控制器
		$this->init();
	}

	/**
	* 注入业务模型
	* ======
	* @param $model_name 	模型名称
	* @param $single		以单例方式注入
	* ======
	* @author 洪波
	* @version 17.09.28
	*/
	protected function model($model_name, $single = true) {
		if($single && isset($this->service_instance[$model_name])) {
			return $this->service_instance[$model_name];
		} else {
			$service = Autumn::app()->config->get('service_path') . ucfirst($model_name);
			$service_class = str_replace('/', '\\', $service);
			if (is_file('./' . $service . '.php')) {
				$this->service_instance[$model_name] = new $service_class;
				return $this->service_instance[$model_name];
			} else {
				Autumn::app()->exception->throws('业务模型：' . $service_class . ' 加载失败，请确认类路径是否正确');
			}
		}
	}

	/**
	* [新版不建议使用]自动载入业务模型
	* ======
	* @author 洪波
	* @version 17.04.13
	*/
	public function __get($model_name) {
		if(isset($this->service_instance[$model_name])) {
			return $this->service_instance[$model_name];
		} else {
			$service = Autumn::app()->config->get('service_path') . ucfirst($model_name);
			$service_class = str_replace('/', '\\', $service);
			if (is_file('./' . $service . '.php')) {
				$this->service_instance[$model_name] = new $service_class;
				return $this->service_instance[$model_name];
			} else {
				Autumn::app()->exception->throws('业务模型：' . $service_class . ' 加载失败，请确认类路径是否正确');
			}
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
	* 默认404页面action
	* ======
	* @author 洪波
	* @version 17.04.20
	*/
	public function actionNotFound() {
		Autumn::app()->exception->throws('404 NotFound. 您访问的页面不见了，呜呜～～');
	}

	/**
	* 页面重定向
	* ======
	* @param $path 	定向路径
	* ======
	* @author 洪波
	* @version 16.07.06
	*/
	public function redirect($path) {
		header('Location:' . $path);
		exit;
	}
}