<?php
/**
* 缓存基础类
* ======
* @author 洪波
* @version 16.03.01
*/
abstract class Cache
{
	abstract public function set($key, $value, $limit);

	abstract public function get($key, $default);

	abstract public function delete($key);
}