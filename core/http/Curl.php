<?php
/**
* Curl请求类
* ======
* @author 洪波
* @version 16.02.29
*/
namespace core\http;

class Curl
{
	//请求地址
	private $url = '';
	//请求数据
	private $fields = [];
	//请求消息头
	private $headers = [];
	//请求方法
	private $method;
	//响应结果
	public $response;

	const METHOD_POST = 1;
	const METHOD_GET = 0;

	/**
	* 构造方法
	* ======
	* @param $url 		请求地址
	* @param $fields 	请求数据
	* @param $headers 	请求消息头
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	public function __construct($url = '', $fields = [], $headers = [])
	{	
		$this->url = $url;
		$this->fields = $fields;
		$this->headers = [
			'User-Agent: Mozilla/5.0 Autumn 1.21'
		];
		$this->headers = array_merge($this->headers, $headers);
	}

	/**
	* 设置请求地址
	* ======
	* @param $url 	请求地址
	* ======
	* @author 洪波
	* @version 14.05.14
	*/
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	* 设置请求消息头
	* ======
	* @param $headers 	请求消息头
	* ======
	* @author 洪波
	* @version 14.05.14
	*/
	public function setHeaders($headers)
	{
		$this->headers = $headers;
	}

	/**
	* 设置请求数据
	* ======
	* @param $fields 	请求数据
	* ======
	* @author 洪波
	* @version 14.05.14
	*/
	public function setFields($fields)
	{
		$this->fields = $fields;
	}

	/**
	* 发送get请求
	* ======
	* @param $url 	请求地址
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	public function get($url = '')
	{
		if($url != '')
		{
			$this->url = $url;
		}
		//设置请求方法为GET
		$this->method = self::METHOD_GET;
		return $this->exec();
	}

	/**
	* 发送post请求
	* ======
	* @param $url 		请求地址
	* @param $fields 	请求数据
	* @param $headers 	请求消息头
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	public function post($url = '', $fields = [], $headers = [])
	{
		if($url != '')
		{
			$this->url = $url;
		}
		if($fields)
		{
			$this->fields = $fields;
		}
		if($headers)
		{
			$this->headers = array_merge($this->headers, $headers);
		}
		//设置请求方法为POST
		$this->method = self::METHOD_POST;
		return $this->exec();
	}

	/**
	* 通过crul获取请求
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	private function exec()
	{
		if($this->url == '')
		{
			die('the url is null!');
		}	
		$ch = curl_init($this->url);
		//设置消息头
		if($this->headers)
		{
			//print_r($this->headers);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		}
		//设置请求方法
		if($this->method == self::METHOD_POST)
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->fields);
		}
		//curl_setopt($ch, CURLOPT_TIMEOUT, 2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$result = curl_exec($ch);
		$this->response = curl_getinfo($ch);
		curl_close($ch);

		return $result;
	}
}