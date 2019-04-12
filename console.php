<?php
require_once('core/Autumn.php');
use \core\Autumn;
Autumn::app()->config->set('config/main.php');
Autumn::app()->test->set('config/test_list.php');
$index = -1;
foreach ($argv as $arg) {
    if (substr($arg, 0, 2) == '-i') {
        $index = substr($arg, 2);
    }
}
Autumn::app()->test->run($index);