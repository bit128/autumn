<?php
/**
* Http请求类
* ======
* @author 洪波
* @version 19.11.22
*/
namespace core\http;

class Http {

    const TIMEOUT = 30;

    //请求消息头
    private $headers = [];
    //响应结果信息
    private $response = [];
    
    public function __construct(array $headers = []) {
        $this->headers = [
			'User-Agent: Mozilla/5.0 Autumn'
		];
        if ($headers) {
            $this->setHeader($headers);
        }
    }

    /**
     * 设置请求头
     * ======
     * @param $headers 请求头数组 | 注意没有键只有值哦
     * ======
     * @author 洪波
     * @version 19.11.22
     */
    public function setHeader(array $headers) {
        $this->headers = array_merge($this->headers, $headers);
    }
    
    /**
     * GET提交请求
     * ======
     * @param $url      请求地址
     * @param $callback 响应结果的回调函数
     * ======
     * @author 洪波
     * @version 19.11.22
     */
    public function get($url, $callback = null) {
        $ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($ch, CURLOPT_TIMEOUT, self::TIMEOUT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
		$this->response = curl_getinfo($ch);
        curl_close($ch);
        if ($callback != null) {
            $callback($result);
        } else {
            return $result;
        }
    }

    /**
     * POST提交请求
     * ======
     * @param $url      请求地址
     * @param $params   请求参数
     * @param $callback 响应结果的回调函数
     * ======
     * @author 洪波
     * @version 19.11.22
     */
    public function post($url, array $params, $callback = null) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($params) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($ch, CURLOPT_TIMEOUT, self::TIMEOUT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
		$this->response = curl_getinfo($ch);
        curl_close($ch);
        if ($callback != null) {
            $callback($result);
        } else {
            return $result;
        }
    }

    /**
     * 获取响应结果信息
     * ======
     * @author 洪波
     * @version 19.11.22
     */
    public function getInfo() {
        return $this->response;
    }
}