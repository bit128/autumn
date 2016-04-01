<?php
/**
* autumn主配置文件
* ======
* @author 洪波
* @version 15.02.08
*/
$config = array(
	'app_name' => 'Autumn',
	'version' => '1.02',
	//路由器设置
	'router' => array(
		'index' => '/index.php',
		'controller' => 'site',
		'action' => 'index'
		),
	//视图设置
	'view' => array(
		'path' => '/app/views/',
		'cache_dir' => '/app/caches/',
		'cache_limit' => 86400
		),
	//自动加载类目录
	'import' => array(
		'core/',
		'library/',
		'app/controllers/',
		'app/models/',
		),
	//数据库配置
	'database' => array(
		'driver' => 'Mysql',
		'host' => '127.0.0.1',
		'user' => '',
		'password' => '',
		'dbname' => 'test'
		),
	//系统日志
	'log' => array(
		'enable' => false,
		'level' => 1,
		'dir' => '/app/caches/',
		'cache' => false,
		'log_prefix' => 'log_',
		'log_file' => 'Ymd',
		'log_postfix' => '.log',
		),
	);