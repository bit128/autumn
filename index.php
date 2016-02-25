<?php
define('ROOT', dirname(__FILE__));
require_once(ROOT . '/config/main.php');
require_once(ROOT . '/core/Autumn.php');
Autumn::app($config)->run();
//echo Autumn::app()->config('version', 'haha');