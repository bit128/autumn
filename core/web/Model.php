<?php
/**
* 模型基础类
* ======
* @author 洪波
* @version 16.03.02
*/
namespace core\web;

abstract class Model {
	//数据对象实例
	protected $orm = null;
	//[重写]模型表名称
	public $table_name = '';
	//验证字段错误信息
	private $errors = [];

	public function __construct() {
		$this->init();
		$this->orm = new \core\db\Orm($this->table_name);
	}

	/**
	* 子模型初始化方法
	* ======
	* @author 洪波
	* @versin 17.03.02
	*/
	public function init(){}

	/**
	* 获取表对象orm实例
	* ======
	* @author 洪波
	* @version 17.03.02
	*/
	public function getOrm() {
		return $this->orm;
	}

	/**
	* 保存对象数据
	* ======
	* @author 洪波
	* @version 17.03.02
	*/
	public function save() {
		return $this->orm->save();
	}

	/**
	* 获取对象数据
	* =======
	* @author 洪波
	* @version 17.03.02
	*/
	public function get($id) {
		return $this->orm->find($this->orm->pk . "='{$id}'");
	}

	/**
	* 获取对象列表
	* ======
	* @author 洪波
	* @version 17.03.02
	*/
	public function getList($criteria = '') {
		return [
			'count' => $this->orm->count($criteria),
			'result' => $this->orm->findAll($criteria)
		];
	}

	/**
	* 更新对象数据
	* =======
	* @author 洪波
	* @version 17.03.02
	*/
	public function update($id, $data) {
		return $this->orm->updateAll($data, $this->orm->pk . "='{$id}'");
	}

	/**
	* 删除对象数据
	* =======
	* @author 洪波
	* @version 17.03.02
	*/
	public function delete($id) {
		return $this->orm->deleteAll($this->orm->pk . "='{$id}'");
	}

	/**
	* [重写]字段验证规则
	* ======
	* @author 洪波
	* @version 17.02.21
	*/
	public function rules() {
        return [];
		/*
		return [
			//'field' => ['必须存在','类型','最少位数','最多位数']
			'user_email' => [true, 'email', 10, 60],
			'user_name' => [true, 'word', 6, 12],
			'user_age' => [true, 'number', 2],
			'user_note' => [false, 'text', 20]
		];*/
    }

	/**
	* 加载数据到模型中
	* ======
	* @param $data 	数据
	* @param $post 	是否从post表单中映射
	* ======
	* @author 洪波
	* @version 17.03.02
	*/
	public function load($data = [], $post = false) {
		if ($post) {
			foreach ($_POST as $k => $v) {
				if (isset ($this->orm->ar[$k])) {
					$this->orm->ar[$k] = htmlspecialchars(addslashes($v));
				}
			}
		}
		if ($data) {
			foreach ($data as $k => $v) {
				if (isset ($this->orm->ar[$k])) {
					$this->orm->ar[$k] = htmlspecialchars(addslashes($v));
				}
			}
		}
	}

	/**
	* 验证模型字段
	* ======
	* @author 洪波
	* @version 17.02.21
	*/
	public function validate() {
		$flag = true;
		$this->errors = [];
		
		if ($this->rules()) {
			foreach ($this->rules() as $field => $rule) {
				if ($rule[0] && !isset($this->orm->ar[$field])) {
					$this->errors[] = '字段：' . $field . ' 不能为空';
					$flag = false;
					continue;
				}
				if (isset($this->orm->ar[$field])) {
					$value = $this->orm->ar[$field];
					$len = strlen((string) $value);
					//正则类型
					if (isset($rule[1])) {
						switch ($rule[1]) {
							case 'email':
								if (! preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $value)) {
									$this->errors[] = '字段：' . $field . ' 必须是有效的Email地址';
									$flag = false;
								}
								break;
							case 'number':
								if (! preg_match("/\d/", $value)) {
									$this->errors[] = '字段：' . $field . ' 必须是数字';
									$flag = false;
								}
								break;
							case 'word':
								if (! preg_match("/\w/", $value)) {
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
					if (isset($rule[3])) {
						//长度测量-有最小值和最大值
						if ($len < $rule[2]) {
							$this->errors[] = '字段：' . $field . ' 长度不能小于' . $rule[2];
							$flag = false;
						}
						if ($len > $rule[3]) {
							$this->errors[] = '字段：' . $field . ' 长度不能大于' . $rule[3];
							$flag = false;
						}
					} else if (isset($rule[2])) {
						//长度测量-仅有最大值
						if ($len > $rule[2]) {
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
	public function getErrors() {
		return $this->errors;
	}

}