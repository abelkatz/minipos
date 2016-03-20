<?php
include_once("conn/check_user.php");
$your_site = 'http://olejadash.com/demopos/';

if($user_is_logged == true){
//	header("location: index.php");
	echo "already logged";
	exit();
}
if(isset($_POST['username'])){
    $username = strip_tags($_POST['username']);
    $email1 = strip_tags($_POST['email1']);
    $email2 = strip_tags($_POST['email2']);
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    // make sure no fields are blank /////
    if(trim($username) == "" || trim($email1) == "" || trim($pass1) == "" || trim($pass2) == ""){
        echo "Error: All fields are required. Please press back in your browser and try again.";
        $db = null;
        exit();
    }
    /// Make sure both email fields match /////
    if($email1 != $email2){
        echo "Your email fields do not match. Press back and try again";
		$db = null;
        exit();
    }
    //// Make sure both password fields match ////
    else if($pass1 != $pass2){
        echo "Your password fields do not match. Press back and try again";
		$db = null;
        exit();
    }
	if(!filter_var($email1, FILTER_VALIDATE_EMAIL)){
		echo "You have entered an invalid email. Press back and try again";
		$db = null;
        exit();
	}
    //// create the hmac /////
    $hmac = hash_hmac('sha512', $pass1, file_get_contents('secret/key.txt'));
    //// create random bytes for salt ////
    $bytes = mcrypt_create_iv(16, MCRYPT_RAND );
    //// Create salt and replace + with . ////
    $salt = strtr(base64_encode($bytes), '+', '.');
    //// make sure our bcrypt hash is 22 characters which is the required length ////
    $salt = substr($salt, 0, 22);
    //// This is the hashed password to store in the db ////
    $bcrypt = crypt($hmac, '$2y$12$' . $salt);
    $token = md5($bcrypt);
	//// query to check if email is in the db already ////

	if ($stmt = $conn->prepare("SELECT email FROM members WHERE email=?")) {
		$stmt->bind_param("s", $email1);
		$stmt->execute();
		$stmt->store_result();
		$count = $stmt->num_rows;
		$stmt->close();
	} else {
		return false;
	}


	if ($stmt = $conn->prepare("SELECT username FROM members WHERE username=?")) {
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->store_result();
		$unCount = $stmt->num_rows;
		$stmt->close();
	} else {
		return false;
	}

	if($count > 0){
		echo "Sorry, that email is already in use in the system";
		$conn = null;
		exit();
	}
	//// Check if username is in the db already ////
	if($unCount > 0){
		echo "Sorry, that username is already in use in the system";
		$conn = null;
		exit();
	}
	try{
		$conn->autocommit(false);
		$ipaddress = getenv('REMOTE_ADDR');
		if ($stmt2 = $conn->prepare("INSERT INTO members (username, email, password, signup_date, ipaddress) VALUES (?, ?, ?, now(), ?)")){
			$stmt2->bind_param("ssss", $username, $email1, $bcrypt, $ipaddress);
			$stmt2->execute();
			$stmt2->store_result();
		}
		/// Get the last id inserted to the db which is now this users id for activation and member folder creation ////
		$lastId = $conn->insert_id;
		if($stmt3 = $conn->prepare("INSERT INTO activate (user, token) VALUES ('$lastId', ?)")){
			$stmt3->bind_param("s",$token);
			$stmt3->execute();
			$stmt3->store_result();
		}
		//// Send email activation to the new user ////
		$from = "noreply@secureserver.net";
		$subject = "Activate your DEMO account";
		$link = $your_site.'activate.php?user='.$lastId.'&token='.$token.'';
		//// Start Email Body ////yahoo
		$message = "
Thanks for registering an account.
Theres just one last step to set up your account. Please click the link below to confirm your identity and get started.

$link
";
		//// Set headers ////
		$headers = 'MIME-Version: 1.0' . "rn";
		$headers .= "Content-type: textrn";
		$headers .= "From: ".$from."rn";
		/// Send the email now ////
		mail($email1, $subject, $message, $headers, '-f abelkatz@yahoo.com');
		//mail($email1, $subject, $message, $headers, '-f noreply@your-email.com');
		$conn->commit();
		echo "Thanks for joining! Check your email in a few moments to activate your account so that you may log in. See you on the site!";
		echo "<a href='javascript:window.close()'>close</a>";

		$conn = null;
		exit();
	}
		catch(mysqli_sql_exception $e){
			$conn->rollBack();
			echo $e->getMessage();
			$conn = null;
			exit();
		}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register Your Account</title>
<link rel="stylesheet" href="style/style.css"/>
<style type="text/css">
.contentBottom{
	width:68%;
	margin-left:auto;
	margin-right:auto;
}
</style>
<!--[if lte IE 7]>
<style>
.content { margin-right: -1px; } 
ul.nav a { zoom: 1; }
</style>
<![endif]-->
</head>
<body>
<div class="container">
  <div class="content">
  <div class="contentBottom">
    <form action="" method="post" class="form">
    <h3 style="text-align:center">Sign Up here</h3>
<label for="username"><strong>Username</strong>
<br />
<input type="text" name="username">
</label>
<br />
<label for="email1"><strong>Email</strong>
<br />
<input type="text" name="email1">
</label>
<br />
<label for="email2"><strong>Confirm Email</strong>
<br />
<input type="text" name="email2">
</label>
<br />
<label for="pass1"><strong>Create Password</strong>
<br />
<input type="password" name="pass1">
</label>
<br />
<label for="pass2"><strong>Confirm Password</strong>
<br />
<input type="password" name="pass2">
</label>
<br />
<br />
<p class="submit">
<button type="submit">Sign Up</button>
</p>
</form>
  <br />
  <br />
  </div>
</div>
    <div class="clearfloat"></div>
  <!-- end .container --></div>
</body>
</html>
