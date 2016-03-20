<?php
//include_once("scripts/connect.php");
include_once("conn/connect.php");
if(isset($_GET['ide'])) {
	$ide = $_GET['ide'];
}else{
	echo 'error no ide';
	die();
}
if(isset($_COOKIE['id'])) {
	// make sure no fields are blank /////


	if ($stmt4 = $conn->prepare("DELETE FROM doc_detail where ide = ?")) {
		$stmt4->bind_param("s", $ide);
		$stmt4->execute();

		$stmt4->close();
		$conn = null;
		header("location: viewcart.php");
		exit();
	}
}
?>
