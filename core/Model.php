<?php
/**
* 模型基础类
* ======
* @author 洪波
* @version 16.03.02
*/
namespace core;

abstract class Model
{

	//[重写]模型表名称
	public $table_name;
	//模型字段值
	protected $ar = [];
	//验证字段错误信息
	private $errors = [];

	/**
	* 获取字段
	* ======
	* @param $key 	键
	* ======
	* @author 洪波
	* @version 17.02.21
	*/
	public function __get($key)
	{
		if (isset($this->ar[$key]))
		{
			return $this->ar[$key];
		}
	}

	/**
	* 设置字段
	* ======
	* @param $key 	键
	* @param $key 	值
	* ======
	* @author 洪波
	* @version 17.02.21
	*/
	public function __set($key, $value)
	{
		$this->ar[$key] = htmlspecialchars(addslashes($value));
	}

	/**
	* [重写]字段验证规则
	* ======
	* @author 洪波
	* @version 17.02.21
	*/
	public function rules()
    {
        return [];
    }

	/**
	* 验证模型字段
	* ======
	* @author 洪波
	* @version 17.02.21
	*/
	public function validate()
	{
		$flag = true;
		$this->errors = [];
		
		if ($this->rules())
		{
			foreach ($this->rules() as $field => $rule)
			{
				if ($rule[0] && !isset($this->ar[$field]))
				{
					$this->errors[] = '字段：' . $field . ' 不能为空';
					$flag = false;
					continue;
				}
				if (isset($this->ar[$field]))
				{
					$value = $this->ar[$field];
					$len = strlen((string) $value);
					//正则类型
					if (isset($rule[1]))
					{
						switch ($rule[1]) {
							case 'email':
								if (! preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $value))
								{
									$this->errors[] = '字段：' . $field . ' 必须是有效的Email地址';
									$flag = false;
								}
								break;
							case 'number':
								if (! preg_match("/\d/", $value))
								{
									$this->errors[] = '字段：' . $field . ' 必须是数字';
									$flag = false;
								}
								break;
							case 'word':
								if (! preg_match("/\w/", $value))
								{
									$this->errors[] = '字段：' . $field . ' 不支持中文或特殊字符';
									$flag = false;
								}
							case 'text':
								preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/",
									$value, $match);
								$len = count($match[0]);
								break;
						}	
					}
					//长度测量-有最小值和最大值
					if (isset($rule[3]))
					{
						if ($len < $rule[2])
						{
							$this->errors[] = '字段：' . $field . ' 长度不能小于' . $rule[2];
							$flag = false;
						}
						if ($len > $rule[3])
						{
							$this->errors[] = '字段：' . $field . ' 长度不能大于' . $rule[3];
							$flag = false;
						}
					}
					//长度测量-仅有最大值
					else if (isset($rule[2]))
					{
						if ($len > $rule[2])
						{
							$this->errors[] = '字段：' . $field . ' 长度不能大于' . $rule[2];
							$flag = false;
						}
					}
				}
			}
		}
		return $flag;
	}

	/**
	* 获取验证失败信息
	* ======
	* @author 洪波
	* @version 17.02.21
	*/
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	* 以数组形式返回模型字段
	* ======
	* @author 洪波
	* @version 17.02.21
	*/
	public function toArray()
	{
		return $this->ar;
	}

}