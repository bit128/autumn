<?php
namespace app\models;
use core\db\Orm;

class M_user extends Orm
{
	public $table_name = 't_user';

	public function rules()
    {
        return [
			//'field' => ['必须存在','类型','最少位数','最多位数']
			'user_email' => [true, 'email', 10, 60],
			'user_name' => [true, 'word', 6, 12],
			'user_age' => [true, 'number', 2],
			'user_note' => [false, 'text', 20]
		];
    }
}