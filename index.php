<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=320, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/main.css"> 
<link rel="stylesheet" href="//code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css"/>
<script src="//code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/modernizr.mq.js"></script>
<script type="text/javascript" src="js/jquery.joyride-2.1.js"></script>
<title>Hey DJ Play My Song</title>
</head>
<body>
<?php
/* error_reporting(E_ALL); */
/* ini_set('display_errors', '1'); */
include('db.php');
$lifetime = 60 * 60;
session_set_cookie_params($lifetime, '/');
session_start();
$useridx = null;
$useridx = $_SESSION['userid'];
/* var_dump($); */
if(!isset($_SESSION['userid']) && empty($_SESSION['userid'])) {
	$link = '/';
}
else{
	$link = 'dashboard.php';
}
?>
<div data-role="header" data-theme="b">
	<h1>Hey DJ Play My Song</h1>
	<?php
	if(isset($_SESSION['userid']) && !empty($_SESSION['userid'])) {
		echo "<button onclick=\"window.location='logout.php'\" data-icon=\"delete\" data-theme=\"b\" class=\"ui-btn-right\">Logout</button>";
		$dashlog = '<a href="dashboard.php" data-role="button" data-theme="e" data-icon="forward">Dashboard</a>';
	}
	else{
		$dashlog = '<a href="#popupLogin" id="joylogin" data-rel="popup" data-position-to="window" data-role="button" data-icon="forward" data-theme="b" data-transition="pop">Sign In</a>';
		$createlog = '<a href="#popupCreate" id="joycreate" data-rel="popup" data-position-to="window" data-role="button" data-icon="check" data-theme="b" data-transition="pop">Create an account</a>';
		$forgotlog = "<button onclick=\"window.location='forgotpassword.php'\" data-icon=\"refresh\" data-theme=\"b\">Forgot password</button>";
	}
	?>
</div>
<form id="searchform" action="#" method='post'>
	<div data-role="controlgroup">
		<input type="text" id="joyplaylist" name="playlistname" placeholder="playlist name" data-theme="b" class="required"/>
		<input type="submit" name="musica" class="joyindex" value="Search" data-theme="b" data-icon="search"/>
		<?php
		/* MUSIC MUSIC MUSIC MUSIC MUSIC MUSIC MUSIC MUSIC MUSIC MUSIC MUSIC MUSIC MUSIC MUSIC */
		if(isset($_POST['musica'])){
			$playlistname = $_POST['playlistname'];
			$playlistname = stripslashes($playlistname);
			/* Playlist check and collect */
			$getplaylistq = "CALL getplaylistname ('$playlistname')";
			$stmt = $dsn->prepare($getplaylistq);
			$stmt->execute();
			$rs2 = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($rs2){
				$_SESSION['playlistname'] = $playlistname;
				echo "<script type='text/javascript'>window.location.href = 'music.php';</script>";
			}
			else{
				echo "<script type='text/javascript'>alert('Incorrect playlist name.  Try again.');</script>";
			}
		}
		?>
	</div>
</form>
<!--Login popup-->
<div data-role="controlgroup">
	<?php echo $dashlog; ?>
	<div data-role="popup" id="popupLogin" data-theme="b" class="ui-corner-all">
		<form action="#" method='post'>
			<div style="padding:10px 20px;">
				<h3>Please sign in</h3>
				<input type="text" name="email" placeholder="email" data-theme="b" class="required email"/>
				<input type="password" name="password" placeholder="password" data-theme="b" class="required"/>
				<input type="submit" name="signin" value="Sign in" data-theme="b" data-icon="check"/>
				<input type="submit" data-rel="back" value="Cancel" data-theme="b" data-icon="delete"/>
				<?php
					/* LOGIN LOGIN LOGIN LOGIN LOGIN LOGIN LOGIN LOGIN LOGIN LOGIN LOGIN LOGIN LOGIN LOGIN */
					if(isset($_POST['signin'])){
						/* email and password sent from form */
						$email = $_POST['email']; 
						$password = $_POST['password'];
						$email = stripslashes($email);
						$password = stripslashes($password);
						if ((!empty($email)) || (!empty($password))){
							/* Password check */
							$cost = 12;
							$salt = sprintf("$2y$07$3xdmUfn39Jd.l2.8dAxl", $cost) . $salt;
							$hash = crypt($password, $salt);
							$loginq = "CALL login ('$email', '$hash')";
							$stmt = $dsn->prepare($loginq);
							$stmt->execute();
							$rs = $stmt->fetch(PDO::FETCH_ASSOC);
							if ($rs){
								$_SESSION['userid'] = $email;
								echo "<script type='text/javascript'>window.location.href = 'dashboard.php';</script>";
							}
							else{
								echo "<script type='text/javascript'>alert('Invalid entry.  If you believe you have an account, use Forgot password.');</script>";
							}
						}
						else{
							echo "<script type='text/javascript'>alert('Invalid entry.  If you believe you have an account, use Forgot password.');</script>";
						}
					}
				?>
			</div>
		</form>
	</div>
	<!--Create popup-->
	<?php echo $createlog; ?>
	<div data-role="popup" id="popupCreate" data-theme="b" class="ui-corner-all">
		<form action="#" method='post'>
			<div style="padding:10px 20px;">
				<h3>Please enter an email and password</h3>
				<input type="text" name="cemail" placeholder="email" data-theme="b"/>
				<input type="password" name="cpassword" placeholder="password (between 6-12)" data-theme="b"/>
				<input type="submit" name="create" value="Submit" data-theme="b" data-icon="check"/>
				<input type="submit" data-rel="back" value="Cancel" data-theme="b" data-icon="delete"/>
				<?php
					/* CREATE CREATE CREATE CREATE CREATE CREATE CREATE CREATE CREATE CREATE CREATE CREATE */
					if(isset($_POST['create'])){
						/* email and password sent from form */
						$email = $_POST['cemail']; 
						$password = $_POST['cpassword'];
						$email = stripslashes($email);
						$password = stripslashes($password);
						/* EMAIL VALIDATION */
						if (filter_var($email, FILTER_VALIDATE_EMAIL)){
							/* Check email */
							$emailchkq = "CALL emaildupcheck (?)";
							$stmt = $con->prepare($emailchkq);
							$stmt->bind_param('s', $email);
							$stmt->execute();
							$rs = $stmt->fetch();
							$stmt->close();
							if (!$rs){
								/* Password length check */
								if (strlen($password)<13 && strlen($password)>5){
									$s = '0';
									/* Encryption */
									$cost = 12;
									$salt = sprintf("$2y$07$3xdmUfn39Jd.l2.8dAxl", $cost) . $salt;
									$hash = crypt($password, $salt);
									/* Activation code */
									$activation = sprintf("%06x", mt_rand(0, 0xffffff));
									try {
										$createq = "CALL createaccount('$email', '$hash', '$activation', '$s')";
										$rs = $dsn->exec($createq);
										/* Email notification */
										$to = $email;
										$subject = "You've created an account with Hey DJ Play My Song - Please activate account to use all the features.";
										$message = "Please verify your account by clicking on this link: http://heydjplaymysong.com/qactivate.php?confirm=$activation or login and use $activation to activate your account.  Click this link: http://www.heydjplaymysong.com/English_Instruction_Manual.pdf to download the English Instruction Manual and this link: http://www.heydjplaymysong.com/HDJPMS_Import_Template_1.0.csv for the upload template.";
										$from = "info@heydjplaymysong.com";
										$headers = "From:" . $from;
										mail($to,$subject,$message,$headers);
										echo "<script type='text/javascript'>alert('Your account has been created and emails to you.  Click on the link provided in the email to activate your account and use all the features.'); window.location.href = 'dashboard.php';</script>";
										$lifetime = 60 * 60;
										session_set_cookie_params($lifetime, '/');
										session_start();
										$_SESSION['userid'] = $email;
									}catch (PDOException $e){
										$error_message = $e->getmessage();
										echo "<p>Error: $error_message </p>";
										exit();
									}
								}
								else{
									echo "<script type='text/javascript'>alert('Your password must be between 6 to 12 characters long.');</script>";
								}
							}
							else{
								echo "<script type='text/javascript'>alert('Invalid entry.  If you believe you have an account, use Forgot password.');</script>";
							}
						}
						else{
							echo "<script type='text/javascript'>alert('Invalid entry.  If you believe you have an account, use Forgot password.');</script>";
						}
					}
				?>
			</div>
		</form>
	</div>
<?php echo $forgotlog; ?>
</div>
<button onclick="window.location='about.php'" data-icon="info" data-theme="b">About</button>
<form action="#" method='post' <?php if (!$useridx) echo 'style="display: none;"' ?>>
	<div>
		<input type="submit" name="deletep" value="temp delete all button" data-theme="e"/>
		<?php
		// DELETE DELETE DELETE DELETE DELETE DELETE DELETE DELETE DELETE DELETE DELETE DELETE
		if(isset($_POST['deletep'])){
		$deleteq = "CALL deleteall ('$useridx')";
		$stmt = $dsn->prepare($deleteq);
		$stmt->execute();
		session_destroy();
		echo "<script type='text/javascript'>alert('Everything related to $useridx was deleted.'); window.location.href = '/';</script>";
		}
		?>
	</div>
</form>
<div data-role="footer" data-theme="b" data-position="fixed"> 
	<h4><?php if (!empty($useridx)){print $useridx;}else{print "Welcome";}?></h4>
</div>
<ol id="joyRideTipContent">
	<li data-text="Next" data-id="joyplaylist" data-options="tipLocation:bottom;">
		<h4>Playlist name</h4>
		<p>Here you enter the playlist name and hit search below.</p>
	</li>
	<li data-text="Next" data-id="joylogin" data-options="tipLocation:bottom;">
		<h4>Sign in</h4>
		<p>If you already have an account, sign in here.  Forgot your password?  Hit Forgot password below.</p>
	</li>
	<li data-text="Next" data-id="joycreate" data-options="tipLocation:top;">
		<h4>Create an account</h4>
		<p>Create an account if you wish to make your own playlist.</p>
	</li>
</ol>
 <!--
 * Markup for jQuery Joyride Plugin 2.1
 * www.ZURB.com/playground
 * Copyright 2013, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 -->
<a href="#" data-role="button" class="button" id="openTour" data-icon="info" data-theme="b">Start Tour</a>
<p>1.1</p>
<script type="text/javascript">
	$('#openTour').click(function() {
        $('#joyRideTipContent').joyride({
            autoStart : true,
            cookieMonster: false,
        });
    });
</script>
<?php
$con->close();
$dsn = null;
?>
</body>
</html>