<?php
require_once("config.php");
$data = $_POST['check'];
function check($hostip, $port, $svname) 
{
        if (!$x = @fsockopen($hostip, $port, $errno, $errstr, 5))
        { 
           echo "<j style='color:red'>$svname Offline </j><br/>";
        } 
        else 
        { 
            echo "<j style='color:green'>$svname Online </j><br/>";
            if ($x) 
            { 
                @fclose($x);
            } 
        }   
}
if(empty($host))
{
	die("Disabled by Administrator.");
}
elseif($data == "true")
{
	$result = check($host, $mysql, "MySQL Server");
	$result .= check($host, $login, "Login Server");
	$result .= check($host, $world, "World Server");
	die($result);
}
else
{
	die("Dont try to crack this, someting will be dangerousus for you.:)");
}
?>