<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" ng-app>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="css/abc.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<?php
//include_once("scripts/connect.php");
include_once("conn/connect.php");
if(isset($_GET['idp'])) {
	$idp = $_GET['idp'];
}else{
	echo 'error en producto';
	die();
}
if(isset($_GET['q'])) {
	$q = $_GET['q'];
}else{
	$q=1;
}
if(isset($_COOKIE['id'])){
    // make sure no fields are blank /////

	if ($stmt = $conn->prepare("SELECT price, pack, pack_price FROM products WHERE idp  = ?")) {
		$stmt->bind_param("i", $idp);
		$stmt->execute();
		$stmt->store_result();
		$count = $stmt->num_rows;
		if($count == 1) {
			$stmt->bind_result($price, $pack, $pack_price);
			while ($stmt->fetch()) {
//				printf ("%d (%d) %d\n --", $price, $pack, $pack_price);
			}
		}
		$stmt->close();
	} else {

		return false;
	}


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

	if($count == 0){
		if ($stmt4 = $conn->prepare("INSERT INTO docs (doc_date, user, doc_type, doc_num, estatus) VALUES ( now(),?, 'SALE', 0, '000')")){
			$stmt4->bind_param("s", $_COOKIE['username']);
			$stmt4->execute();
			$stmt4->store_result();
            $idd = $conn->insert_id;
		} else {
		}
	} else {
	}

	if ($stmt = $conn->prepare("SELECT ide FROM doc_detail WHERE idd = ? and idp = ?")) {
		$stmt->bind_param("ii", $idd, $idp);
		$stmt->execute();
		$stmt->store_result();
		$count = $stmt->num_rows;
		if($count == 1) {
			$stmt->bind_result($ide);
			while ($stmt->fetch()) {
			}
		}
		$stmt->close();
	} else {
		$count = 0;
//		return false;
	}




	//// Check if username is in the db already ////
	try{
		$conn->autocommit(false);
		if($count == 0) {
			if ($stmt2 = $conn->prepare("INSERT INTO doc_detail (idd, idp, quantity, price, total, pack, pack_price,last_update) VALUES (?, ?, ?, ?, 0, ?, ?,now())")) {
				$stmt2->bind_param("iiidid", $idd, $idp, $q, $price, $pack, $pack_price);
				$stmt2->execute();
				$stmt2->store_result();
				$lastId = $conn->insert_id;
			}
		}else {
			if ($stmt2 = $conn->prepare("UPDATE doc_detail SET quantity = quantity+$q, last_update=now() where idd =  ? and idp =  ?")) {
				$stmt2->bind_param("ii", $idd, $idp);
				$stmt2->execute();
			}
		}

		$conn->commit();

		$stmt->close();
		$conn = null;
		header("location: viewcart.php");
		exit();
	}
		catch(mysqli_sql_exception $e){
			$conn->rollBack();
			echo "error";
			echo $e->getMessage();
			$conn = null;
			exit();
		}
}
?>
</body>
</html>
