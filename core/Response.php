<?php
/**
* 响应处理类
* ======
* @author 洪波
* @version 16.07.13
*/
namespace core;

class Response
{
	const RES_UNKNOW	= 0;	//响应码 - 未知
	const RES_SUCCESS	= 1;	//响应码 - 成功
	const RES_FAIL		= 2;	//响应码 - 失败

	protected $code;
	protected $result;
	protected $error;

	/**
	* 构造方法，刷新响应结果集
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function __construct()
	{
		$this->flush();
		$this->init();
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
	* 刷新响应结果
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function flush()
	{
		$this->code = self::RES_UNKNOW;
		$this->result = '';
		$this->error = '';
	}

	/**
	* 设置响应码
	* ======
	* @code 	响应码
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function setCode($code)
	{
		$this->code = $code;
	}

	/**
	* 设置结果集
	* ======
	* @result 	结果集
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function setResult($result)
	{
		$this->result = $result;
	}

	/**
	* 设置报错信息
	* ======
	* @error 	报错信息
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function setError($error)
	{
		$this->error = $error;
	}

	/**
	* 返回结果json格式
	* ======
	* @param $output 	输出模式
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function json($output = false)
	{
		$rs = array(
			'code' => $this->code,
			'result' => $this->result,
			'error' => $this->error,
			'date' => date('Y-m-d H:i:s')
			);
		if($output)
		{
			header("Content-Type:application/json; charset=utf-8");
			echo json_encode($rs);
		}
		else
		{
			return $rs;
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
	public function xml($output = false)
	{
		$rs = simplexml_load_string('<?xml version="1.0" encoding="utf-8" ?><ResponseRoot />');
		$rs->addChild('code', $this->code);
		$rs->addChild('result');
		$this->addNode($rs->result, $this->result);
		$rs->addChild('error', $this->error);
		$rs->addChild('date', date('Y-m-d H:i:s'));
		if($output)
		{
			header("Content-Type:text/xml; charset=utf-8");
			echo $rs->asXML();
		}
		else
		{
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
	private function addNode($xml, $data)
	{
		foreach ($data as $k => $v)
		{
			if(is_numeric($k))
			{
				$k = 'item';
			}
			if(is_array($v) || is_object($v))
			{
				$xml->addChild($k);
				$this->addNode($xml->$k, $v);
			}
			else
			{
				$xml->addChild($k, $v);
			}
		}
	}
}