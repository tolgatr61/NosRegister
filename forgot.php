<?php
require_once('config.php');
$status = cleanthis(@$_REQUEST['status']);
?>
<html>
	<head>
		<title><?=$title?></title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
		<script src="./js/jquery.min.js"></script>
		<script src="./js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="./css/style.min.css">
		<link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css">
		<script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
	<body>
		<div id='cont'>
<?php
switch($status) {
	case "keynotgood":
		echo "<p style='color:red;font-width:bold;'>You enter an invalid token! Please try again!</p>";
		break;
	case "nokeyfound":
		echo "<p style='color:red;font-width:bold;'>I can't find your token did you enter right? Please try again!</p>";
		break;
	case "gcap":
		echo "<p style='color:red;font-width:bold;'>You enter captcha wrong. Try again!</p>";
		break;
	case "mailnotfound":
		echo "<p style='color:red;font-width:bold;'>I can't find your mail. Did you type mail right? Please try again!</p>";
		break;
	case "filterfail":
		echo "<p style='color:red;font-width:bold;'>This don't look like mail address did you try to cheat? Try again!</p>";
		break;
	case "changepass":
		$key = cleanthis($_POST["fkey"]);
		$pass = cleanthis($_POST["pass"]);
		$cpass = cleanthis($_POST["c_pass"]);
		$resp = $recaptcha->verify(@$_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
		if($resp->isSuccess() or $usecaptcha == false)
		{
			if($pass === $cpass)
			{
				if(strlen($key) == 32)
				{
					$key = strtolower($key);
					$params = array($key);
					$sql = "SELECT * FROM Account WHERE VerificationToken = ?";
					$opts = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
					$result = sqlsrv_query($mssql, $sql, $params, $opts);
					$result = sqlsrv_num_rows($result);
					if($result == 1)
					{
						$pass = hash("sha512", $pass);
						$params = array($pass,$key);
						$loverandom = md5(md5(rand(0,9)).md5(rand(0,9).$key));
						$sql = "UPDATE Account SET VerificationToken = '$loverandom', Password = ? WHERE VerificationToken = ?";
						$result = sqlsrv_query($mssql, $sql, $params);
						echo "<h2 style='color:whitesmoke;'>Your password was changed successfully!";
						exit();
					}
					else
						exit(header("Location: forgot.php?status=nokeyfound"));
				}
				else
					exit(header("Location: forgot.php?status=keynotgood"));
			}
			exit(header("Location: forgot.php?status=enterkey&err=passpass&key=$key"));
		}
		exit(header("Location: forgot.php?status=gcap"));
		break;
    case "enterkey":
		$key = cleanthis(@$_GET["key"]);
		$err = cleanthis(@$_GET['err']);
		if($err == "passpass")
			echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Password must be same. Try again.</div>';
        echo "<form action='forgot.php' method='POST'>
				  <div class='form-group'>
					<label for='user'>Email</label>";
					if(strlen($key) == 32)
						echo "<input type='text' class='form-control' pattern='.{32,32}' placeholder='your forgot key' name='fkey' value='$key' required readonly>";
					else
						echo "<input type='text' class='form-control' pattern='.{32,32}' placeholder='your forgot key' name='fkey' required>";
					echo "<p class=\"help-block\">Enter the key was sent early on your email (check for it).</p>
				  </div>
			 <div class='form-group'>
				<label for='Password'>Password</label>
				<input type='password' class='form-control' pattern='.{6,30}' placeholder='Password' name='pass' required>
				<p class='help-block'>Password must be between 6 and 30 characters. We recommand to use complexe password.</p>
			  </div>
			  <div class='form-group'>
				<label for='Password1'>Repeat Password</label>
				<input type='password' class='form-control' pattern='.{6,30}' placeholder='Repeat Password' name='c_pass' required>
				<p class='help-block'>Repeat your password.</p>
			  </div>";
        if ($usecaptcha == true)
            echo "<div style='display: block;text-align: center;text-align: -webkit-center;'><div class='g-recaptcha' id='googlechap' data-sitekey='$captchapublickey'></div></div>";
        echo "<center><button type='submit' class='btn btn-default' name='status' value='changepass'>Change my password!</button></center></form>";
        break;
	case "forgot":
		$resp = $recaptcha->verify(@$_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
		if($resp->isSuccess() or $usecaptcha == false)
		{
			$email = $_POST["fmail"];
			$email = filter_var($email, FILTER_SANITIZE_STRING);
			if(filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$params = array($email);
				$sql = "SELECT * FROM Account WHERE Email = ?";
				$opts = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
				$result = sqlsrv_query($mssql, $sql, $params, $opts);
				$restul = sqlsrv_num_rows($result);
				if($restul == 1)
				{
					$keygen = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).$title.$_SERVER['REMOTE_ADDR'].rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
					$enckey = md5(md5($keygen).md5($keygen).$keygen);
					$params = array($enckey, $email);
					$sql = "UPDATE Account SET VerificationToken = ? WHERE Email = ?";
					$result = sqlsrv_query($mssql, $sql, $params);
					forgotmail($email, $enckey);
					echo "<p style='color:whitesmoke;'>An email has been sent to the address <span class='fmail'>$email</span> .<br/>Check your email and follow the instructions.</p>";
					exit();
				}
				else
					exit(header("Location: forgot.php?status=mailnotfound"));
			}
			else
				exit(header("Location: forgot.php?status=filterfail"));
		}
		else
			exit(header("Location: forgot.php?status=gcap"));
		break;
    default:
        echo "<form action='forgot.php' method='POST'>
				  <div class='form-group'>
					<label for='user'>Email</label>
					<input type='email' class='form-control' pattern=\"[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$\" placeholder='YourRegister@email.com' name='fmail' required>
					<p class=\"help-block\">Enter your email address.</p>
				  </div>";
        if ($usecaptcha == true)
            echo "<div style='display: block;text-align: center;text-align: -webkit-center;'><div class='g-recaptcha' id='googlechap' data-sitekey='$captchapublickey;'></div></div>";
        echo "<center><button type='submit' class='btn btn-default' name='status' value='forgot'>Send my password!</button></center></form>";
}
?>
		</div>
	</body>
</html>
