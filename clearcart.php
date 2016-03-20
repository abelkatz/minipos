<?php
//include_once("scripts/connect.php");
include_once("conn/connect.php");
if(isset($_COOKIE['id'])) {
	// make sure no fields are blank /////

	if ($stmt = $conn->prepare("SELECT idd FROM docs WHERE doc_type = 'SALE' and estatus = '000' and user =?")) {
		$stmt->bind_param("s", $_COOKIE['username']);
		$stmt->execute();
		$stmt->store_result();
		$count = $stmt->num_rows;
		if($count == 1) {
			$stmt->bind_result($idd);
			while ($stmt->fetch()) {
			}
		}
		$stmt->close();
	} else {
//		echo '-- no hay ---';
		$count = 0;
//		return false;
	}

	if ($stmt4 = $conn->prepare("DELETE FROM doc_detail where idd = ?")) {
		$stmt4->bind_param("s", $idd);
		$stmt4->execute();

		$stmt4->close();
		$conn = null;
		header("location: viewcart.php");
		exit();
	}
}
?>
