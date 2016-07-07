<?php
/**
* HTTP请求类
* ======
* @author 洪波
* @version 16.02.29
*/
namespace library;

class HttpRequest
{
	//请求地址
	private $url = '';
	//请求数据
	private $fields = array();
	//请求消息头
	private $headers = array();
	//请求方法
	private $method;

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
	public function __construct($url = '', $fields = array(), $headers = array())
	{	
		$this->url = $url;
		$this->fields = $fields;
		$this->headers = $headers;
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
			$this->url = $url;
		//设置请求方法为GET
		$this->method = self::METHOD_GET;
		return $this->curl();
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
	public function post($url = '', $fields = array(), $headers = array())
	{
		if($url != '')
			$this->url = $url;

		if($fields)
			$this->fields = $fields;

		if($headers)
			$this->headers = $headers;
		//设置请求方法为POST
		$this->method = self::METHOD_POST;

		return $this->curl();
	}

	/**
	* 通过crul获取请求
	* ======
	* @author 洪波
	* @version 16.02.29
	*/
	private function curl()
	{
		if($this->url == '')
		{
			die('the url is null!');
		}	
		$curl = curl_init($this->url);
		//设置消息头
		if($this->headers)
		{
			curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
		}
		//设置请求方法
		if($this->method == self::METHOD_POST)
		{
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $this->fields);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		$result = curl_exec($curl);
		//$response = curl_getinfo($curl);
		curl_close($curl);

		return $result;
	}
}