<?php
require_once('config.php');
if(!isset($_COOKIE['passkey']))
{
	setcookie("passkey", "", time()-3600);
}
$secret = rand(1, 100) . rand(1, 100) . rand(1, 100) . rand(1, 100) . rand(1, 100) . rand(1, 100) . rand(1, 100) . $title . rand(1,100);
$secret = hash("sha512", $secret);
setcookie("passkey", $secret);
$headers = apache_request_headers();
if(isset($headers['If-Modified-Since'])) {
  if(strtotime($headers['If-Modified-Since']) < time() - 43200) {
    header('HTTP/1.1 304 Not Modified');
	 Header("Cache-Control: private");
    exit;
  }
}
$reg = @$_GET['reg'];
if($reg == "success")
	echo '<div id="alert" class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Welcome '.$_GET['user'].' ! Your account was created. Enjoy!</div>';
if($reg == "faildup")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Account name already exist. Please choose differit name</div>';
if($reg == "failpass")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Password must be same.</div>';
if($reg == "elimit")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Your password or username are too long.</div>';
if($reg == "gfail")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! You don\'t pass google captcha test. Are you a robot?</div>';
?>
<html>
	<head>
		<title><?= $title?></title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Cache-control" content="private">
		<script src="./js/jquery.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			$("#user").keyup(function (e) {
				$(this).val($(this).val().replace(/\s/g, ''));
				var username = $(this).val();
				if(username.length < 6){$("#user-result").html('');return;}
				if(username.length >= 6){
					$("#user-result").html('<i class="fa-li fa fa-spinner fa-spin"></i>');
					$.post('check_username.php', {'user':username}, function(data) {
					  $("#user-result").html(data);
					});
				}
			});	
		});
		</script>
		<script src="./js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="./css/style.css">
		<link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css">
		<script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
	<body>
		<div id='cont'>
			<form action='register.php' method='POST'>
			  <div class='form-group'>
				<label for='user'>Username</label>
				<input type='text' class='form-control' id='user' pattern=".{6,30}" placeholder='Username' name='user' required><span style="float:right;" id="user-result"></span>
				<p class="help-block">Username must be between 6 and 30 characters</p>
			  </div>
			  <div class='form-group'>
				<label for='Password'>Password</label>
				<input type='password' class='form-control' pattern=".{6,30}" placeholder='Password' name='pass' required>
				<p class="help-block">Password must be between 6 and 30 characters. We recommand to use complexe password.</p>
			  </div>
			  <div class='form-group'>
				<label for='Password1'>Repeat Password</label>
				<input type='password' class='form-control' pattern=".{6,30}" placeholder='Repeat Password' name='c_pass' required>
				<p class="help-block">Repeat your password.</p>
			  </div>
			  <?php
			  if($usecaptcha == true)
				  echo "<div style='display: block;text-align: center;text-align: -webkit-center;'><div class='g-recaptcha' id='googlechap' data-sitekey='$captchapublickey;'></div></div>";
			  ?>
			  <input type='hidden' name='passkey' value='<?= $secret?>'>
			  <center><button type='submit' class='btn btn-success'>Register</button>
<?			if(!empty($dl['1']))
			  echo "<div class='btn btn-info' style='margin-left:5px;' data-toggle='modal' data-target='#myModal'><a style='color:white !important;font-weight:700;text-decoration:none'><i class='fa fa-cloud-download'></i> Download</a></div>";?>
			</center>
			</form>
		</div>
<?
 if(!empty($dl['1']))
{
	echo '
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><center>Download Client of '.$title.'</h4>
			  </div>
			  <div class="modal-body">
			  <center><h2>Download Servers<h2></center><hr><hr>
			  <h3>';
			  
if(!empty($dl['1']))
echo "Download : <a href='" . $dl['1'] ."'> ".$dl['name1']." <i class='fa fa-cloud-download'></i> </a><hr/>";
if(!empty($dl['2']))
echo "Download : <a href='" . $dl['2'] ."'> ".$dl['name2']." <i class='fa fa-cloud-download'></i> </a><hr/>";

echo '
			</h3>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			  </div>
			</div>
		  </div>
		</div>';
}?>
	</body>
</html>