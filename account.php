<?php
require_once('config.php');
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
      $result = sqlsrv_num_rows($restul);
      if($result == 1)
      {
        echo "<h1 style='color:whitesmoke;'>Welcome, $user</h1><hr/>";
        if($lcpw == true)
          echo "<button type='button' class='btn btn-primary btn-lg btn-block' data-toggle='modal' data-target='#changepw'>Change Password</button><br/>";
        if($lce == true)
          echo"<button type='button' class='btn btn-primary btn-lg btn-block' data-toggle='modal' data-target='#changemail'>Change Email</button><br/>";
        echo "<button type='button' class='btn btn-success btn-lg btn-block'>Support</button><br/>
              <button type='button' class='btn btn-danger btn-lg btn-block' onclick='location.href = \"account.php?status=logout\";'>Log Out</button>";
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
      exit(header("Location: index.php"));
      break;
    default:
      $data = $_COOKIE['passtoken'];
      if(strlen($data) == 32)
        exit(header("Location: account.php?status=menu"));
      else
        exit(header("Location: index.php?status=noa"));
      break;
  }
?>
    </div>
<?php
if($lcpw == true && strlen($_COOKIE['passtoken']) == 32)
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
if($lce == true && strlen($_COOKIE['passtoken']) == 32)
echo "                <div class='modal fade' id='changemail' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                  <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                        <h4 class='modal-title' id='myModalLabel'>Change Password</h4>
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