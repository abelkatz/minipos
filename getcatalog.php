<?php
/**
 * Created by PhpStorm.
 * User: abel
 * Date: 23/12/2015
 * Time: 07:29 PM
 */


header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html; charset=UTF-8");
//header("Content-Type: application/json; charset=UTF-8");
header("Content-Language: HE");

include_once("conn/connect.php");
//$json_file_name = 'jasonfile';
//if(isset($_COOKIE['id'])) { para limitar datos
if(isset($_COOKIE['id'])){



    $query = "select idp, image, type, gender, stock, price, pack, pack_price, ppp, name";
    $query .= " from products where canceled = 0 order by type, gender limit 100";
//echo $query;
    $result = $conn->query($query);
    $outp = "";
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $renglon = '<div id="products" class="minileter">';
        $renglon .='<div>';
        $renglon .= "<p align='center'>";
        $renglon .='<img src="images/' . $row["image"] . '" onclick="addtocart(' . $row["idp"] . ')" >';
        $renglon .= "<a href='images/". $row["image"] . "' target='_blank'>".$row["name"]."<br/>".$row["price"]."-".$row["pack_price"];
        $renglon .= "</a></p>";
        $renglon .= "</div>";
 //       $renglon .= "</div>".$row["type"]."</br>".$row["gender"]."</br>";
        $renglon .= '</div>';
        $outp .=  $renglon ;
    }
    $conn->close();

    echo($outp);
}else{
    echo "No credentials!";
}
