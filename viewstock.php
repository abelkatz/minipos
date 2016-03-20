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
	$tstock =0;
	$tsalep =0;
	$tsalea =0;
	if ($stmt = $conn->prepare("select idp, name, image, type, gender, stock, price, pack, pack_price, stock, salep, salea from products where canceled = 0 order by type, gender, name")) {

//		$stmt->bind_param();
		$stmt->execute();
		$stmt->store_result();
		$count = $stmt->num_rows;
			$stmt->bind_result($idp, $name, $image, $type, $gender, $stock, $price, $pack, $pack_price, $stock, $salep, $salea);
			while ($stmt->fetch()) {
				$tstock += $stock;
				$tsalep += $salep;
				$tsalea += $salea;
				$out .= "<tr class='negro'><td >$name</td><td >$stock</td><td >".number_format($price,2)."</td><td >$pack</td><td >".number_format($pack_price,2)."</td><td >$salep</td><td >".number_format($salea,2)."</td><td>";

				$out .= '<span class="boton4"  data-toggle="modal" onclick="prodedit('.$idp.');" data-target="#myModal">  <img src="img/edit.png"   style="width:24px;"></span>';
				$out .= "</td></tr>";
			}

		$stmt->close();
		$out2 = "<table class='cel4'>";
		$out2 .= "<tr class='total'><td >Producto</td><td >Stock</td><td >Precio</td><td >Pzas Paq</td><td >precio pack</td><td >Vta Pzs</td><td >Vta $</td><td >Accion</td></tr>";
		$out2 .= "<tr class='total'><td >Total</td><td >$tstock</td><td ></td><td ></td><td ></td><td >$tsalep</td><td >".number_format($tsalea,2)."</td><td>";
		$out2 .= '<span class="boton4"  data-toggle="modal" onclick="prodedit(0);" data-target="#myModal">  <img src="img/add.png"   style="width:24px;"></span>';
		$out2 .= "</td></tr>";

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
