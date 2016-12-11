<?php
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
	global $norplaymail, $title, $hosturl;
	$to = $email;
	$subject = "Confirm your account on ".$title;
	$message = '
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="http://fonts.googleapis.com/css?family=Raleway:400,600" rel="stylesheet" type="text/css">
  <style type="text/css">
    
    html{
        width: 100%; 
    }

	body{
        width: 100%;  
        margin:0; 
        padding:0; 
        -webkit-font-smoothing: antialiased; 
        mso-padding-alt: 0px 0px 0px 0px;
        background: #ffffff;
    }
    
    p,h1,h2,h3,h4{
        margin-top:0;
		margin-bottom:0;
		padding-top:0;
		padding-bottom:0;
    }
    
    table{
        font-size: 14px;
        border: 0;
    }

    img{
    	border: none!important;
    }

  </style>
</head>
<body style="margin: 0; padding: 0;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#424242" style="height:450px;">
	  <tr>
	   <td>
		   	<table width="600" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
		   		<tbody>
		   			<tr>
		   				<td height="169"></td>
		   			</tr>
		   			<tr>
		   				<td style="text-align:center; color: #fff; font-family: \'Raleway\', arial; font-weight:600; font-size: 36px; text-transform:uppercase; letter-spacing:3px;">'.$title.'</td>
		   			</tr>
		   			<tr>
		   				<td height="133"></td>
		   			</tr>
		   		</tbody>
		   	</table>
	   </td>
	  </tr>
	 </table>
	<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#212121" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
		<tbody>
			<tr>
				<td>
					<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
						<tbody>
							<tr>
								<td width="100%" height="100"></td>
							</tr>
							<tr>
								<td width="100%" height="20"></td>
							</tr>
							<tr>
								<td style="color: whitesmoke; font-family: \'Raleway\', arial; font-size: 18px; line-height:28px;">';
	$message .= "									Hello $user,<br/>
									Welcome to our server $title we need to check if this is really your email address.<br/>
									Please click below button to activate your accont.<br/>
									<div align='center'><a href=\"$hosturl/activeacc.php?key=$mailtoken\" onMouseOver=\"this.style.color='#03a9f4'\" onMouseOut=\"this.style.color='#f9823a'\" style='color: #f9823a;font-family: 'Raleway', arial;font-size: 18px;line-height: 28px;text-decoration: none;padding: 3px 5px;border: 2px dashed'>Activate account</a><br/></div>
									If above button don't work you can go manually at :<br/> $hosturl/activeacc.php <br/> and enter below key<br/>
									<b>$mailtoken</b><br/>
									Your Staff, $title<br/>";
	$message .= '								</td>
							</tr>
							<tr>
								<td width="100%" height="100"></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<table width="100%" bgcolor="#f9823a" cellpadding="0" border="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
		<tbody>
			<tr>
				<td>
					<table width="600" align="center" cellpadding="0" border="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
						<tbody>
							<tr>
								<td width="100%" height="40px"></td>
							</tr>
							<tr>
								<td>
									<table  align="left" cellpadding="0" border="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
										<tbody>
											<tr>
												<td>
													<table cellpadding="0" border="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
														<tr>
															<td width="100%" height="16"></td>
														</tr>
														<tr>
															<td style="color: #fff; font-family: \'Raleway\'; font-size: 12px;">© All rights reserved '.$title.'</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td width="100%" height="40px"></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>

</body>
</html>';
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "To: $user <$email>" . "\r\n";
	$headers .= "From: $title <$norplaymail>" . "\r\n";
	mail($to, $subject, $message, $headers);
}
?>