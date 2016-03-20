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
if(isset($_GET['idd'])) {
	$idd = $_GET['idd'];
//	echo 'idd ok';
}else{
	echo 'error no idd';
	die();
}

if(isset($_COOKIE['id'])) {
    // make sure no fields are blank /////

    if ($stmt = $conn->prepare("SELECT idd FROM docs WHERE doc_type = 'SALE' and estatus = '000' and user =? and idd=?")) {
        $stmt->bind_param("si", $_COOKIE['username'], $idd);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        if ($count == 1) {
        } else{
            echo '-- ningun documento por procesar ---';
            $count = 0;
            die();

        }
        $stmt->close();
    } else {
        echo '-- ningun documento por procesar ---';
        $count = 0;
        die();
//		return false;
    }


    try {
        $conn->autocommit(false);

        if ($stmt = $conn->prepare("SELECT products.name, doc_detail.quantity, doc_detail.pack, doc_detail.price,doc_detail.pack_price,doc_detail.ide,doc_detail.idp  FROM doc_detail INNER JOIN products ON doc_detail.idp = products.idp  WHERE doc_detail.idd = ? order by doc_detail.last_update DESC")) {
            $stmt->bind_param("i", $idd);
            $stmt->execute();
            $stmt->store_result();
            $count = $stmt->num_rows;
            if($count != 0) {
                $stmt->bind_result($name,$q,$pack,$p,$pp,$ide,$idp);

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
                    $out .= "<tr><td class='$vende'>$name=$p ($pack=$pp)</td><td class='$vende'>$q</td><td class='$vende'>".number_format($tot,2)."</td></tr>";
                    if ($stmt2 = $conn->prepare("UPDATE doc_detail SET quantity = ?, total = ?, last_update=now() where ide =  ? ")) {
                        $stmt2->bind_param("idi",$q,$tot, $ide);
                        $stmt2->execute();
//                        echo '-- update dd! ---';
                    } else {
                        echo "error en update detail";
                        $ww = 1/0;
                    }

                    if ($stmt3 = $conn->prepare("UPDATE products SET stock = stock-?, salep = salep+?, salea=salea+? where idp =  ? ")) {
                        $stmt3->bind_param("iidi",$q,$q,$tot, $idp);
                        $stmt3->execute();
 //                       echo '-- update prod! ---';
                    } else {
                        echo "error en update prod";
                        $ww = 1/0;
                    }

                }
                if ($stmt4 = $conn->prepare("UPDATE docs SET estatus = '100', tot_pieces=?, tot_money=?, doc_num=99, doc_date=now() where idd =  ?")) {
                    $stmt4->bind_param("idi", $tq,$gt,$idd);
                    $stmt4->execute();
 //                   echo '-- update doc! ---';
                } else {
                    echo "error en update doc";
                    $ww = 1/0;
                }



                $out2 = "<h1>Venta procesada</h1>";
                $out2 .= "<table class='cel4'>";
                $out2 .= "<tr class='total'><td >Producto</td><td class='negro'>Cantidad</td><td class='negro'>Importe</td></tr>";
                $out2 .= "<tr class='total'><td >Total</td><td >$tq</td><td >".number_format($gt,2)."</td></tr>";
                $out = $out2.$out."</table>";
echo $out;
            }
        } else {
            $count = 0;
//		return false;
        }

        $conn->commit();

        $stmt->close();
        $conn = null;
        exit();
    } catch (mysqli_sql_exception $e) {
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
