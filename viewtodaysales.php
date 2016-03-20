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
	if ($stmt = $conn->prepare("select idd, doc_date, tot_pieces, tot_money from docs where estatus = '100' and doc_type = 'SALE' and closure_date is NULL order by doc_date")) {
	$cr = 0;
//		$stmt->bind_param();
		$stmt->execute();
		$stmt->store_result();
		$count = $stmt->num_rows;
			$stmt->bind_result($idd, $doc_date, $salep, $salea);
			while ($stmt->fetch()) {
				$tsalep += $salep;
				$tsalea += $salea;
				$cr++;
				$out .= "<tr class='totaleft'><td >$doc_date</td><td >$salep</td><td >".number_format($salea,2)."</td><td class='totl2'><span class='boton4'  id='cancelsale' name='cancelsale' onclick='cancelsale($idd);'>CANCELA</span></td></tr>";
				if ($stmt2 = $conn->prepare("SELECT products.name, doc_detail.quantity,doc_detail.total  FROM doc_detail INNER JOIN products ON doc_detail.idp = products.idp  WHERE doc_detail.idd = ? order by doc_detail.last_update DESC")) {
					$stmt2->bind_param("i", $idd);
					$stmt2->execute();
					$stmt2->store_result();
					$count = $stmt2->num_rows;
					$outd = "";
					if($count != 0) {
						$stmt2->bind_result($name,$q,$tot);
						while ($stmt2->fetch()) {
							$outd .= "<tr><td >$name</td><td >$q</td><td >".number_format($tot,2)."</td></tr>";
						}

					}
				} else {
//					$outd = "No products";
				}
				$out .= $outd;

				}

		$stmt->close();
		$out2 = "<table class='cel4'>";
		$out2 .= "<tr class='totalf'><td >Hora</td><td >Vta Pzs</td><td >Vta $</td><td >Accion</td></tr>";
		$out2 .= "<tr class='totalf'><td >Total</td><td >$tsalep</td><td >".number_format($tsalea,2)."</td><td class='totl'><span class='boton4'  id='closesale' name='closesale' onclick='seesalesperday();'>HISTORIAL</span></td></tr>";
		$out = $out2.$out."</table>";
		if($cr > 0){
			$out .= "<span class='boton5'  id='closesale' name='closesale' onclick='closeday();'>HACER CORTE DIARIO!!!</span>";
		}
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
