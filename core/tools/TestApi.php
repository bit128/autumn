<?php
/**
* Api列表测试类
* ======
* @author 洪波
* @version 19.05.21
*/
namespace core\tools;

class TestApi extends \core\Config {

    private $domain;

    public function __construct($config) {
        if (isset($config['config'])) {
            $this->set($config['config']);
        }
    }

    public function run($index = -1) {
        echo "\n>-------------------------------<\n\n";
        $this->domain = $this->get('domain');
        if ($index == -1) {
            foreach ($this->get('list') as $item) {
                $this->printItem($item);
            }
        } else if (isset($this->get('list')[$index])) {
            $this->printItem($this->get('list')[$index]);
        }
    }

    private function printItem($item) {
        echo 'TEST: ';
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