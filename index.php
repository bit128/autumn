<?php
/**
* Autumn入口文件
* ======
* @author 洪波
* @version 16.03.10
*/
define('ROOT', dirname(__FILE__));
require_once(ROOT . '/config/main.php');
require_once(ROOT . '/core/Autumn.php');
Autumn::app($config)->run();