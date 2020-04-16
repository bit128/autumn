<?php
/**
* autumn主配置文件
* ======
* @author 洪波
* @version 19.11.21
*/
return [
	'app_name' => 'Autumn',
	'version' => '1',
	//开启debug模式，可显示错误警告
	'debug' => true,
	//开启session
	'session_start' => true,
	//业务模型载入路径
	'service_path' => 'app/models/',
	//响应码配置路径
	'response_code' => 'config/response.php',
	//IP地址防火墙
	'ip_filter' => [
		//是否开启IP过滤（默认关闭）
		'enabled' => false,
		//IP配置表
		'ip_list' => 'config/ip.php',
	],
	//模块配置
	'module' => [
		//API测试用例
		//*
		'test' => [
			'class' => 'core\tools\TestApi',
			'config' => 'config/api.php'
		],//*/
		//系统日志
		'log' => [
			'class' => 'core\Log',
			'path' => 'app/runtimes/',
			'prefix' => date('Ymd')
		],
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
			//默认布局（模板）
			'layout' => 'layout',
			//视图文件夹
			'path' => 'app/views/',
			//视图缓存文件夹
			'cache_dir' => 'app/runtimes/',
			//视图缓存有效期
			'cache_limit' => 86400
		],
		//数据库配置
		/*
		'db' => [
			'class' => 'core\db\Sqlite',
			'source' => 'app/runtimes/test.db'
		],//*/
		//*
		'db' => [
			'class' => 'core\db\Mysqli',
			'host' => '127.0.0.1',
			'user' => '',
			'password' => '',
			'dbname' => '',
			'port' => 3306
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