<?php
require_once('config.php');
require_once('mail.lib.php');
$status = cleanthis(@$_REQUEST['status']);
?>
<html>
  <head>
    <title><?=$title?></title>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta http-equiv='Cache-control' content='private'>
    <script src='./js/jquery.min.js'></script>
    <script src='./js/bootstrap.min.js'></script>
    <link rel='stylesheet' type='text/css' href='./css/bootstrap.min.css'>
    <link rel='stylesheet' type='text/css' href='./css/style.min.css'>
    <link rel='stylesheet' type='text/css' href='./css/font-awesome.min.css'>
    <script src='https://www.google.com/recaptcha/api.js'></script>
  </head>
  <body>
    <div id='cont'>
<?php
  switch($status) {
    case "login":
      $resp = $recaptcha->verify(@$_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
      if($resp->isSuccess() or $usecaptcha == false)
      {
        $user = cleanthis(@$_POST['luser']);
        $pass = cleanthis(@$_POST['lpass']);
        $pass = hash("SHA512", $pass);
        $params = array($user, $pass);
        $sql = "SELECT * FROM Account WHERE Name = ? AND Password = ?";
        $opts = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $restul = sqlsrv_query($mssql, $sql, $params, $opts);
        $result = sqlsrv_num_rows($restul);
        if($result == 1)
        {
          $ip = $_SERVER['REMOTE_ADDR'];
          $params = array($user, $pass);
          $sql = "SELECT * FROM Account WHERE Name = ? AND Password = ?";
          $opts = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
          $result = sqlsrv_query($mssql, $sql, $params, $opts);
          $obj = sqlsrv_fetch_object($result);
          $email = $obj->Email;
          $valid = $obj->Authority;
          if($valid == -3)
            exit(header("Location: account.php?status=closed"));
          if($valid == -2)
            exit(header("Location: account.php?status=banned"));
          if($valid == -1)
            exit(header("Location: account.php?status=invalid"));
          if($valid == 0 or $valid == 1 or $valid == 2)
          {
            $token = md5(md5(rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).$pass.date("Y-M-S-i").$user.rand(0,9).rand(0,9).rand(0,9).rand(0,9)));
            setcookie("passtoken", $token, time()+3600);
            setcookie("passuser", $user, time()+3600);
            $sql = "UPDATE Account SET VerificationToken = ? WHERE Name = ? AND Password = ?";
            $params = array($token, $user, $pass);
            sqlsrv_query($mssql, $sql, $params);
            if($notifymail == true)
              notifymail($email, $ip, $token);
            exit(header("Location: account.php?status=menu"));
          }
          else
            exit(header("Location: account.php?status=invalid"));
        }
        else
          exit(header("Location: account.php?status=authfail"));
      }
      else
        exit(header("Location: account.php?status=gfail"));
      break;
    case "menu":
      $user = cleanthis(@$_COOKIE['passuser']);
      $token = cleanthis(@$_COOKIE['passtoken']);
      $params = array($user, $token);
      $sql = "SELECT * FROM Account WHERE Name = ? AND VerificationToken = ?";
      $opts = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
      $restul = sqlsrv_query($mssql, $sql, $params, $opts);
      $obj = sqlsrv_fetch_object($restul);
      $email = $obj->Email;
      $rank = $obj->Authority;
      $result = sqlsrv_num_rows($restul);
      if($result == 1)
      {
        echo "<h1 style='color:whitesmoke;'>Bienvenue, $user</h1><hr/>";
        echo '
<button id="infot" class="btn btn-primary btn-lg btn-block">Information du compte</button><br/>
<div class="well well-lg" id="infob" style="display:none;">
Identifiant: '.$user.'<br/>
Email: '.$email.'</br>
Grade: '.rank($rank).'</br>
Caractères: <br/>
'.infos($user).'
</div>
<script>
$("#infot" ).click(function() {
  $( "#infob" ).slideToggle( "slow", function() {
  });
});
</script>';

        if($lcpw == true)
          echo "<button type='button' class='btn btn-primary btn-lg btn-block' data-toggle='modal' data-target='#changepw'>Change Password</button><br/>";
        if($lce == true)
          echo"<button type='button' class='btn btn-primary btn-lg btn-block' data-toggle='modal' data-target='#changemail'>Change Email</button><br/>";
        echo "<button type='button' class='btn btn-success btn-lg btn-block'>Support</button><br/>
              <button type='button' class='btn btn-danger btn-lg btn-block' onclick='location.href = \"account.php?status=logout\";'>Deconnexion</button><br/>";
        if($delc == true)
          echo"<button type='button' class='btn btn-danger btn-lg btn-block' data-toggle='modal' data-target='#delmyacc'>Delete this account</button>";
      }
      else {
        setcookie("passtoken", "", time()-93600);
        setcookie("passuser", "", time()-93600);
        exit(header("Location: account.php?status=cofail"));
      }
      break;
    case "invalid":
      echo "<h1 style='color:whitesmoke;'>Please valid your account before login!</h1>";
      break;
    case "closed":
      echo "<h1 style='color:whitesmoke;'>Your account has been closed. Please contact GameTeam for more information!</h1>";
      break;
    case "banned":
      echo "<h1 style='color:tomato;font-weight:bold;'>Your account has been banned!.<br/> Please contact GameTeam for more information!</h1>";
      break;
    case "psize":
      echo "<h1 style='color:whitesmoke;'>You'r password are too weak, try another one.</h1>";
      break;
    case "authfail":
      echo "<h1 style='color:whitesmoke;text-align:center;text-align:-webkit-center;'>Authentification failed!</h1>";
      break;
    case "gfail":
      echo "<h1 style='color:whitesmoke;'>Sorry but you don't pass captcha test. Are you a robot?!</h1>";
      break;
    case "npass":
      echo "<h1 style='color:whitesmoke;'>Password must be same due security reason!</h1>";
      break;
    case "cofail":
      setcookie("passtoken", "", time()-93600);
      setcookie("passuser", "", time()-93600);
      echo "<h1 style='color:whitesmoke;'>You'r security token are broken!</h1>";
      break;
    case "cpasswrong":
      echo "<h1 style='color:whitesmoke;'>You enter wrong password, try again</h1>";
      break;
    case "psuccess":
      echo "<h1 style='color:whitesmoke;'>You'r password was changed success fully!</h1>";
      setcookie("passtoken", "", time()-93600);
      setcookie("passuser", "", time()-93600);
      break;
    case "cmail":
      echo "<h1 style='color:whitesmoke;'>You'r mail was updated success fully!</h1><br/><a href='account.php' class='btn btn-primary btn-lg btn-block'>Return to menu</a><br/>";
      break;
    case "delc":
      $user = $_COOKIE['passuser'];
      $pass = cleanthis($_POST['pass']);
      $pass = hash("SHA512", $pass);
      $params = array($user, $pass);
      $sql = "SELECT * FROM Account WHERE Name = ? AND Password = ?";
      $opts = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
      $restul = sqlsrv_query($mssql, $sql, $params, $opts);
      $restuls = sqlsrv_num_rows($restul);
      if($restuls == 1)
      {
        $auth = -3;
        $params = array($auth, $user);
        $sql = "UPDATE Account SET Authority = ? WHERE Name = ?";
        sqlsrv_query($mssql, $sql, $params);
        exit(header("Location: index.php?reg=dels"));
      }
      else
        exit(header("Location: account.php?status=logout"));
      break;
    case "changepw":
      $token = cleanthis($_COOKIE['passtoken']);
      $cpass = cleanthis($_POST['cpass']);
      $npass = cleanthis($_POST['npass']);
      $cnpass = cleanthis($_POST['cnpass']);
      if(strlen($npass) <= 5)
        exit(header("Location: account.php?status=psize"));
      $npass = hash("SHA512", $npass);
      $cnpass = hash("SHA512", $cnpass);
      if($npass === $cnpass)
      {
        if(strlen($token) == 32)
        {
          $cpass = hash("SHA512", $cpass);
          $params = array($token, $cpass);
          $sql = "SELECT * FROM Account WHERE VerificationToken = ? AND Password = ?";
          $opts = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
          $restul = sqlsrv_query($mssql, $sql, $params, $opts);
          $obj = sqlsrv_fetch_object($restul);
          $email = $obj->Email;
          $restul = sqlsrv_num_rows($restul);
          if($restul == 1)
          {
            $params = array($npass, $token, $email);
            $sql = "UPDATE Account SET Password = ? WHERE VerificationToken = ? AND Email = ?";
            sqlsrv_query($mssql, $sql, $params);
            if($notifymail == true)
            {
              $ip = $_SERVER['REMOTE_ADDR'];
              notifycpass($email, $ip, $token);
            }
            exit(header("Location: account.php?status=psuccess"));
          }
          else
            exit(header("Location: account.php?status=cpasswrong"));
        }
        else
        {
          setcookie("passtoken", "", time()-93600);
          setcookie("passuser", "", time()-93600);
          exit(header("Location: account.php?status=cofail"));
        }
      }
      else
        exit(header("Location: account.php?status=npass"));
      break;
    case "changemail":
      $pass = hash("SHA512",cleanthis($_POST["cpass"]));
      $nmail = cleanthis($_POST["nmail"]);
      $token = cleanthis($_COOKIE['passtoken']);
      if(strlen($token) == 32)
      {
          $params = array($token, $pass);
          $sql = "SELECT * FROM Account WHERE VerificationToken = ? AND Password = ?";
          $opts = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
          $restul = sqlsrv_query($mssql, $sql, $params, $opts);
          $obj = sqlsrv_fetch_object($restul);
          $cmail = $obj->Email;
          $restul = sqlsrv_num_rows($restul);
          if($restul == 1)
          {
            notifycmail($cmail, $nmail, $token);
            $params = array($nmail, $token, $cmail);
            $sql = "UPDATE Account SET Email = ? WHERE VerificationToken = ? AND Email = ?";
            sqlsrv_query($mssql, $sql, $params);
            notifycsmail($nmail);
            exit(header("Location: account.php?status=cmail"));
          }
          else
            exit(header("Location: account.php?status=cpasswrong"));
      }
      else {
          setcookie("passtoken", "", time()-93600);
          setcookie("passuser", "", time()-93600);
          exit(header("Location: account.php?status=cofail"));
      }
      break;
    case "logout":
      setcookie("passtoken", "", time()-93600);
      setcookie("passuser", "", time()-93600);
      exit(header("Location: index.php?reg=logout"));
      break;
    default:
      $data = $_COOKIE['passtoken'];
      if(strlen($data) == 32)
        exit(header("Location: account.php?status=menu"));
      else
        exit(header("Location: index.php?reg=cofail"));
      break;
  }
?>
    </div>
<?php
if($delc == true && strlen(@$_COOKIE['passtoken']) == 32)
  echo "
  <div class='modal fade' id='delmyacc' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
  <div class='modal-dialog' role='document'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
        <h4 class='modal-title' id='myModalLabel'>Change Password</h4>
      </div>
      <div class='modal-body'>
        <form class='form-horizontal' action='account.php' method='POST'>
          <div class='form-group'>
            <label for='inputEmail3' class='col-sm-2 control-label'>Account</label>
            <div class='col-sm-10'>
              <input class='form-control' type='text' placeholder='$user' readonly>
            </div>
          </div>
          <div class='form-group'>
            <label for='inputEmail3' class='col-sm-2 control-label'>Email</label>
            <div class='col-sm-10'>
              <input class='form-control' type='text' placeholder='$email' readonly>
            </div>
          </div>
          <div class='form-group'>
            <label for='inputEmail3' class='col-sm-2 control-label'>Current Password</label>
            <div class='col-sm-10'>
              <input type='password' class='form-control' placeholder='Confrim Password' name='pass'>
              <p class='help-block'>You'r current password</p>
            </div>
          </div>
          <p style='color:red;font-weight:bold;'>This action is irreversible, you will close your account forever.</p>
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
        <button type='submit' class='btn btn-primary' name='status' value='delc'>Delete my account</button>
      </div>
     </form>
    </div>
  </div>
</div>";
if($lcpw == true && strlen(@$_COOKIE['passtoken']) == 32)
echo "<div class='modal fade' id='changepw' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                  <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                        <h4 class='modal-title' id='myModalLabel'>Change Password</h4>
                      </div>
                      <div class='modal-body'>
                        <form class='form-horizontal' action='account.php' method='POST'>
                          <div class='form-group'>
                            <label for='inputEmail3' class='col-sm-2 control-label'>Current Password</label>
                            <div class='col-sm-10'>
                              <input type='password' class='form-control' placeholder='Current Password' name='cpass'>
                              <p class='help-block'>You'r current password</p>
                            </div>
                          </div>
                          <div class='form-group'>
                            <label for='inputPassword3' class='col-sm-2 control-label'>New Password</label>
                            <div class='col-sm-10'>
                              <input type='password' class='form-control' placeholder='New Password' name='npass'>
                              <p class='help-block'>You'r new password</p>
                            </div>
                          </div>
                          <div class='form-group'>
                            <label for='inputPassword3' class='col-sm-2 control-label'>Confirm New Password</label>
                            <div class='col-sm-10'>
                              <input type='password' class='form-control' placeholder='Confirm New Password' name='cnpass'>
                              <p class='help-block'>Just to be ensure you enter correct password.</p>
                            </div>
                          </div>
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                        <button type='submit' class='btn btn-primary' name='status' value='changepw'>Change my password</button>
                      </div>
                     </form>
                    </div>
                  </div>
                </div>";
if($lce == true && strlen(@$_COOKIE['passtoken']) == 32)
echo "                <div class='modal fade' id='changemail' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                  <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                        <h4 class='modal-title' id='myModalLabel'>Change Email Address</h4>
                      </div>
                      <div class='modal-body'>
                        <form class='form-horizontal' action='account.php' method='POST'>
                          <div class='form-group'>
                            <label for='inputEmail3' class='col-sm-2 control-label'>New Email Address</label>
                            <div class='col-sm-10'>
                              <input type='email' class='form-control' placeholder='New mail' name='nmail'>
                              <p class='help-block'>! You may need to confirm this mail again !</p>
                            </div>
                          </div>
                          <div class='form-group'>
                            <label for='inputPassword3' class='col-sm-2 control-label'>Password</label>
                            <div class='col-sm-10'>
                              <input type='password' class='form-control' placeholder='Password' name='cpass'>
                              <p class='help-block'>You'r current password</p>
                            </div>
                          </div>
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                        <button type='submit' class='btn btn-primary' name='status' value='changemail'>Change my email</button>
                      </div>
                     </form>
                    </div>
                  </div>
                </div>";
?>
  </body>
</html>