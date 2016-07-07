<?php
namespace app\models;
use core\Model;

class M_user extends Model
{
	public $table_name = 't_user';

	public function say() 
	{
		return 'hi, world';
	}
}