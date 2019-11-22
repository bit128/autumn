<?php
/**
 * 遍历测试api列表脚本
 * ======
 * 切换到脚本目录中，命令行执行：php test.php
 * 单独测试示例(单独测试列表中第三个api)：php test.php -i2
 * ======
 * @author 洪波
 * @version 19.11.22
 */
require_once('core/Autumn.php');
//命令行参数
$index = -1;
foreach ($argv as $arg) {
    if (substr($arg, 0, 2) == '-i') {
        $index = substr($arg, 2);
    }
}
//执行测试用例
\core\Autumn::app()->test->run($index);