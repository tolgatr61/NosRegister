<?php
require_once('config.php');
$user = cleanthis(@$_POST['user']);
$pass = cleanthis(@$_POST['pass']);
$confirmpass = cleanthis(@$_POST['c_pass']);
$ip = cleanthis($_SERVER['REMOTE_ADDR']);
$passkey = cleanthis(@$_POST['passkey']);
$data = date("Y-m-d H:i:s");
$cpasskey = cleanthis($_COOKIE['passkey']);

$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
if($resp->isSuccess())
{
	if($cpasskey == $passkey)
	{
		if($pass === $confirmpass)
		{
			if(strlen($pass) < 235 || strlen($user) < 255)
			{
				$params = array($user);
				$sql = "SELECT * FROM Account WHERE Name = ?";
				$restul = sqlsrv_query($mssql, $sql, $params);
				$result = sqlsrv_num_rows($restul);
				if($result  == true)
				{
					$pass = hash("sha512", $pass);
					$session = rand(1,9).rand(0,9);
					$sql = "INSERT INTO Account (Name, Password, Authority, LastSession, LastCompliment) VALUES ( ?, ?, '0', ?, ?)";
					$params = array($user, $pass, $session, $data);
					$result = sqlsrv_query($mssql, $sql, $params);
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
}
else {
	header("Location: index.php?reg=gfail");
	exit();
}
?>