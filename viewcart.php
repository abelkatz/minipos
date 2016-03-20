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
if(isset($_COOKIE['id'])){
    // make sure no fields are blank ///// /
	$out="NO HAY PRODUCTOS EN EL CARRITO";
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
		$count = 0;
		$idd = 0;
	}

		if ($stmt = $conn->prepare("SELECT products.name, doc_detail.quantity, doc_detail.pack, doc_detail.price,doc_detail.pack_price,doc_detail.ide  FROM doc_detail INNER JOIN products ON doc_detail.idp = products.idp  WHERE doc_detail.idd = ? order by doc_detail.last_update DESC")) {
			$stmt->bind_param("i", $idd);
			$stmt->execute();
			$stmt->store_result();
			$count = $stmt->num_rows;
			if($count != 0) {
				$stmt->bind_result($name,$q,$pack,$p,$pp,$ide);
				$gt = 0;
				$tq=0;
				$out = "";
				while ($stmt->fetch()) {
					if ($pack>0){
						$qp = floor($q /$pack);
						$qs = $q % $pack;
						$tot = ($qp * $pp) + ($qs*$p) ;
					} else {
						$qp = 0;
						$qs = $q;
						$tot = ($qs*$p) ;
					}
					$gt += $tot;
					$tq += $q;
					if($pack!=0 and $qs!=0){
						$vende="rojo";
					}else{
						$vende="negro";
					}
					$out .= "<tr><td class='$vende'>$name=$p ($pack=$pp)</td><td class='$vende'>$q</td><td class='$vende'>".number_format($tot,2)."</td><td>&nbsp;&nbsp;&nbsp;<img src='img/delete.png' onclick='delrec($ide)'  style='width:24px;'></td></tr>";
				}
				$out2 = "<table class='cel4'>";
				$out2 .= "<tr class='total'><td >Producto</td><td class='negro'>Cantidad</td><td class='negro'>Importe</td></tr>";
				$out2 .= "<tr class='total'><td >Total</td><td >$tq</td><td >".number_format($gt,2)."</td><td class='totl'>&nbsp;&nbsp;<img src='img/delete.png' onclick='clearcart();'  style='width:24px;'></td></tr>";
			$out = $out2.$out."</table>";
$out .= "<span class='boton4'  id='sale' name='sale' onclick='sale($idd);'>COBRA</span>";
			}
		} else {
			$count = 0;
//		return false;
		}
		$stmt->close();
		$conn = null;
echo $out;
		exit();
}
?>
</body>
</html>
