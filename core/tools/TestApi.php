<?php
/**
 * API测试用例类
 * ======
 * @author 洪波
 * @version 19.11.21
 */
namespace core\tools;

class TestApi extends \core\Config {

    private $domain;
    private $count = 0;

    public function __construct($config) {
        if (isset($config['config'])) {
            $this->set($config['config']);
        }
    }

    /**
     * 测试用例入口
     * ======
     * @param $index    测试编号
     * ======
     * @author 洪波
     * @version 19.11.21
     */
    public function run($index = -1) {
        echo "\n*********************************\n\n";
        $this->domain = $this->get('domain');
        if ($index == -1) {
            foreach ($this->get('list') as $item) {
                $this->printItem($item);
            }
        } else if (isset($this->get('list')[$index])) {
            $this->printItem($this->get('list')[$index]);
        }
    }

    /**
     * 执行测试成员
     * ======
     * @param $item 测试对象
     * ======
     * @author 洪波
     * @version 19.11.21
     */
    private function printItem($item) {
        echo ++$this->count, '. TEST: ';
        if (isset($item['name'])) {
            echo '【', $item['name'], '】';
        }
        echo "\n", $item['path'],"\n\n";
        $result = '';
        $curl = new \core\http\Curl;
        if (isset($item['method']) && strtolower($item['method']) == 'post') {
            $data = [];
            $header = [];

            if (isset($item['data'])) {
                $data = array_merge($this->get('extra'), $item['data']);
            } else {
                $data = $this->get('extra');
            }
            if (isset($item['header'])) {
                $header = $item['header'];
            }
            $result = $curl->post($this->domain . $item['path'], $data, $header);
        } else {
            $result = $curl->get($this->domain . $item['path']);
        }
        echo "RESULT:\n", $result, "\n";
        echo "\n>-------------------------------<\n\n";
    }

}