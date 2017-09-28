<?php
/**
* 电子邮件服务类
* 基于smtp实现
* ======
* @author 洪波
* @version 16.05.08
*/
namespace core\tools;
use core\Autumn;

class SmtpEmail {
	private $host;
	private $port;
	private $user;
	private $passwd;
	private $socket;
	private $types = 'text/html';
	private $debug;

	public function __construct($config) {
		//设置邮箱服务信息
		$this->host = $config['host'];
		$this->port = $config['port'];
		$this->user = base64_encode($config['user']);
		$this->passwd = base64_encode($config['passwd']);
		$this->debug = $config['debug'];

		//建立套接字连接
		$this->socket = fsockopen($this->host, $this->port, $errorno, $errstr, 30);
		if(! $this->socket) {
			Autumn::app()->exception->throws('连接邮件服务器出错：',$errstr);
		} else {
			$response = fgets($this->socket);
			if(strstr($response, '220') === false) {
				Autumn::app()->exception->throws('邮件服务器响应异常');
			}
			//调试信息
			$this->debugMessage($response);
		}
	}

	/**
	* 检查电邮邮件地址合法性
	* ======
	* @param $email 邮箱地址
	* ======
	* @author 洪波
	* @version 16.05.08
	*/
	public static function checkEmailAddr($email) {
		$pattern = "/^[^_][\w]*@[\w.]+[\w]*[^_]$/";
		if(preg_match($pattern, $email, $mathces)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* 发送邮件协议命令
	* ======
	* @param $command 	命令
	* @param $code 		返回码
	* ======
	* @author 洪波
	* @version 16.05.08
	*/
	private function sendCommand($command, $code) {

		fwrite($this->socket, $command);
		$response = fgets($this->socket);
		//调试信息
		$this->debugMessage($response);

	}

	/**
	* 打印调试信息
	* ======
	* @param $message 	调试信息
	* ======
	* @author 洪波
	* @version 16.05.08
	*/
	private function debugMessage($message) {
		if($this->debug) {
			echo $message, '<br>';
		}
	}

	/**
	* 发送电子邮件
	* ======
	* @param $from 		发送者
	* @param $to 		接收者
	* @param $subject 	主题
	* @param @body 		内容
	* ======
	* @author 洪波
	* @version 16.05.08
	*/
	public function sendEmail($from, $to, $subject, $body) {
		if(self::checkEmailAddr($from) && self::checkEmailAddr($to)) {
			//组合请求报文
			$content = "From:" . $from . "\r\n";
			$content .= "To:" . $to . "\r\n";
			$content .= "Subject:" . $subject . "\r\n";
			$content .= "Content-Type:" . $this->types . "\r\n";
			$content .= "charset=utf-8\r\n\r\n";
			$content .= $body;
			//发生smtp通讯协议命令
			//握手
			$this->sendCommand("HELO " . $this->host . "\r\n", 250);
			//身份验证
			$this->sendCommand("AUTH LOGIN\r\n", 334);
			$this->sendCommand($this->user . "\r\n", 334);
			$this->sendCommand($this->passwd . "\r\n", 235);
			//设置收发地址
			$this->sendCommand("MAIL FROM:" . $from . "\r\n", 250);
			$this->sendCommand("RCPT TO:" . $to . "\r\n", 250);
			//发送邮件内容
			$this->sendCommand("DATA\r\n", 354);
			$this->sendCommand($content . "\r\n.\r\n", 250);
			$this->sendCommand("QUIT\r\n", 221);

			return true;
		} else {
			return false;
		}
	}
}