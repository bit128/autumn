<?php
/**
* Autumn入口文件
* ======
* @author 洪波
* @version 17.02.20
*/
require_once('core/Autumn.php');
core\Autumn::app()->config->set('config/main.php');
core\Autumn::app()->run();