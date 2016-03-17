<?php
require_once('config.php');
if(isset($_POST["user"]))
{
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}
	$username =  strtolower(trim($_POST["user"])); 
	$username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	$results = $sql->query("SELECT `Name` FROM `account` WHERE `Name`='$username'");
	$username_exist = mysqli_num_rows($results);
	if($username_exist) {
		die('<i style="color:red;" class="fa fa-times-circle"></i> Username not available! Try again.');
	}else{
		die('<i style="color:lime;" class="fa fa-check"></i> Available');
	}
}
?>

