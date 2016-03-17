<?php
require_once('config.php');
//Getting data from user//
$user = @$_POST['user'];
//$mail = @$_POST['email'];
$pass = @$_POST['pass'];
$confirmpass = @$_POST['c_pass'];
$ip = @$_POST['ip'];
$passkey = @$_POST['passkey'];
$data = date("D M d H:i:s Y");
////////////SQL INJECT???///////
$user = $sql->real_escape_string(stripslashes($user));
//$mail = $sql->real_escape_string(stripslashes($mail));
$pass = $sql->real_escape_string(stripslashes($pass));
$ip = $sql->real_escape_string(stripslashes($ip));
$confirmpass = $sql->real_escape_string(stripslashes($confirmpass));
////////////////////////////////
if($_COOKIE['passkey'] == $passkey)
{
	if($pass === $confirmpass)
	{
		if(strlen($pass) < 235 || strlen($user) < 255)
		{
			$restul = $sql->query("SELECT * FROM `account` WHERE `Name`='$user'");
			if($restul->num_rows < 1)
			{
				$pass = hash("sha256", $pass);
				$sql->query("INSERT INTO `account` (`Name`, `Password`, `Authority`) VALUES ('$user', '$pass', '0')");
				header("Location: index.php?reg=success");
				exit();
			}
			else
			{
				header("Location: index.php?reg=faildup");
				exit();
			}
		}
		else {
			header("Location: index.php?reg=elimit");
			exit();
		}
	}
	else {
		header("Location: index.php?reg=failpass");
		exit();
	}
}
else
{
	die("Nice try my friend! <b>Access Denied</b>");
}
?>