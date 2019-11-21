<?php
namespace app\models;

/**
* 业务模型示例
* ======
* @author bit128
* @version 17.09.28
*/
class User extends \core\web\Model {

	public $table_name = 't_user';

	public function rules() {
        return [
			//'field' => ['必须存在','类型','最少位数','最多位数']
			'user_email' => [true, 'email', 5, 60],
			'user_name' => [true, 'word', 6, 12]
		];
    }
}