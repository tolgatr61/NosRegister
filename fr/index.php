<?php
require_once('config.php');
if(!isset($_COOKIE['passkey']))
	setcookie("passkey", "", time()-3600);
$secret = rand(1, 100) . rand(1, 100) . rand(1, 100) . rand(1, 100) . rand(1, 100) . rand(1, 100) . rand(1, 100) . $title . rand(1,100);
$secret = hash("sha512", $secret);
setcookie("passkey", $secret);
$headers = apache_request_headers();
if(isset($headers['If-Modified-Since'])) {
  if(strtotime($headers['If-Modified-Since']) < time() - 43200) {
    header('HTTP/1.1 304 Not Modified');
	 Header("Cache-Control: private");
    exit;
  }
}
?>
<html>
	<head>
		<title><?php echo $title;?></title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Cache-control" content="private">
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
		<script src="./js/jquery.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			$("#user").keyup(function (e) {
				$(this).val($(this).val().replace(/\s/g, ''));
				var username = $(this).val();
				if(username.length < 6){$("#user-result").html('');return;}
				if(username.length >= 6){
					$("#user-result").html('<i class="fa-li fa fa-spinner fa-spin"></i>');
					$.post('check.php', {'user':username}, function(data) {
					  $("#user-result").html(data);
					});
				}
			});	
		});
		$(document).ready(function() {
			$("#mail").keyup(function (e) {
				$(this).val($(this).val().replace(/\s/g, ''));
				var username = $(this).val();
				if(username.length < 6){$("#mail-result").html('');return;}
				if(username.length >= 6){
					$("#mail-result").html('<i class="fa-li fa fa-spinner fa-spin"></i>');
					$.post('check.php', {'mail':username}, function(data) {
					  $("#mail-result").html(data);
					});
				}
			});	
		});
		</script>
		<script src="./js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="./css/style.min.css">
		<link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css">
        <?php if($usecaptcha == true)
		echo "<script src='https://www.google.com/recaptcha/api.js?onload=Chap&render=explicit' async defer></script>
    <script>
      var recaptcha1;
      var recaptcha2;
      var Chap = function() {
        recaptcha1 = grecaptcha.render('recaptcha1', {
          'sitekey' : '$captchapublickey',
          'theme' : 'light'
        });
        recaptcha2 = grecaptcha.render('recaptcha2', {
          'sitekey' : '$captchapublickey',
          'theme' : 'light'
        });
      };
    </script>";?>
	</head>
	<body>
<?php
$reg = cleanthis(@$_GET['reg']);
if($reg == "active")
	echo '<div id="alert" class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><b>Success!</b> Ton compte est maintenant actif ! Tu es prêt à jouer !</div>';
if($reg == "success")
	echo '<div id="alert" class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Bienvenue '.cleanthis($_GET['user']).' ! Ton compte est prêt<br/> Telecharge NosTorm maintenant ! </div>';
if($reg == "dels")
	echo '<div id="alert" class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Your account has been be deleted</div>';
if($reg == "sucess")
	echo '<div id="alert" class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Bienvenue '.cleanthis($_GET['user']).' ! Your account was created.';
if($reg == "logout")
	echo '<div id="alert" class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Deconnecté! Deconnection avec succès!</div>';
if($reg == "cofail")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Token de securité manquant ! Veuillez vous reconnecter !</div>';
if($reg == "faildup")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Identifiant déjà pris ! Veuillez en prendre un autre. </div>';
if($reg == "failpass")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Les mots de passe doivent être identiques.</div>';
if($reg == "elimit")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Votre mot de passe ou identifant est trop long.</div>';
if($reg == "gfail")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Veuillez réessayer le Google Captcha.</div>';
if($reg == "maildup")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Email déjà utiliser. Veuillez changer.</div>';
if($reg == "mailfail")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Email non valide. Merci de le modifier </div>';
if($reg == "nokey")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Votre code d activation est pas bon.</div>';
if($reg == "notkey")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Ce n est pas un code.</div>';
if($reg == "gfailkey")
	echo '<div id="alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed! Captcha incorrect! Veuillez réessayer</div>';
?>
		<div id='cont'>
        <?php
            if($login == true)
            {
                echo "<div class='lbox'>
                <a id='logint'> Connexion </a>
            </div>
            <div id='login'>
                <form action='account.php' method='POST'>
                  <div class='form-group'>
                    <label for='user'>Identifiant</label>
                    <input type='text' class='form-control' pattern='.{6,30}' placeholder='Identifiant' name='luser' onfocus='this.removeAttribute(\"readonly\");'  required readonly>
                  </div>
                  <div class='form-group'>
                    <label for='Password'>Mot de passe</label>
                    <input type='password' id='noremember' class='form-control' pattern='.{6,30}' placeholder='Mot de passe' name='lpass' onfocus='this.removeAttribute(\"readonly\");' required readonly>
                  </div>
                  <center>";
                  if($usecaptcha == true)
                      echo "<div style='display: block;text-align: center;text-align: -webkit-center;'><div id='recaptcha1'></div></div>";
                  echo "<button type='submit' class='btn btn-login' name='status' value='login'>Connexion</button></center>
                 </form>
            </div>";
            }?>
            <div id='register'>
                <form action='register.php' method='POST'>
                  <div class='form-group'>
                    <label for='user'>Nom d'utilisateur</label>
                    <input type='text' class='form-control' id='user' pattern=".{6,30}" placeholder="Nom d'utilisateur" name='user' required><span style="float:right;" id="user-result"></span>
                    <p class="help-block">Votre nom d'utilisateur doit être entre 6 et 30 caractères.</p>
                  </div>
                  <div class='form-group'>
                    <label for='Password'>Email</label>
                    <input type='email' id="mail" class='form-control' pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" placeholder='email@x.com' name='email' onfocus="this.removeAttribute('readonly');" required readonly><span style="float:right;" id="mail-result"></span>
                    <p class="help-block">Votre adresse email. Merci de ne pas utilisez Yahoo !</p>
                  </div>
                  <div class='form-group'>
                    <label for='Password'>Mot de passe</label>
                    <input type='password' id="noremember" class='form-control' pattern=".{6,30}" placeholder='Mot de passe' name='pass' onfocus="this.removeAttribute('readonly');" required readonly>
                    <p class="help-block">Le mot de passe doit être entre 6 et 30 caractères. Nous vous recommandons d'utiliser un mot de passe complexe.</p>
                  </div>
                  <div class='form-group'>
                    <label for='Password1'>Repetez votre mot de passe.</label>
                    <input type='password' class='form-control' pattern=".{6,30}" id="noremember" placeholder='Repetez votre mot de passe' name='c_pass' onfocus="this.removeAttribute('readonly');" required readonly>
                    <p class="help-block">Repetez votre mot de passe.</p>
                  </div>
                  <?php
                  if($displaytos == true)
                      echo "
                  <div class='checkbox'>
                    <label>
                      <input type='checkbox' name='tos' value='true' required> I agree to the <a href='$toslink'>Terms of Use</a> and <a href='$pplink'>Privacy Policy</a>.
                    </label>
                  </div>";
                  if($usecaptcha == true)
                      echo "<div style='display: block;text-align: center;text-align: -webkit-center;'><div id='recaptcha2'></div></div>";
                  if($forgot == true)
                      echo "<a href='forgot.php'>I forgot my password!</a>";
                  ?>
                  <input type='hidden' name='passkey' value='<?= $secret?>'>
                  <center><button type='submit' class='btn btn-success'>S'Inscrire</button>
    <?php			if(!empty($dl['1']))
                  echo "<div class='btn btn-info' style='margin-left:5px;' data-toggle='modal' data-target='#myModal'><a style='color:white !important;font-weight:700;text-decoration:none'><i class='fa fa-cloud-download'></i> Telecharger</a></div>";?>
                </center>
				<center><button type='submit' class='btn btn-discord' onclick="location.href='https://discord.gg/6cqzjSe'"; >Discord</button></center>
				<a href="/"><img src="img/en.png" alt="Image" /></a>
				<button type='submit' class='btn btn-donate' onclick="location.href='nosmall.html'"; >Donation</button></center>
                </form>
            </div>
		</div>
<?php
 if(!empty($dl['1'])) {
     echo '
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><center>Placez le client de ' . $title . ' dans votre dossier NosTale.</h4>
			  </div>
			  <div class="modal-body">
			  <center><h2>Liens de téléchargements<h2></center><hr><hr>
			  <h3>';

     if (!empty($dl['1']))
         echo "Telecharger : <a href='" . $dl['1'] . "'> " . $dl['name1'] . " <i class='fa fa-cloud-download'></i> </a><hr/>";
     if (!empty($dl['2']))
         echo "Telecharger : <a href='" . $dl['2'] . "'> " . $dl['name2'] . " <i class='fa fa-cloud-download'></i> </a><hr/>";

     echo '
			</h3>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			  </div>
			</div>
		  </div>
		</div>';
 }
?>
        <script>
       $(document).ready(function () {
    $("#logint").click(function() {
      $("#login").slideToggle(function() {
          $("#register").slideToggle();
      });
    });
})
        </script>
        <?php
        if($footer == true)
          echo '<div id="footer">
              <a href="#" style="text-align:center;"> NosRegister version française développer par Rayshon pour NosTorm &copy; </a>
          </div>';
        ?>
	</body>
</html>