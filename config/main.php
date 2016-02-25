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
	//数据库配置
	'mysql' => array(
		'host' => '127.0.0.1',
		'user' => 'root',
		'password' => 'hong_1987',
		'dbname' => 'v2'
		),
	//框架默认配置项
	'default_index' => 'index.php',
	'default_controller' => 'site',
	'default_action' => 'index',
	);