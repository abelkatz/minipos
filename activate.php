<?php
if(isset($_GET['user']) && $_GET['user'] != "" && isset($_GET['token']) && $_GET['token'] != "") {
	include_once("conn/connect.php");
	$user = preg_replace('#[^0-9]#', '', $_GET['user']);
	$token = preg_replace('#[^a-z0-9]#i', '', $_GET['token']);

	$query = "SELECT user, token FROM activate WHERE user=? AND token=?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("ss", $user, $token);
	$stmt->execute();
	$stmt->store_result();
	$count = $stmt->num_rows;
	$stmt->close();


	if ($count > 0) {
		try {
			$conn->autocommit(false);

			if ($stmt = $conn->prepare("UPDATE members SET activated='1' WHERE id=? LIMIT 1")) {
				$stmt->bind_param("s", $user);
				$stmt->execute();
			} else {
				echo 'error ..-.';
				return false;
			}

			if ($deleteSQL = $conn->prepare("DELETE FROM activate WHERE user=? AND token=? LIMIT 1")) {
				$deleteSQL->bind_param("ss", $user, $token);
				$deleteSQL->execute();
//					$deleteSQL->close();
			} else {
				echo 'error ..-.';
				return false;
			}


			if (!file_exists("members/$user")) {
				mkdir("members/$user", 0755);
			}
			$conn->commit();

			echo 'Your account has been activated! Click the link to log in: <a href="login.php">Log In</a>';
			$stmt->close();
			$deleteSQL->close();
			$conn = null;
			exit();

		} catch (mysqli_sql_exception  $e) {
			$conn->rollBack();
			echo 'errores....';
		}
	} else {
		echo "Sorry, There has been an error. Maybe try registering again derp.";
		$conn->rollback();
		$conn = null;
		exit();
	}
}else{
	echo 'no hay params';
}

?>