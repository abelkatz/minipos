<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Agrega palabra</title>
    <link href="css/abc.css" rel="stylesheet" type="text/css" media="screen" />
</head>

<body>

    <?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html; charset=UTF-8");
header("Content-Language: HE");


include_once("conn/connect.php");
$idp = $_GET['idp'];

    $query = "SELECT idp, name, stock, cost, price, pack, pack_price, type, gender, ppp, image, canceled from products where idp=$idp  ";


$outp = "";
$result = $conn->query($query);
$i = 0;
    if($idp == 0){
        $outp .=putHiddenRow('idp',"");
        $outp .=putRow('name',"");
        $outp .=putRow('type',"");
        $outp .=putRow('gender',"");
        $outp .=putRow('cost',"");
        $outp .=putRow('price',"");
        $outp .=putRow('pack',"");
        $outp .=putRow('pack_price',"");
        $outp .=putRow('ppp',"");
        $outp .=putRow('stock',"0");
		$outp .=putFile('image',"");

    } else{
        while ($row = mysqli_fetch_assoc($result)) {
            $renglon = "";
            $outp .=putHiddenRow('idp',$row["idp"]);
            $outp .=putRow('name',$row["name"]);
            $outp .=putRow('type',$row["type"]);
            $outp .=putRow('gender',$row["gender"]);
            $outp .=putRow('cost',$row["cost"]);
            $outp .=putRow('price',$row["price"]);
            $outp .=putRow('pack',$row["pack"]);
            $outp .=putRow('pack_price',$row["pack_price"]);
            $outp .=putRow('ppp',$row["ppp"]);
            $outp .=putRow('stock',$row["stock"]);
            $outp .=putFile('image',$row["image"]);
            $outp .=putChk('canceled',$row["canceled"]);
        }

    }
$outp .="<tr><td>action</td><td ><button  type='submit' onclick='closewindow()'  >Update</button>";
$outp .="</td></tr>";
$outp = "<form method='post' action='prodUp.php' id='form1' name='form1' target='up' enctype='multipart/form-data'><table class='cel3a'>".$outp."</table></form>";

$conn->close();
echo($outp);
    function putRow($key,$value){
        $out ="<tr><td >$key</td><td > <input  type='text' id='$key' name='$key' value='" . $value . "'></td></tr>" ;
        return $out;
}
    function putChk($key,$value){
		if ($value == 1){$chked = "checked";}else{$chked = "";}
        $out ="<tr><td >$key</td><td > <input  type='checkbox' id='$key' name='$key' $chked '></td></tr>" ;
        return $out;
}
    function putFile($key,$value){
		if ($value!=""){$aa="<br/>Actual:".$value;} else {$aa = "";}
        $out ="<tr><td >$key $aa</td><td ><input type='file' name='$key' id='$key'></td></tr>" ;
        return $out;
}
    function putHiddenRow($key,$value){
        $out ="<tr><td ><input  type='hidden' id='$key' name='$key' value='" . $value . "'></td></tr>" ;
        return $out;
    }
    function putRowSel($key,$value,$conn,$table,$index,$show){
        $out ="<tr><td >$key</td><td >";
        $out .="<select id='$key' name='$key'>";
        $out .="<option value=''>Choose</option>";
        $query1 = "SELECT $index, $show FROM $table";
        $result1 = $conn->query($query1);
        while ($row1 = mysqli_fetch_assoc($result1)) {
            $out .="<option value='".$row1[$index]."'";
            if($row1[$index]==$value){
                $out .= " selected";
            }
            $out .= ">".$row1[$show]."</option>";
        }
        $out .="</select>";
        $out .= "</td></tr>" ;
        return $out;
    }


    ?>
<form action="upload.php" method="post" enctype="multipart/form-data">
	</body>
</html>
