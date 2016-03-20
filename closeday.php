<?php
//include_once("scripts/connect.php");
include_once("conn/connect.php");

if(isset($_COOKIE['id'])) {
	// make sure no fields are blank /////

	$now = date("Y-m-d H:i:s");

	if ($stmt4 = $conn->prepare("UPDATE docs SET closure_date = ? where closure_date is NULL")) {
		$stmt4->bind_param("s", $now);
		$stmt4->execute();

		$stmt4->close();
		$conn = null;
		echo "SE HIZO CIERRE DIARIO";
		exit();
	}else{
		echo "NO SE HIZO CIERRE DIARIO";

	}
}
?>
