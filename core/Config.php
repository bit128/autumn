<?php
/**
* 配置项
* ======
* @author 洪波
* @version 17.02.20
*/
namespace core;

class Config {

    //默认配置文件
    const DEFAULT_CONFIG = './config/main.php';

    //配置表是否可用
    public $isEnabled = false;
    //配置列表组
    protected $config_list = [];

    public function __construct() {
        $this->set(self::DEFAULT_CONFIG);
    }

    /**
    * 设置全局配置项
    * ======
    * @param $config_uri    配置项uri
    * @param $config_name   配置项名称
    * ======
    * @author 洪波
    * @version 19.11.22
    */
    public function set($config_uri) {
        if (\is_file($config_uri)) {
            $this->config_list = require_once($config_uri);
            $this->isEnabled = true;
        }
    }

    /**
    * 获取配置项
    * ======
    * @param $key           配置键
    * @param $default       默认值
    * @param $config_name   配置项名称
    * ======
    * @author 洪波
    * @version 17.02.20
    */
    public function get($key, $default = '') {
        $dir = explode('.', $key);
        $temp = '';

        foreach ($dir as $d) {
            if ($d == '') {
                break;
            }
            if (is_array($temp)) {
                if (isset($temp[$d])) {
                    $temp = $temp[$d];
                } else {
                    $temp = $default;
                    break;
                }
            } else if (isset($this->config_list[$d])) {
                $temp = $this->config_list[$d];
            } else {
                $temp = $default;
                break;
            }
        }
        return $temp;
    }

    /**
    * 扩展配置项
    * ======
    * @param $key           配置键
    * @param $value         配置值
    * @param $config_name   配置项名称
    * ======
    * @author 洪波
    * @version 17.02.20
    */
    public function add($key, $value) {
        $this->config_list[$key] = $value;
    }
}