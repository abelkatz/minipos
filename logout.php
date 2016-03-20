<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="css/abc.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
session_start();
$user_is_logged=false;

if(isset($_COOKIE['id']) && isset($_COOKIE['username']) && isset($_COOKIE['password'])){
    setcookie("id", '', strtotime('-30 days'), '/');
    setcookie("username", '', strtotime('-30 days'), '/');
    setcookie("password", '', strtotime('-30 days'), '/');
}
session_destroy();
header("Location: logoutup.php");
exit;
?>
</body>
</html>
