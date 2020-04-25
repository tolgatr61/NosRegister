<?php
$dbhostname = "localhost"; //MsSQl Server Addres
$db = array(
    "Database" => "opennos", // Database Name
    "Uid" => "", //Database User
    "PWD" => "" // Database Password;
);
$hosturl = "http://25.81.119.156:8080"; //Your host patch eg http://yourdomain.com (without last slash " / ") ----- MUST BE SETTED!
$title = "NosTorm"; //site title  ----- MUST BE SETTED!
$norplaymail = "nostorm@gmail.com"; // noreplay mail eg "noreplay@yourdomain.com"
///////////////////////////////////////////
////Do you want to display footer ?    ////
////True = yes | False = no            ////
///////////////////////////////////////////
$footer = true;
///////////////////////////////////////////
////Do you want to display ToS ?       ////
////True = yes | False = no            ////
///////////////////////////////////////////
$displaytos = false;
$toslink = "//25.81.119.156:8080/tos.html"; //link to your ToS page eg "//mywebsite.com/tos.html" (please use // instead of http or https !!!)
$pplink = "//25.81.119.156:8080/pp.html"; //link to your Privacy Policy page eg "//mywebsite.com/pp.html" (please use // instead of http or https !!!)
///////////////////////////////////////////
////Do you want to enable forgot ?      ///
////true = yes  | false = no            ///
///////////////////////////////////////////
$forgot = false;
///////////////////////////////////////////////
////Do you want to send verification mail ? ///
////true = yes  | false = no            	///
///////////////////////////////////////////////
$sendverification = false;
///////////////////////////////////////////
////Do you want to use Google CAPTCHA ? ///
////true = yes  | false = no            ///
///////////////////////////////////////////
$usecaptcha = true; //if you want to use google captcha you can get secret key and public key there https://www.google.com/recaptcha/admin
$captchapublickey = "6LezuMUUAAAAALyJWxweBI_DYMOiRFzEn7UXa3iu"; //If yes , put your PUBLIC key there  (site key its called on google page's)
$captchasecret = "6LezuMUUAAAAAFKMMG83aKAjtDt05keWDSID7s41"; //If yes, put your SECRET key there
///////////////////////////////////////////
$dl['name1'] = "Client nostorm"; //Set Download Name for 1st Link	
$dl['1'] = "nostorm.exe"; //Download link 1
$dl['name2'] = ""; //Download Name for 2nd Link	 OPTIONAL
$dl['2'] = ""; //Download link 2	OPTIONAL
///////////////////////////////////////////
////Do you want to enable login  ?      ///
////true = yes  | false = no            ///
///////////////////////////////////////////
//That mean to allow users to login in ther account and they can change his password, or email, ofc you my config this...
$login = true; //Allow login to site
$notifymail = true; //Send notification to mail for each login, if account are accesed a mail will be send to his address with some information.
$lcpw = false; //Allow change password
$lce = false; //Allow change email
$delc = false; //Allow user to 'delete' his account, Note user account will not be deleted from database will be closed.
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
?>
