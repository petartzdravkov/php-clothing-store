<div class="login_form m-auto">
    <h2 class="text-center"> Login </h2>
    <form method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?page=login">

	<p class="m-2 text-center text-danger"> <?= isset($error) ? $error : '';?></p>
	<input type="text" name="uname" placeholder="Username" class="m-2" value="<?=$uname;?>" autofocus><br>
	<input type="password" name="pass" placeholder="Password" class="m-2" value="<?=$pass;?>"><br>
	<div class="text-center">
	    <input type="submit" name="login_btn" value="Login" class="m-2">
	</div>
    </form>
</div>
