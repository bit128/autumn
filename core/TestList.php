<?php
/**
* 测试列表
* ======
* @author 洪波
* @version 19.04.12
*/
namespace core;
use \core\http\Curl;

class TestList extends Config {

    private $base_url;

    public function run($index = -1) {
        echo "\n>-------------------------------<\n\n";
        $this->base_url = $this->get('base_url');
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
        $curl = new Curl;
        if (isset($item['method']) && strtolower($item['method']) == 'post') {
            $data = [];
            $header = [];
            if (isset($item['data'])) {
                $data = $item['data'];
            }
            if (isset($item['header'])) {
                $header = $item['header'];
            }
            $result = $curl->post($this->base_url . $item['path'], $data, $header);
        } else {
            $result = $curl->get($this->base_url . $item['path']);
        }
        echo "RESULT:\n", $result, "\n";
        echo "\n>-------------------------------<\n\n";
    }

}