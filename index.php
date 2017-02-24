<?php
/**
* Autumn入口文件
* ======
* @author 洪波
* @version 17.02.20
*/
defined('AUTUMN_DEBUG') or define('AUTUMN_DEBUG', true);
require_once('core/Autumn.php');
core\Autumn::app()->config->set('config/main.php');
core\Autumn::app()->run();