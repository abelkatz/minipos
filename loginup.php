<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
//error_reporting(E_ALL); ini_set('display_errors', 'On');
//ob_start();
include_once("conn/check_user.php");
if($user_is_logged == true){
	echo 'You are logged Click the link to continue: <a href="index.php" target="_parent">Refresh</a>';
	die();
}else{
}
if(isset($_POST['email']) && trim($_POST['email']) != ""){
	$email = strip_tags($_POST['email']);
	$password = $_POST['password'];
	$hmac = hash_hmac('sha512', $password, file_get_contents('secret/key.txt'));

	$stmt1 = $conn->prepare("SELECT id, username, password FROM members WHERE email=? AND activated='1' LIMIT 1");
	$stmt1->bind_param("s", $email);
	$stmt1->execute();
	$stmt1->store_result();
	$count = $stmt1->num_rows;

	if($count > 0){
		$stmt1->bind_result($column1, $column2, $column3);
		while ($stmt1->fetch()) {
			$uid = $column1;
			$username = $column2;
			$hash = $column3;
		}
			if (crypt($hmac, $hash) === $hash) {
				if ($result = $conn->query("UPDATE members SET lastlog=now() WHERE id='$uid' limit 1")) {
//					echo 'last login...';
				}
				$_SESSION['uid'] = $uid;
				$_SESSION['username'] = $username;
				$_SESSION['password'] = $hash;
				setcookie("id", $uid, strtotime( '+30 days' ), "/", "", "", TRUE);
				setcookie("username", $username, strtotime( '+30 days' ), "/", "", "", TRUE);
    			setcookie("password", $hash, strtotime( '+30 days' ), "/", "", "", TRUE); 
				/* echo 'Valid password<br />'.$_SESSION['uid'].'<br />'.$_SESSION['username'].'<br />'.$_SESSION['password'].'
				<br />'.$_COOKIE['id']; */
				echo "<a href='index.php' target='_parent'>acreditado! click para continuar</a>";
//				header('Location: /miniposClass/index.php');
//				exit;
			} else {
				echo 'Invalid password Press back and try again<br />';
				exit();
			}
		}
		else{
			echo "A user with that email address does not exist here";
			$conn = null;
			exit();
		}

}
?>
</body>
</html>

