<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=320, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" href="//code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css"/>
<script src="//code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<title>Hey DJ Play My Song - Activate account</title>
</head>

<body>
<div data-role="header" data-theme="b">
	<a href="dashboard.php" data-icon="back">Back</a>
	<h1>Activate account</h1>
</div>
	<form action="#" method='post'>
		<input type="text" name="activatenum" placeholder="activation code" data-theme="b"/>
		<input type="submit" name="activate" value="Activate account" data-theme="b" data-icon="check"/>
		<input type="submit" name="resetc" value="Reset passcode" data-theme="b" data-icon="refresh"/>
		<?php
		/* error_reporting(E_ALL); */
		/* ini_set('display_errors', '1'); */
		include('db.php');
		include('session.php');
		/* var_dump($); */
		/* Collecting the user email */
		$email = $_SESSION['userid'];
		/* ACTIVATE ACTIVATE ACTIVATE ACTIVATE ACTIVATE ACTIVATE ACTIVATE ACTIVATE ACTIVATE ACTIVATE */
		if(isset($_POST['activate'])){
			/* email and password sent from form */
			$activationnum = $_POST['activatenum']; 
			$email = stripslashes($email);
			$activationnum = stripslashes($activationnum);
			$activationchkq = "CALL activationcheck (?)";
			$stmt = $con->prepare($activationchkq);
			$stmt->bind_param('s', $activationnum);
			$stmt->execute();
			$rs = $stmt->fetch();
			$stmt->close();
			if ($rs){
				$activateacc = "CALL accountactivation ('$activationnum')";
				$rs = $dsn->exec($activateacc);
				echo "<script type='text/javascript'>alert('Account activated'); window.location.href = 'dashboard.php';</script>";
			}
			else{
				echo "<script type='text/javascript'>alert('Invalid code.  Reset your activate code.');</script>";
			}
		}
		if(isset($_POST['resetc'])){
			/* New activation code */
			$activation = sprintf("%06x", mt_rand(0, 0xffffff));
			$rstactivationq = "CALL resetactivation (?, ?)";
			$stmt = $con->prepare($rstactivationq);
			$stmt->bind_param('ss', $email, $activation);
			$stmt->execute();
			$rs = $stmt->fetch();
			$stmt->close();
			/* Email notification */
			$to = $email;
			$subject = "You've created an account with Hey DJ Play My Song - Please activate account";
			$message = "Please verify your account by clicking on this link: http://heydjplaymysong.com/qactivate.php?confirm=$activation or login and use $activation to activate your account.";
			$from = "info@heydjplaymysong.com";
			$headers = "From:" . $from;
			mail($to,$subject,$message,$headers);
			echo "<script type='text/javascript'>alert('Your activation code has been reset and email to you.  Click on the link provided in the email to activate your account.'); window.location.href = 'index.php';</script>";	
		}
		?>
	</form>
<div data-role="footer" data-theme="b" data-position="fixed"> 
	<h4>Activate account</h4> 
</div>
<?php
$con->close();
$dsn = null;
?>
</body>
</html>