<?php
/**
* 缓存接口
* ======
* @author 洪波
* @version 16.03.01
*/
interface Cache
{
	public function set($key, $value, $limit);

	public function get($key, $default);

	public function delete($key);
}