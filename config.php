<?php
$db['host'] = ""; //Database host  ----- MUST BE SETTED!
$db['name'] = ""; //Database name  ----- MUST BE SETTED!
$db['user'] = ""; //Database user  ----- MUST BE SETTED!
$db['pass'] = ""; //Database password  //MUST BE SETTED!
$hosturl = ""; //Your host patch eg http://yourdomain.com (without last slash " / ") ----- MUST BE STTED!
$title = ""; //site title  ----- MUST BE SETTED!
///////////////////////////////////////////////////////////////////////////////////////////////////////////
$dl['1'] = ""; //Download link 1	OPTIONAL
$dl['2'] = ""; //Download link 2	OPTIONAL
$dl['3'] = ""; //Download link 3	OPTIONAL
$dl['4'] = ""; //Download link 4	OPTIONAL
$dl['5'] = ""; //Download link 5	OPTIONAL
///////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////EDIT BELOW LINE WITH YOUR INFOMRATION// IF YOU DONT WANT SERVER STATUS JUST LEAVE BLANK////
///////////////////////////////////////////////////////////////////////////////////////////////////////////
$mysql = ""; //MySQL PORT default 3306
$login = ""; //Login Port (Check your config.)
$world = ""; //World Port (Check your config.)
$host = ""; //HostIP for your server Hamachi or localhost or DNS.
///////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////DONT TUCH BELOW LINE///////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql = new mysqli($db["host"], $db["user"], $db["pass"], $db["name"]);
if(empty($db["host"]))
die("<center><h1 style='color:tomato'>Server its not configurate. Please check config.php</h1></center>");
if(empty($db["user"]))
die("<center><h1 style='color:tomato'>Server its not configurate. Please check config.php</h1></center>");
if(empty($db["pass"]))
die("<center><h1 style='color:tomato'>Server its not configurate. Please check config.php</h1></center>");
if(empty($db["name"]))
die("<center><h1 style='color:tomato'>Server its not configurate. Please check config.php</h1></center>");
if(empty($title))
die("<center><h1 style='color:tomato'>Configuration incomplete. check config.php</h1></center>");
if(empty($hosturl))
die("<center><h1 style='color:tomato'>Configuration incomplete. check config.php</h1></center>");
?>