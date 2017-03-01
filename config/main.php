<?php
/**
* autumn主配置文件
* ======
* @author 洪波
* @version 17.02.24
*/
return [
	'app_name' => 'Autumn',
	'version' => '1',
	//开启session
	'session_start' => true,
	'module' => [
		//路由器设置
		'route' => [
			'class' => 'core\web\Route',
			'path' => 'app/controllers/',
			//默认入口脚本文件
			'index' => '/index.php',
			//默认控制器
			'controller' => 'site',
			//默认控制器执行动作
			'action' => 'index',
			//自定义路由规则
			'route_alias' => [
				//'hello' => 'site/test'
			]
		],
		//视图设置
		'view' => [
			'class' => 'core\web\View',
			'layout' => 'layout',
			'path' => 'app/views/',
			'cache_dir' => 'app/runtime/',
			'cache_limit' => 86400
		],
		//数据库配置
		'database' => [
			'class' => 'core\db\Mysqli',
			'host' => '127.0.0.1',
			'user' => 'root',
			'password' => '',
			'dbname' => ''
		],
		/*
		//缓存配置
		'cache' => [
			'class' => 'library\Redis',
			'host' => '127.0.0.1',
			'port' => 6379,
			'cache_db' => 0,
			'cache_limit' => 60
		],//*/
	]
];