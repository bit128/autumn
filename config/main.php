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
	//业务模型载入路径
	'service_path' => 'app/models/',
	//模块配置
	'module' => [
		//*
		//系统日志
		'log' => [
			'class' => 'core\Log',
			'path' => 'app/runtimes/',
			'prefix' => date('Ymd')
		],//*/
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
			'cache_dir' => 'app/runtimes/',
			'cache_limit' => 86400
		],
		//数据库配置
		/*
		'db' => [
			'class' => 'core\db\Sqlite',
			'source' => 'app/runtimes/test.db'
		],//*/
		/*
		'db' => [
			'class' => 'core\db\Mysqli',
			'host' => '127.0.0.1',
			'user' => 'root',
			'password' => '',
			'dbname' => 'test'
		],//*/
		/*
		'db' => [
			'class' => 'core\db\Pdo',
			'link' => 'mysql:host=127.0.0.1;dbname=test',
			'user' => 'root',
			'password' => ''
		],//*/
		/*
		//缓存配置
		'cache' => [
			'class' => 'core\cache\RedisExpress',
			'host' => '127.0.0.1',
			'port' => 6379,
			'dbname' => 0,
			'exp' => 60
		],//*/
	]
];