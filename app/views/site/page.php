<div class="content-box">
	<small>我才是真正的内容.</small>
	<p style="color:blue;"></p>
	<form action="/site/test" method="post">
		<input type="text" name="user_account">
		<input type="text" name="user_name">
		<input type="text" name="user_note">
		<?php echo \core\Autumn::app()->request->createToken();?>
		<button type="submit">submit</button>
	</form>
</div>