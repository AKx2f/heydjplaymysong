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
<title>Hey DJ Play My Song - Reset password</title>
</head>

<body>
<div data-role="header" data-theme="b">
	<a href="/" data-icon="home">Home</a>
	<h1>Reset password</h1>
</div>
<form action="#" method='post'>
	<div data-role="controlgroup">
	<input type="text" id="joyemail" name="email" placeholder="email" data-theme="b">
	<input type="password" id="joycurrent" name="password" placeholder="temporary password" data-theme="b"/>
	<input type="password" id="joynew" name="password2" placeholder="new password (between 6-12)" data-theme="b"/>
	<input type="password" id="joynew2" name="password3" placeholder="new password (required) (between 6-12)" data-theme="b"/>
	<input type="submit" name="resetp" value="Submit" data-theme="b"/>
	<?php
	/* error_reporting(E_ALL); */
	/* ini_set('display_errors', '1'); */
	include('db.php');
	/* var_dump($); */
	/* RESET RESET RESET RESET RESET RESET RESET RESET RESET RESET RESET RESET RESET RESET */
	if(isset($_POST['resetp'])){
		/* email and password sent from form */
		$email = $_POST['email']; 
		$password = $_POST['password'];
		$password2 = $_POST['password2'];
		$password3 = $_POST['password3'];
		$email = stripslashes($email);
		$password = stripslashes($password);
		$password2 = stripslashes($password2);
		$password3 = stripslashes($password3);
		/* Check email */
		$emailchkq = "CALL emaildupcheck (?)";
		$stmt = $con->prepare($emailchkq);
		$stmt->bind_param('s', $email);
		$stmt->execute();
		$rs = $stmt->fetch();
		$stmt->close();
		if ($rs){
			/* Clone password check */
			if ($password2 == $password3){
				/* Temporary password check	*/			
				$tmpasswordchk = "CALL login (?, ?)";
				$stmt = $con->prepare($tmpasswordchk);
				$stmt->bind_param('ss', $email, $password);
				$stmt->execute();
				$rs = $stmt->fetch();
				$stmt->close();
				if ($rs){
					/* Password length check */
					if (strlen ($password3)<13 && strlen($password3)>5){
							/* New password */
							$cost = 12;
							$salt = sprintf("$2y$07$3xdmUfn39Jd.l2.8dAxl", $cost) . $salt;
							$hash = crypt($password3, $salt);
							$rstpasswordq = "CALL resetpassword('$email', '$hash')";
							$dsn->exec($rstpasswordq);
							/* Email notification */
							$to = $email;
							$subject = "Password change -- Hey DJ Play My Song ";
							$message = "Password change.  If you believe this wasn't done by you, immediately email support@heydjplaymysong.com";
							$from = "info@heydjplaymysong.com";
							$headers = "From:" . $from;
							mail($to,$subject,$message,$headers);
							echo "<script type='text/javascript'>alert('Your password has been reset.  You may now login.'); window.location.href = 'dashboard.php';</script>";				
					}
					else{
						echo "<script type='text/javascript'>alert('Your password must be between 6 to 12 characters long.');</script>";
					}
				}
				else{
					echo "<script type='text/javascript'>alert('Invalid email or password.  Try again or create an account.');</script>";
				}
			}
			else{
				echo "<script type='text/javascript'>alert('Your password do not match.  Try again.2');</script>";		
			}
		}
		else{
			echo "<script type='text/javascript'>alert('Invalid email or password.  Try again or create an account.');</script>";
		}
	}
	?>
	</div>
</form>
<div data-role="footer" data-theme="b" data-position="fixed"> 
	<h4>Reset password</h4> 
</div>
<ol id="joyRideTipContent">
	<li data-text="Next" data-id="joyemail" data-options="tipLocation:bottom;">
		<h4>Email</h4>
		<p>Enter your account's email address.</p>
	</li>
	<li data-text="Next" data-id="joycurrent" data-options="tipLocation:bottom;">
		<h4>Temporary password</h4>
		<p>Enter your temporary password.</p>
	</li>
	<li data-text="Next" data-id="joynew" data-options="tipLocation:bottom;">
		<h4>New password</h4>
		<p>Your password must be between 6-12 characters long.</p>
	</li>
	<li data-text="Ok" data-id="joynew2" data-options="tipLocation:top;">
		<h4>Required</h4>
		<p>Enter your new password again and hit submit below.</p>
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