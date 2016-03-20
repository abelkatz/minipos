<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<?php
include_once("conn/check_user.php");
$x = rand();
if($user_is_logged == true){
	echo 'Estas firmado!';
	die();
}else{
}
?>
<form method='post' action='loginup.php' id='form1' name='form1'  class="form" >
    <h3>Log In</h3>
    <strong>Email</strong><br />
	<input type="text" name="email">
	<br />
<strong>Password</strong><br />
	<input type="password" name="password">
	<br />
    <br />
    <p class="submit">
		<button  type='submit'   >Login</button>
    </p>
  </form>
</body>
</html>

