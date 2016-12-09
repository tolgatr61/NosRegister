<?php

$dbhostname = ""; //MsSQl Server Addres
$db = array(
    "Database" => "", // Database Name
    "Uid" => "", //Database User
    "PWD" => "" // Database Password;
);
$hosturl = ""; //Your host patch eg http://yourdomain.com (without last slash " / ") ----- MUST BE STTED!
$title = ""; //site title  ----- MUST BE SETTED!
///////////////////////////////////////////
////Do you want to use Google CAPTCHA ? ///
////true = yes  | false = no            ///
///////////////////////////////////////////
$usecaptcha = true; //if you want to use google captcha you can get secret key and public key there https://www.google.com/recaptcha/admin
$captchapublickey = ""; //If yes , put your PUBLIC key there  (site key its called on google page's)
$captchasecret = ""; //If yes, put your SECRET key there
///////////////////////////////////////////
$dl['name1'] = ""; //Set Download Name for 1st Link	
$dl['1'] = ""; //Download link 1	OPTIONAL
$dl['name2'] = ""; //et Download Name for 2nd Link	
$dl['2'] = ""; //Download link 2	OPTIONAL
///////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////DONT TUCH BELOW LINE///////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////
$mssql = sqlsrv_connect($dbhostname, $db);
if(!$mssql)
    die('Something went wrong while connecting to MSSQL');
if(empty($dbhostname))
die("<center><h1 style='color:tomato'>Server its not configurate. Please check config.php</h1></center>");
if(empty($title))
die("<center><h1 style='color:tomato'>Configuration incomplete. check config.php</h1></center>");
if(empty($hosturl))
die("<center><h1 style='color:tomato'>Configuration incomplete. check config.php</h1></center>");
if($usecaptcha == true && empty($captchapublickey) && empty($captchasecret))
	die("<center><h1 style='color:tomato'>Configuration incomplete. check config.php</h1></center>");
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
?>