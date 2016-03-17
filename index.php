<?php
require_once('config.php');
if(!isset($_COOKIE['passkey']))
{
	setcookie("passkey", "", time()-3600); //adio my friend
}
$secret = rand(1, 100) . rand(1, 100) . rand(1, 100) . rand(1, 100) . rand(1, 100) . rand(1, 100) . rand(1, 100);
$secret = hash("sha512", $secret);
setcookie("passkey", $secret);
///////////////DONT TUCH!////////////////////
$headers = apache_request_headers();
if(isset($headers['If-Modified-Since'])) {
  if(strtotime($headers['If-Modified-Since']) < time() - 43200) {
    header('HTTP/1.1 304 Not Modified');
	 Header("Cache-Control: private");
    exit;
  }
}
///////////////DONT TUCH!////////////////////
//require_once();
$ip = $_SERVER['REMOTE_ADDR'];
$reg = @$_GET['reg'];
if($reg == "success")
	echo '<div id="alert" class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Account creeated success fully</div>';
if($reg == "faildup")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Account name already exist. Please choose differit name</div>';
if($reg == "failpass")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Password must be same.</div>';
if($reg == "elimit")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Your password or username are too long.</div>';
?>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Cache-control" content="private">
		<script src="<?php echo $hosturl;?>/js/jquery.min.js"></script>
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
		<script type="text/javascript">
		$(document).ready(function() {
					$("#server-result").html('<i class="fa-li fa fa-spinner fa-spin"></i>');
					$.post('server_check.php', {'check':'true'}, function(data) {
					  $("#server-result").html(data);
					});
		});
		</script>
		<script src="<?php echo $hosturl;?>/js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo $hosturl;?>/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $hosturl;?>/css/style.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $hosturl;?>/css/font-awesome.min.css">
	</head>
	<body>
		<div id='cont'>
			<form action='register.php' method='POST'>
			  <div class='form-group'>
				<label for='user'>Username</label>
				<input type='text' class='form-control' id='user' pattern=".{6,30}" placeholder='Username' name='user' required><span style="float:right;" id="user-result"></span>
			  </div>
<!--
			  <div class='form-group'>
				<label for='Email'>Email</label>
				<input type='email' class='form-control' id='exampleInputEmail1' placeholder='Email' name='email' required>
			  </div>
-->
			  <div class='form-group'>
				<label for='Password'>Password</label>
				<input type='password' class='form-control' pattern=".{6,30}" placeholder='Password' name='pass' required>
			  </div>
			  <div class='form-group'>
				<label for='Password1'>Repeat Password</label>
				<input type='password' class='form-control' pattern=".{6,30}" placeholder='Repeat Password' name='c_pass' required>
			  </div>
			  <input type='hidden' name='passkey' value='<?php echo $secret;?>'>
			  <input type='hidden' name='ip' value='<?php echo $ip;?>'>
			  <center><button type='submit' class='btn btn-primary'>Register</button><div class='btn btn-info' style='margin-left:5px;' data-toggle="modal" data-target="#myModal"><a style='color:white !important;font-weight:700;text-decoration:none'> <i class='fa fa-cloud-download'></i> Download</a></div></center>
			</form>
			<hr>
			<center><h3 style="">Server Status</h3></center>
			<center><span id="server-result"></span></center>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><center>Download Client of <?php echo $title;?></h4>
			  </div>
			  <div class="modal-body">
			  <center><h2>Download Servers<h2></center><hr><hr>
			  <h3>
<?php
/////////////////////DONT TUCH///////////////////////////////
if(!empty($dl['1']))
echo "Download : <a href='" . $dl['1'] ."'> Server I <i class='fa fa-cloud-download'></i> </a><hr/>";
if(!empty($dl['2']))
echo "Download : <a href='" . $dl['2'] ."'> Server II <i class='fa fa-cloud-download'></i> </a><hr/>";
if(!empty($dl['3']))
echo "Download : <a href='" . $dl['3'] ."'> Server III <i class='fa fa-cloud-download'></i> </a><hr/>";
if(!empty($dl['4']))
echo "Download : <a href='" . $dl['4'] ."'> Server IV <i class='fa fa-cloud-download'></i> </a><hr/>";
if(!empty($dl['5']))
echo "Download : <a href='" . $dl['5'] ."'> Server V <i class='fa fa-cloud-download'></i> </a><br/>";
//////////////////////////////////////////////////////////////
?>
			</h3>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			  </div>
			</div>
		  </div>
		</div>
	</body>
</html>