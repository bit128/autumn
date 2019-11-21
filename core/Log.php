<?php
/**
* 日志
* ======
* @author 洪波
* @version 17.04.01
*/
namespace core;

class Log {
    
    const TYPE_NORMAL   = 'normal';     //类型 - 通用
    const TYPE_INFO     = 'info';       //类型 - 信息
    const TYPE_WARNING  = 'warning';    //类型 - 警告
    const TYPE_ERROR    = 'error';      //类型 - 错误

    //日志存储路径
    private $save_path;
    //日志文件前缀
    private $prefix = 'log';

    public function __construct($config) {
        $this->save_path = $config['path'];
        $this->prefix = $config['prefix'];
    }

    /**
    * 写入日志
    * ======
    * @param $content   日志内容
    * @param $type      类型
    * @param $flag      标记
    * ======
    * @author 洪波
    * @version 17.05.15
    */
    public function write($content, $type = self::TYPE_NORMAL, $flag = '0') {
        $data = '['.date('H:i:s')
            . '] {' . Autumn::app()->request->getIp()
            . '} (' . $flag . ') ' . $content;
        $file_name = $this->save_path . $this->prefix . '_' . $type . '.log';
        return file_put_contents($file_name, $data."\r\n", FILE_APPEND);
    }

    /**
    * 获取日志文件列表
    * ======
    * @param $type  日志类型
    * ======
    * @author 洪波
    * @version 17.03.14
    */
    public function getFileList($type = '') {
        if ($type != '') {
            $match = '/^\d+\_' . $type . '\.log$/';
        } else {
            $match = '/^\d+\_[a-z]+\.log$/';
        }
        $file_list = [];
        foreach (scandir($this->save_path) as $file) {
            if (preg_match($match, $file)) {
                $file_list[] = $file;
            }
        }
        rsort($file_list);
        return $file_list;
    }

    /**
    * 获取日志内容
    * ======
    * @param $file_name 文件名
    * ======
    * @author 洪波
    * @version 17.03.14
    */
    public function getContent($file_name) {
        return file_get_contents(Logs::$base_path . $file_name);
    }
}