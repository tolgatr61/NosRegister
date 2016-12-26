<?php
require_once("config.php");
$key = cleanthis(@$_GET['key']);
$status = cleanthis(@$_POST['status']);
if($status == "active")
{
	$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
	if($resp->isSuccess() or $usecaptcha == false)
	{
		$code = cleanthis($_POST['key']);
		if(strlen($code) == 32)
		{
			$code = strtolower($code);
			$params = array($code);
			$sql = "SELECT * FROM Account WHERE VerificationToken = ?";
			$opts = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			$result = sqlsrv_query($mssql, $sql, $params, $opts);
			$result = sqlsrv_num_rows($result);
			if($result == 1)
			{
				$params = array($code);
				$loverandom = md5(md5(rand(0,9)).md5(rand(0,9).$code));
				$sql = "UPDATE Account SET VerificationToken = '$loverandom', Authority = '0' WHERE VerificationToken = ?";
				$result = sqlsrv_query($mssql, $sql, $params);
				exit(header("Location: index.php?reg=active"));
				
			}
			else
				exit(header("Location: index.php?reg=nokey"));
		}
		else
			exit(header("Location: index.php?reg=notkey"));
	}
	else
			exit(header("Location: index.php?reg=gfailkey"));
}
?>
<html>
	<head>
		<title><?= $title?></title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Cache-control" content="private">
		<script src="./js/jquery.min.js"></script>
		<script src="./js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="./css/style.min.css">
		<link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css">
		<script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
	<body>
		<div id='cont'>
			<form action='activeacc.php' method='POST'>
			  <div class='form-group'>
				<label for='user'>Activation Code</label>
				<input type='text' class='form-control' id='noremember' <?php if(strlen($key) == 32) echo "value='$key'";?> onfocus="this.removeAttribute('readonly');" pattern=".{32,32}" placeholder='Activation Code' name='key' required>
				<p class="help-block">Your activation code from your mail address</p>
			  </div>
<?php
if($usecaptcha == true)
  echo "<div style='display: block;text-align: center;text-align: -webkit-center;'><div class='g-recaptcha' id='googlechap' data-sitekey='$captchapublickey;'></div></div>";
?>
			  <center><button type='submit' class='btn btn-success' name='status' value='active'>Active my account now!</button>
			</form>
		</div>
	</body>
</html>