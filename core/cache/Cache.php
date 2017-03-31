<?php
/**
* 缓存驱动接口
* ======
* @author 洪波
* @version 17.03.31
*/
namespace core\cache;

interface Cache
{
    /**
    * 判断缓存是否存在
    * ======
    * @param $key   键
    * ======
    * @author 洪波
    * @version 17.03.31
    */
    public function exist($key);

    /**
    * 设置缓存
    * ======
    * @param $key   键
    * @param $value 值
    * @param $exp   过期时间
    * ======
    * @author 洪波
    * @version 17.03.31
    */
    public function set($key, $value, $exp);

    /**
    * 获取缓存
    * ======
    * @param $key   键
    * @param $value 值
    * ======
    * @author 洪波
    * @version 17.03.31
    */
    public function get($key);

    /**
    * 删除缓存
    * ======
    * @param $key   键
    * ======
    * @author 洪波
    * @version 17.03.31
    */
    public function delete($key);
}