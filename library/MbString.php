<?php
/**
* 扩展字符串
* ======
* @author 洪波
* @version 17.02.15
*/
namespace library;

class MbString
{
    const TYPE_UTF8     = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    const TYPE_GB2312   = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    const TYPE_GBK      = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    const TYPE_BIG5     = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";

    /**
    * 截取中文字符串
    * ======
    * @param $string    原中文字符
    * @param $start     开始截取位置
    * @param $length    截取数量
    * @param $chatset   字符编码
    * ======
    * @author 洪波
    * @version 17.02.15
    */
    public static function substr($string, $start, $length = 0, $charset = self::TYPE_UTF8)
    {
        preg_match_all($charset, $string, $matches);
        if ($length == 0)
        {
            $length = count($matches[0]) - $start;
        }
        return implode("", array_slice($matches[0], $start, $length));
    }
}