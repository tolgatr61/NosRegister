<?php
require_once('config.php');
$user = @$_POST['user'];
$pass = @$_POST['pass'];
$confirmpass = @$_POST['c_pass'];
$ip = @$_POST['ip'];
$passkey = @$_POST['passkey'];
$data = date("Y-m-d H:i:s");
$user = $sql->real_escape_string(stripslashes($user));
$pass = $sql->real_escape_string(stripslashes($pass));
$ip = $sql->real_escape_string(stripslashes($ip));
$confirmpass = $sql->real_escape_string(stripslashes($confirmpass));
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
				$session = rand(1,9).rand(0,9);
				$sql->query("INSERT INTO `account` (`Name`, `Password`, `Authority`, `LastSession`, `LastCompliment`) VALUES ('$user', '$pass', '0', '$session', '$data')");
				header("Location: index.php?reg=success&user=$user");
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