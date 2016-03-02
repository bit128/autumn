<?php
class User
{
	public $user_name = 'hongbo';
	public function say()
	{
		echo 'hello:', $this->user_name;
	}
}