<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" ng-app>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="css/default.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<?php
//include_once("scripts/connect.php");
include_once("conn/connect.php");
if(isset($_COOKIE['id'])){
    // make sure no fields are blank /////
	$out="";
	$tsalep =0;
	$tsalea =0;
	if ($stmt = $conn->prepare("select closure_date as d, sum(tot_pieces) as q, sum(tot_money) as m  from docs where estatus = '100' and doc_type = 'SALE' and closure_date is not NULL group by closure_date")) {

//		$stmt->bind_param();
		$stmt->execute();
		$stmt->store_result();
		$count = $stmt->num_rows;
			$stmt->bind_result($doc_date, $salep, $salea);
			while ($stmt->fetch()) {
				$tsalep += $salep;
				$tsalea += $salea;
				$out .= "<tr class='negro'><td >$doc_date</td><td >$salep</td><td >".number_format($salea,2)."</td></tr>";
			}

		$stmt->close();
		$out2 = "<table class='cel4'>";
		$out2 .= "<tr class='total'><td >Fecha</td><td >Vta Pzs</td><td >Vta $</td><td >Accion</td></tr>";
		$out2 .= "<tr class='total'><td >Total</td><td >$tsalep</td><td >".number_format($tsalea,2)."</td><td class='totl'><span class='boton4'  id='closesale' name='closesale' onclick='seesales();'>VTA DIA</span></td></tr>";
		$out = $out2.$out."</table>";
		echo $out;

	} else {
		$count = 0;
		$idd = 0;
	}

		$conn = null;
		exit();
}
?>
</body>
</html>
