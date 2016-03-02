<?php
/**
* phpSpring主配置文件
* ======
* @author 洪波
* @version 15.02.08
*/
$config = array(
	'app_name' => 'Autumn',
	'version' => '1.0',
	//路由器设置
	'router' => array(
		'index' => 'index.php',
		'controller' => 'site',
		'action' => 'index'
		),
	//应用路径
	'path' => array(
		'controller' => '/app/controllers/',
		'view' => '/app/views/',
		'model' => '/app/models/'
		),
	//自动加载核心类库
	'import' => array(
		//核心控制器 - 必须
		'Controller',
		//核心模型类 - 可选
		'Model',
		//缓存基础类 - 可选
		'Cache',
		//核心日志类 - 可选
		'Log',
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