<?php
/**
* autumn主配置文件
* ======
* @author 洪波
* @version 15.02.08
*/
$main_config = array(
	'app_name' => 'Autumn',
	'version' => '1.5',
	//开启session
	'session_start' => true,
	//路由器设置
	'router' => array(
		//默认入口脚本文件
		'index' => '/index.php',
		//默认控制器
		'controller' => 'site',
		//默认控制器执行动作
		'action' => 'index',
		//自定义路由规则
		/*
		'route_alias' => array(
			'hello' => 'site/test'
			)//*/
		),
	//视图设置
	'view' => array(
		'default_layout' => 'layout',
		'path' => 'app/views/',
		'cache_dir' => 'app/runtime/',
		'cache_limit' => 86400
		),
	//数据库配置
	'database' => array(
		'driver' => 'mysqli',
		'host' => '127.0.0.1',
		'user' => 'root',
		'password' => '',
		'dbname' => 'test'
		),
	//缓存服务
	/*
	'cache' => array(
		'driver' => 'redis',
		'host' => '127.0.0.1',
		'port' => 6379,
		'cache_db' => 0,
		'cache_limit' => 60
		)//*/
	);