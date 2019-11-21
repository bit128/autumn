<?php
/**
* 响应处理类
* ======
* @author 洪波
* @version 16.07.13
*/
namespace core\http;
use core\Autumn;

class Response {

	//结果信息
	protected $result = [];
	//响应码
	protected $response_code = [];

	/**
	* 构造方法，刷新响应结果集
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function __construct() {
		$this->flush();
		$this->response_code = require_once(Autumn::app()->config->get('response_code'));
	}

	/**
	* 刷新响应结果
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function flush() {
		$this->result = [];
		$this->result['code'] = 0;
		$this->result['result'] = null;
		$this->result['error'] = null;
	}

	/**
	 * 成功结果集
	 * ======
	 * @param $result	成功结果
	 * @param $extras	扩展数据集
	 * ======
	 * @author 洪波
	 * @version 19.11.21
	 */
	public function success($result = null, array $extras = [], $code = 1) {
		$this->result['code'] = 1;
		if ($result == null) {
			if (isset($this->response_code[$code])) {
				$this->result['result'] = $this->response_code[$code];
			}
		} else {
			$this->result['result'] = $result;
		}
		foreach ($extras as $k => $v) {
			$this->result[$k] = $v;
		}
		return $this;
	}

	/**
	 * 非成功结果集
	 * ======
	 * @param $code 	响应码
	 * @param $error	消极结果
	 * ======
	 * @author 洪波
	 * @version 19.11.21
	 */
	public function fail($code, $error = null) {
		$this->result['code'] = $code;
		if($error == null) {
			if (isset($this->response_code[$code])) {
				$this->result['error'] = $this->response_code[$code];
			}
		} else {
			$this->result['error'] = $error;
		}
		return $this;
	}

	/**
	* [新版不建议使用]设置额外结果集
	* ======
	* @param $key 	键
	* @param $value 值
	* ======
	* @author 洪波
	* @version 17.04.14
	*/
	public function setExtra($key, $value) {
		$this->result[$key] = $value;
		return $this;
	}

	/**
	* 返回结果json格式
	* ======
	* @param $output 	输出模式
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function json($output = true) {
		$this->result['date'] = date('Y-m-d H:i:s');
		if($output) {
			header("Content-Type:application/json; charset=utf-8");
			echo json_encode($this->result);
		} else {
			return $this->result;
		}
	}

	/**
	* 返回结果xml格式
	* ======
	* @param $output 	输出模式
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function xml($output = true) {
		$rs = simplexml_load_string('<?xml version="1.0" encoding="utf-8" ?><ResponseRoot />');
		$this->addNode($rs, $this->result);
		if($output) {
			header("Content-Type:text/xml; charset=utf-8");
			echo $rs->asXML();
		} else {
			return $rs;
		}
	}

	/**
	* 添加xml子节点数据
	* ======
	* @param $xml 	父节点
	* @param $data 	数据
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	private function addNode($xml, $data) {
		foreach ($data as $k => $v) {
			if(is_numeric($k)) {
				$k = 'item';
			}
			if(is_array($v) || is_object($v)) {
				$this->addNode($xml->addChild($k), $v);
			} else {
				$xml->addChild($k, $v);
			}
		}
	}
}