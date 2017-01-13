<?php
/**
* Autumn入口文件
* ======
* @author 洪波
* @version 16.07.06
*/
require_once('config/main.php');
require_once('core/Autumn.php');
core\Autumn::app($config)->run();