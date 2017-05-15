<?php
/**
* 日志服务Base类
* ======
* @author 洪波
* @version 17.04.01
*/
namespace core;

class Log
{
    const TYPE_NORMAL   = 'normal';  //类型 - 通用
    const TYPE_USER     = 'user';    //类型 - 用户
    const TYPE_CLIENT   = 'client';  //类型 - 终端
    const TYPE_ADMIN    = 'admin';   //类型 - 管理员
    const TYPE_DANGER   = 'danger';  //类型 - 高危

    //日志存储路径
    private $save_path;
    //日志文件前缀
    private $prefix = 'log';

    public function __construct($config)
    {
        $this->save_path = $config['path'];
        $this->prefix = $config['prefix'];
    }

    /**
    * 写入日志
    * ======
    * @param $content   日志内容
    * @param $type      类型
    * @param $by_id     业务id
    * ======
    * @author 洪波
    * @version 17.05.15
    */
    public function write($content, $type = self::TYPE_NORMAL, $by_id = '0', $flag = '0')
    {
        $data = '['.date('H:i:s').']'
            . ' - ' . $flag
            . ' - ' . Autumn::app()->request->getIp()
            . ' - ' . $by_id
            . ' - ' . $content;
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
    public function getFileList($type = '')
    {
        if ($type != '')
        {
            $match = '/^\d+\_' . $type . '\.log$/';
        }
        else
        {
            $match = '/^\d+\_[a-z]+\.log$/';
        }
        $file_list = [];
        foreach (scandir($this->save_path) as $file)
        {
            if (preg_match($match, $file))
            {
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
    public function getContent($file_name)
    {
        return file_get_contents(Logs::$base_path . $file_name);
    }
}