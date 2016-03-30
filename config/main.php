<?php
/**
* autumn主配置文件
* ======
* @author 洪波
* @version 15.02.08
*/
$config = array(
	'app_name' => 'Autumn',
	'version' => '1.0',
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
		'user' => 'root',
		'password' => 'hong_1987',
		'dbname' => 'bookstore'
		),
	//memcache缓存
	/*
	'memcache' => array(
		'host' => '127.0.0.1',
		'port' => 11211,
		'limit' => 60,
		'compress' => false
		),*/
	//redis缓存
	/*
	'redis' => array(
		'host' => '127.0.0.1',
		'port' => '6379',
		'db' => 0,
		'limit' => 60
		),*/
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