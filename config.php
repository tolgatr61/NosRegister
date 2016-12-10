<?php
////////////////////START CONFIG THERE//////////////////
$dbhostname = ""; //MsSQl Server Addres
$db = array(
    "Database" => "", // Database Name
    "Uid" => "", //Database User
    "PWD" => "" // Database Password;
);
$hosturl = ""; //Your host patch eg http://yourdomain.com (without last slash " / ") ----- MUST BE STTED!
$title = ""; //site title  ----- MUST BE SETTED!
$norplaymail = ""; // noreplay mail eg "noreplay@yourdomain.com"
///////////////////////////////////////////
////Do you want to display ToS ?       ////
////True = yes | False = no            ////
///////////////////////////////////////////
$displaytos = false;
$toslink = ""; //link to your ToS page eg "//mywebsite.com/tos.html" (please use // instead of http or https !!!)
$pplink = ""; //link to your Privacy Policy page eg "//mywebsite.com/pp.html" (please use // instead of http or https !!!)
///////////////////////////////////////////
////Do you want to use Google CAPTCHA ? ///
////true = yes  | false = no            ///
///////////////////////////////////////////
$usecaptcha = false; //if you want to use google captcha you can get secret key and public key there https://www.google.com/recaptcha/admin
$captchapublickey = ""; //If yes , put your PUBLIC key there  (site key its called on google page's)
$captchasecret = ""; //If yes, put your SECRET key there
///////////////////////////////////////////
$dl['name1'] = ""; //Set Download Name for 1st Link	
$dl['1'] = ""; //Download link 1	OPTIONAL
$dl['name2'] = ""; //et Download Name for 2nd Link	
$dl['2'] = ""; //Download link 2	OPTIONAL
///////////////////////////////END///////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////DONT TUCH BELOW LINE///////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////
$mssql = sqlsrv_connect($dbhostname, $db);
if(!$mssql)
    die('Something went wrong while connecting to MSSQL');
if(empty($dbhostname))
die("<center><h1 style='color:tomato'>Server its not configurate. Please check config.php <br/> Configrate your database please!</h1></center>");
if(empty($title))
die("<center><h1 style='color:tomato'>Configuration incomplete. check config.php <br/> check title!!!</h1></center>");
if(empty($hosturl))
die("<center><h1 style='color:tomato'>Configuration incomplete. check config.php <br/> Check host url </h1></center>");
if($usecaptcha == true && empty($captchapublickey) && empty($captchasecret))
	die("<center><h1 style='color:tomato'>Configuration incomplete. check config.php <br/> Check Google Captcha </h1></center>");
if($displaytos == true && empty($toslink) && empty($pplink))
	die("<center><h1 style='color:tomato'>Configuration incomplete. check config.php <br/> Check ToS or Privacy Policy</h1></center>");
else {
	require_once('recaptcha/autoload.php');
	$recaptcha = new \ReCaptcha\ReCaptcha($captchasecret);
}
function thisword($word)
{
	$badword = array("drop", "insert", "update", "delete", "alter", "index", "truncate", "'", '"');
	$badreplace = array("***", "***", "****", "***", "****", "***", "*****", "*", "*");
	$clean = str_replace($badword,$badreplace,$word);
	return $clean;
}
function cleanthis($data)
{
	$iclean = filter_var($data, FILTER_SANITIZE_STRING);
	$iclean = thisword($iclean);
	$iclean = htmlentities($iclean, ENT_QUOTES);
	return $iclean;
}
function registermail($email, $mailtoken, $user)
{
	$to = $email;
	$subject = "Confirm your account on ".$title;
	$message = "
<html>
<head>
  <title>Confrim Your Accont NOW</title>
</head>
<body>
Hello, $user !
Welcome to our server $title we need to check if this is really your email address.<br/>
Please fallow below link to activate your accont.<br/>
$hosturl/activeacc.php?key=$mailtoken <br/>
If above link don't work you can go manually at : $hosturl/activeacc.php and enter below key<br/>
<b>$mailtoken</b>
<br/>
Your Staff <br/>
$title<br/>
</body>
</html>";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "To: $user <$email>" . "\r\n";
	$headers .= "From: $title <$norplaymail>" . "\r\n";
	mail($to, $subject, $message, $headers);
}
?>