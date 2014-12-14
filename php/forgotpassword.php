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
<title>Hey DJ Play My Song - Forgot password</title>
</head>

<body>
<div data-role="header" data-theme="b">
	<a href="/" data-icon="back">Back</a>
	<h1>Forgot password</h1>
</div>
<form action="#" method='post'>
	<input type="text" name="email" id="joyemail" placeholder="email" data-theme="b" class="required email"/>
	<input type="submit" name="reset" value="Reset" data-theme="b" data-icon="refresh"/>
	<?php
	/* error_reporting(E_ALL); */
	/* ini_set('display_errors', '1'); */
	include('db.php');
	/* var_dump($); */
	/* Collecting the user email */
	$userid = $_SESSION['userid'];
	/* RESET RESET RESET RESET RESET RESET RESET RESET RESET RESET RESET RESET RESET RESET */
		if(isset($_POST['reset'])){
			/* email and password sent from form */
			$email = $_POST['email']; 
			$email = stripslashes($email);
			/* Check email */
			$emailchkq = "CALL emaildupcheck (?)";
			$stmt = $con->prepare($emailchkq);
			$stmt->bind_param('s', $email);
			$stmt->execute();
			$rs = $stmt->fetch();
			$stmt->close();
			if ($rs){
				/* New temp password */
				$tpassword = sprintf("%06x", mt_rand(0, 0xffffff));
				$rstpasswordq = "CALL resetpassword('$email', '$tpassword')";
				$dsn->exec($rstpasswordq);;
				/* Email notification */
				$to = $email;
				$subject = "Password reset -- Hey DJ Play My Song - Please activate account";
				$message = "Your temporary password is $tpassword.  Change it in order to log in: http://heydjplaymysong.com/resetpassword.php";
				$from = "info@heydjplaymysong.com";
				$headers = "From:" . $from;
				mail($to,$subject,$message,$headers);
				echo "<script type='text/javascript'>alert('An email will be sent to a valid email with a temporary password.  Click on the link provided in the email to change your password.'); window.location.href = '/';</script>";
			}
			else{
				echo "<script type='text/javascript'>alert('Invalid entry.  If you believe you have an account and forgot your email, email support@heydjplaymysong.com.');</script>";
			}
		}
	?>
	</div>
</form>
<h4 align="center">Forgot your email?</h4>
<p align="center">email support@heydjplaymysong.com</p>
<div data-role="footer" data-theme="b" data-position="fixed"> 
	<h4>Forgot password</h4> 
</div>
<ol id="joyRideTipContent">
	<li data-text="Next" data-id="joyemail" data-options="tipLocation:bottom;">
		<h4>Email</h4>
		<p>Enter your account's email address and hit the reset button below.</p>
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