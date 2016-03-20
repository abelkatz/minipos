<?php
/**
 * Created by PhpStorm.
 * User: abel
 * Date: 16/12/2015
 * Time: 06:10 PM
 */
?>
<!DOCTYPE html>
<html>

<head>
    <meta content="es" http-equiv="Content-Language" />
    <link href="css/abcd.css" rel="stylesheet" type="text/css" media="screen" />

    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

    <?php
    header('Content-Type: text/html; charset=UTF-8');
    ?>

</head>

<body>

<?php
include_once("conn/connect.php");
//echo "<br/>";
//echo $sql;
?>


<?php
//$split = explode(" ", $sql);
//$table = $split[count($split)-1];

//$valkey = $_POST["valor"];


	// sube archivo
if ($_FILES["image"]["name"]==""){
	echo "no hay imagen";
	$hayimagen = FALSE;
}else{
	$hayimagen = TRUE;
	$target_dir = "images/";
	$target_file = $target_dir . basename($_FILES["image"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image

		$check = getimagesize($_FILES["image"]["tmp_name"]);
		if($check !== false) {
			echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "File is not an image.";
			$uploadOk = 0;
		}
	// Check if file already exists
	if (file_exists($target_file)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
	}
	// Check file size
		$filename = $_FILES['image']['tmp_name'];
		list($width, $height) = getimagesize($filename);
		echo "w".$width; 
		echo "h".$height;  
		$relacion = $width/$height;
		echo "r:$relacion";
	if ($relacion != 0.75) {
		echo "relacion debe ser 3 horizontal a 2 vertical";
//		$uploadOk = 0;
	}
	if ($_FILES["image"]["size"] > 1500000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
		die();
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
			echo "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
		} else {
			echo "Sorry, there was an error uploading your file.";
			die();
		}
	}
}
if($_POST["idp"]==""){
    if ($stmt2 = $conn->prepare("INSERT INTO products (name,  cost, price, pack, pack_price, ppp, type, gender,image,stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?)")){
        $stmt2->bind_param("sddidisssi", $_POST["name"],$_POST["cost"],$_POST["price"],$_POST["pack"],$_POST["pack_price"],$_POST["ppp"],$_POST["type"],$_POST["gender"],$_FILES["image"]["name"],$_POST["stock"]);
        $stmt2->execute();
        $stmt2->store_result();
		$idp = $conn->insert_id;
		echo "se inserto registro";
	}
}
else{
	if (isset($_POST['canceled'])) {
		$canceled = 1;
	} else {
		$canceled = 0;
	}
	echo "canceled $canceled";
    try {


        // get result of record from 'id'
        $sql2 = "UPDATE products set name = '" . $_POST["name"] . "'";
        $sql2 .= ",cost = ". $_POST["cost"] . "";
        $sql2 .= ",price = ". $_POST["price"] . "";
        $sql2 .= ",pack = ". $_POST["pack"] . "";
        $sql2 .= ",pack_price = ". $_POST["pack_price"] . "";
        $sql2 .= ",ppp = ". $_POST["ppp"] . "";
		$sql2 .= ",stock = ". $_POST["stock"] . "";
        $sql2 .= ",type = '". $_POST["type"] . "'";
        $sql2 .= ",gender = '". $_POST["gender"] . "'";
        $sql2 .= ",canceled = ". $canceled . "";
		IF 	($hayimagen == TRUE)
		{
				$sql2 .= ",image = '". $_FILES["image"]["name"] . "'";			
		}
        $sql2 .= " WHERE idp=". $_POST["idp"];
//        echo $sql2;

        $result = $conn->query($sql2);

        if (!$result) {
            throw new Exception($conn->error);
        }else{
//            echo 'No entro a throw new Exception<br/>';
            echo "se actualizo registro<br/>";
        }
    }
    catch (Exception $e)
    {
        echo 'error - tal vez no hubo cambios';
        //die();
    }
}

//$result->free();





?>



</body>
</html>
