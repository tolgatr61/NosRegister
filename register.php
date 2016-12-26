<?php
require_once('config.php');
$user = cleanthis(@$_POST['user']);
$pass = cleanthis(@$_POST['pass']);
$confirmpass = cleanthis(@$_POST['c_pass']);
$ip = cleanthis($_SERVER['REMOTE_ADDR']);
$email = $_POST['email'];
$passkey = cleanthis(@$_POST['passkey']);
$data = date("Y-m-d H:i:s");
$cpasskey = cleanthis($_COOKIE['passkey']);
$email = filter_var($email, FILTER_SANITIZE_STRING);
$resp = $recaptcha->verify(@$_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
if($resp->isSuccess() or $usecaptcha == false)
{
	if($cpasskey == $passkey)
	{
		if($pass === $confirmpass)
		{
			if(strlen($pass) < 235 || strlen($user) < 255)
			{
				$params = array($user);
				$sql = "SELECT * FROM Account WHERE Name = ?";
				$opts = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
				$restul = sqlsrv_query($mssql, $sql, $params, $opts);
				$result = sqlsrv_num_rows($restul);
				if($result < 1)
				{
					if(filter_var($email, FILTER_VALIDATE_EMAIL))
					{
						$params = array($email);
						$sql = "SELECT * FROM Account WHERE Email = ?";
						$opts = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
						$result = sqlsrv_query($mssql, $sql, $params, $opts);
						$restul = sqlsrv_num_rows($result);
						if($restul < 1)
						{
							$pass = hash("sha512", $pass);
							$session = rand(1,9).rand(0,9);
							if($sendverification == true){
								$mailtoken = $passkey.$pass.$user.$data.$ip.$passkey.$title.$email.$session;
								$mailtoken = md5(md5($mailtoken).md5($mailtoken).$data.$ip);
								$sql = "INSERT INTO Account (Name, Password, Authority, LastSession, LastCompliment, Email, RegistrationIP, VerificationToken) VALUES ( ?, ?, '-1', ?, ?, ?, ?, ?)";
								$params = array($user, $pass, $session, $data, $email, $ip, $mailtoken);
								$result = sqlsrv_query($mssql, $sql, $params);
								registermail($email, $mailtoken, $user);
								exit(header("Location: index.php?reg=success&user=$user&mail=$email"));
							}
							else{
								$sql = "INSERT INTO Account (Name, Password, Authority, LastSession, LastCompliment, Email, RegistrationIP, VerificationToken) VALUES ( ?, ?, '0', ?, ?, ?, ?, 'yes')";
								$params = array($user, $pass, $session, $data, $email, $ip);
								$result = sqlsrv_query($mssql, $sql, $params);
								exit(header("Location: index.php?reg=sucess&user=$user"));
							}
						}
						else
							exit(header("Location: index.php?reg=maildup"));
					}
					else
						exit(header("Location: index.php?reg=mailfail"));
						
				}
				else
					exit(header("Location: index.php?reg=faildup"));
					
			}
			else
				exit(header("Location: index.php?reg=elimit"));
		}
		else
			exit(header("Location: index.php?reg=failpass"));
	}
	else
		exit(die("Nice try my friend! <b>Access Denied</b>"));
}
else
	exit(header("Location: index.php?reg=gfail"));
?>