<form action="/site/test" method="post">
	<input type="text" name="user_name">
	<?php echo \core\Autumn::app()->request->createToken(); ?>
	<button type="submit">提交</button>
</form>