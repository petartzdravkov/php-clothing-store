<div class="register_form m-auto">
    <h2 class="text-center"> Register </h2>
    <form method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?page=register">
	<!-- <div class="m-2">
	     <p></p>
	     </div> -->
	<p class="m-2 text-center text-danger"> <?= isset($error) ? $error : '';?></p>
	<input type="text" name="uname" placeholder="Username" class="m-2" value="<?=$uname;?>" autofocus><br>
	<input type="password" name="pass" placeholder="Password" class="m-2" value="<?=$pass;?>"><br>
	<input type="text" name="fname" placeholder="Full Name" class="m-2" value="<?=$fname;?>"><br>
	<input type="radio" id="gender" name="gender" class="m-2" value="male" <?= isset($gender) && $gender === "male" ? "checked" : "";?>>
	<label for="gender"> Male </label>
	<input type="radio" id="gender" name="gender" class="m-2" value="female" <?= isset($gender) && $gender === "female" ? "checked" : "";?>>
	<label for="gender"> Female </label>
	<div class="text-center">
	    <input type="submit" name="register_btn" value="Register" class="m-2">
	</div>
    </form>
</div>
