<?php
/**
* 响应处理类
* ======
* @author 洪波
* @version 16.07.13
*/
namespace core\http;

class Response
{
	const RES_UNKNOW	= 0;	//响应码 - 未知
	const RES_OK		= 1;	//响应码 - 成功
	const RES_FAIL		= 2;	//响应码 - 失败
	const RES_PARAMF	= 3;	//响应码 - 参数错误
	const RES_TOKENF	= 4;	//响应码 - 令牌错误
	const RES_REFUSE	= 5;	//响应码 - 拒绝操作
	const RES_NOTHAS	= 6;	//响应码 - 不存在
	const RES_NOCHAN	= 7;	//响应码 - 无变更

	//结果信息
	protected $result = [];

	public $code_discription = array(
		self::RES_UNKNOW 	=> '未知状态',
		self::RES_OK 		=> '操作成功',
		self::RES_FAIL 		=> '操作失败',
		self::RES_PARAMF	=> '操作失败：参数类型错误，或者缺失',
		self::RES_TOKENF	=> '操作失败：身份验证失败，或者权限不足',
		self::RES_REFUSE	=> '操作失败：因安全策略，系统拒绝操作',
		self::RES_NOTHAS	=> '操作失败：要操作的数据或者目标不存在',
		self::RES_NOCHAN	=> '操作结果无变化'
		);

	/**
	* 构造方法，刷新响应结果集
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function __construct()
	{
		$this->flush();
	}

	/**
	* 刷新响应结果
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function flush()
	{
		$this->result = [];
		$this->result['code'] = self::RES_UNKNOW;
		$this->result['result'] = null;
		$this->result['error'] = null;
	}

	/**
	* 设置默认结果集
	* ======
	* @param $code 		响应码
	* @param $result 	结果集
	* @param $error 	保存集
	* ======
	* @author 洪波
	* @version 16.07.13
	*/
	public function setResult($code = 0, $result = '', $error = '')
	{
		if(in_array($code, array_keys($this->code_discription), true))
		{
			$this->result['code'] = $code;
			if($this->result['code'] == self::RES_OK)
			{
				if($result != '')
				{
					$this->result['result'] = $result;
				}
				else
				{
					$this->result['result'] = $this->code_discription[self::RES_OK];
				}
			}
			else
			{
				if($error != '')
				{
					$this->result['error'] = $error;
				}
				else
				{
					$this->result['error'] = $this->code_discription[$code];
				}
			}
		}
		else
		{
			$this->result['code'] = self::RES_OK;
			$this->result['result'] = $code;
		}
		return $this;
	}

	/**
	* 设置自定义结果集
	* ======
	* @param $code 		响应码
	* @param $result 	结果集
	* @param $error 	保存集
	* ======
	* @author 洪波
	* @version 17.08.30
	*/
	public function set($code = 0, $result = '', $error = '')
	{
		$this->result['code'] = $code;
		$this->result['result'] = $result;
		$this->result['error'] = $error;
		return $this;
	}

	/**
	* 设置额外结果集
	* ======
	* @param $key 	键
	* @param $value 值
	* ======
	* @author 洪波
	* @version 17.04.14
	*/
	public function setExtra($key, $value)
	{
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
	public function json($output = true)
	{
		$this->result['date'] = date('Y-m-d H:i:s');
		if($output)
		{
			header("Content-Type:application/json; charset=utf-8");
			echo json_encode($this->result);
		}
		else
		{
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
	public function xml($output = true)
	{
		$rs = simplexml_load_string('<?xml version="1.0" encoding="utf-8" ?><ResponseRoot />');
		$this->addNode($rs, $this->result);
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
				$this->addNode($xml->addChild($k), $v);
			}
			else
			{
				$xml->addChild($k, $v);
			}
		}
	}
}