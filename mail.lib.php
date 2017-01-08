<?php
require_once("config.php");
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

function notifymail($email, $ip, $token)
{
	global $norplaymail, $title, $hosturl;
	$to = $email;
  $data = date("Y-M-d H:i");
	$subject = "Your account has been accessed ".$title;
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
		   				<td style="text-align:center; color: #fff; font-family: \'Raleway\', arial; font-weight:600; font-size: 36px; text-transform:uppercase; letter-spacing:3px;">Your account has been accessed!</td>
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
	$message .= "Hello dear user,<br/>
									Your security is very important to us. We noticed that your account was recently accessed,<br/>
                  IP -> $ip <br/>
                  Data -> $data <br/>
                  Token -> $token <br/>
									If this was you, you can ignore this alert. If you suspect any suspicious activity on your account, please change your password.<br/>
                  If you have any questions or concerns, don't hesitate to get in touch.<br/>
                  Best,<br/>
                  $title Team";
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
	$headers .= "To: $email" . "\r\n";
	$headers .= "From: $title <$norplaymail>" . "\r\n";
	mail($to, $subject, $message, $headers);
}

function notifycpass($email, $ip, $token)
{
	global $norplaymail, $title;
	$to = $email;
  $data = date("Y-M-d H:i");
	$subject = "Your password has been changed ".$title;
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
		   				<td style="text-align:center; color: #fff; font-family: \'Raleway\', arial; font-weight:600; font-size: 36px; text-transform:uppercase; letter-spacing:3px;">Your account has been accessed!</td>
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
	$message .= "Hello dear user,<br/>
									Your security is very important to us. We noticed that your password was recently changed,<br/>
                  IP -> $ip <br/>
                  Data -> $data <br/>
                  Token -> $token <br/>
									If this was you, you can ignore this alert. If you suspect any suspicious activity on your account, please change your get in touch as soon as possible.<br/>
                  If you have any questions or concerns, don't hesitate to get in touch.<br/>
                  Best,<br/>
                  $title Team";
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
	$headers .= "To: $email" . "\r\n";
	$headers .= "From: $title <$norplaymail>" . "\r\n";
	mail($to, $subject, $message, $headers);
}

function forgotmail($email, $mailtoken)
{
	global $norplaymail, $title, $hosturl;
	$to = $email;
	$subject = "Do you forgot password on ".$title;
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
	$message .= "									Hello dear user,<br/>
									You or someone else ask for your password if you made this request,<br/>
									please click below button to forgot your accont password.<br/>
									<div align='center'><a href=\"$hosturl/forgot.php?key=$mailtoken&status=enterkey\" style='color: #f9823a;font-family: 'Raleway', arial;font-size: 18px;line-height: 28px;text-decoration: none;padding: 3px 5px;border: 2px dashed'>Forgot Password!</a><br/></div>
									If above button don't work you can go manually at :<br/> $hosturl/forgot.php?status=enterkey <br/> and enter below key<br/>
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
	$headers .= "To: $email" . "\r\n";
	$headers .= "From: $title <$norplaymail>" . "\r\n";
	mail($to, $subject, $message, $headers);
}
?>