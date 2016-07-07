<?php
/**
* 核心日志类
* ======
* @author 洪波
* @version 16.03.02
*/
namespace core;

class Log
{
	//类静态实例
	private static $_instance;

	//标志 - 系统日志
	const TAG_SYSTEM		= 'SYS';
	//标志 - 用户日志
	const TAG_USER			= 'USR';

	//等级 - 产品级别 记录ip
	const LEVEL_PRUDUCT		= 1;
	//等级 - 开发级别 记录referer
	const LEVEL_DEVLOPER	= 2;
	//等级 - 调试级别 记录user_gender
	const LEVEL_DEBUG		= 3;

	//是否启用日志
	public $enable = false;
	//日志等级
	private $log_level;
	//存储目录
	private $log_dir;
	//日志文件名
	private $log_file;
	//是否启用缓存
	private $log_cache = false;

	private function __construct()
	{
		$config = Autumn::app()->config('log');
		if($config)
		{
			$this->enable = $config['enable'];
			$this->log_level = $config['level'];
			$this->log_dir = $config['dir'];
			$this->log_cache = $config['cache'];
			$this->log_file = $config['log_prefix'] . date($config['log_file']) . $config['log_postfix'];
		}
		else
		{
			$this->log_level = self::LEVEL_DEBUG;
			$this->log_dir = '/';
			$this->log_file = 'log_' . date('His') . '.log';
		}
	}

	private function __clone(){}

	/**
	* 静态单例获取缓存实例
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	public static function inst()
	{
		if(! (self::$_instance instanceof self))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	* 记录系统日志
	* ======
	* @param $level 	过滤级别
	* @param $content 	内容
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	public function systemRecord($level, $content)
	{
		if($this->enable && $level <= $this->log_level)
		{
			$this->record($content, '0000', self::TAG_SYSTEM);
		}
	}

	/**
	* 记录日志
	* ======
	* @param $type 	日志类型
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	public function record($content, $code = '0000', $tag = 'User')
	{
		$time = time();
		$log_data = array(
			'tag' => $tag,
			'code' => $code,
			'content' => $content,
			'date' => date('Y-m-d H:i:s', $time),
			'time' => $time,
			);
		switch ($this->log_level)
		{
			case self::LEVEL_PRUDUCT:
				$log_data['ip'] = $_SERVER['REMOTE_ADDR'];
				break;
			case self::LEVEL_DEVLOPER:
				$log_data['ip'] = $_SERVER['REMOTE_ADDR'];
				$log_data['referer'] = $_SERVER['HTTP_REFERER'];
				break;
			case self::LEVEL_DEBUG:
				$log_data['ip'] = $_SERVER['REMOTE_ADDR'];
				$log_data['referer'] = $_SERVER['HTTP_REFERER'];
				$log_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		}
		$this->writeLog($log_data);
	}

	/**
	* 写入日志
	* ======
	* @param $log_data 	日志数据
	* ======
	* @author 洪波
	* @version 16.03.02
	*/
	private function writeLog($log_data)
	{
		$save_path = ROOT . $this->log_dir . $this->log_file;
		file_put_contents($save_path, json_encode($log_data) . "\n", FILE_APPEND);
	}
}