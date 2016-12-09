<?php
require_once('config.php');
if(isset($_POST["user"]))
{
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}
	$username =  strtolower(trim($_POST["user"])); 
	$username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	$username = htmlspecialchars($username, ENT_QUOTES);
	$params = array($username);
	$sql = "SELECT Name FROM Account WHERE Name= ?";
	$results = sqlsrv_query($mssql, $sql, $params);
	$username_exist = sqlsrv_num_rows($results);
	if($username_exist == false)
		die('<i class="fa fa-ban" aria-hidden="true"></i> Username not available! Try again.');
	else
		die('<i class="fa fa-check" aria-hidden="true"></i> Available');
}
?>
