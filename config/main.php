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
	//自动加载核心类库
	'core_class' => array(
		//核心控制器 - 必须
		'Controller',
		//缓存基础类 - 可选
		'Cache',
		),
	//数据库配置
	/*
	'mysql' => array(
		'host' => '127.0.0.1',
		'user' => 'root',
		'password' => 'hong_1987',
		'dbname' => 'bookstore'
		),*/
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
	//框架默认配置项
	'default_index' => 'index.php',
	'default_controller' => 'site',
	'default_action' => 'index',
	'controller_path' => '/app/controllers/',
	'view_path' => '/app/views/'
	);