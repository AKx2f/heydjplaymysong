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
<title>Hey DJ Play My Song - Dashboard</title>
</head>
<body>
<?php
/* error_reporting(E_ALL); */
/* ini_set('display_errors', '1'); */
include('db.php');
include('session.php');
/* var_dump($); */
/* Collecting the user email */
$userid = $_SESSION['userid'];
/* Status check */
$sstat = '0';
$statuschk = "CALL statuscheck (?, ?)";
$stmt = $con->prepare($statuschk);
$stmt->bind_param('si', $userid, $sstat);
$stmt->execute();
$rs = $stmt->fetch();
$stmt->close();
if ($rs){
	$verified = ' - Account not verified';
}
else{
	$verified = '';
}
?>
<div data-role="header" data-theme="b">
	<button onclick="window.location='/'" data-icon="back" data-theme="b">Back</button>
	<h1>Dashboard</h1>
	<button onclick="window.location='logout.php'" data-icon="delete" data-theme="b">Logout</button>
</div>
<form action="#" method='post'>
	<div data-role="controlgroup">
		<input type="text" id="joyplaylist" name="playlistname" placeholder="playlist name" data-theme="b" class="required"/>
		<input type="submit" name="musica" value="Search" data-theme="b" data-icon="search"/>
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
		<a href="manageplaylist.php" id="joymanage" data-role="button" data-theme="b" data-icon="bars" <?php if ($rs) echo 'class="ui-disabled"' ?>>Manage playlist</a>
		<a href="changepassword.php" id="joychange" data-role="button" data-theme="b" data-icon="gear">Change password</a>
	</div>
<a href="activate.php" data-role="button" data-theme="b" data-icon="alert" <?php if (!$rs) echo 'style="display: none;"' ?>>Activate account</a>
<div data-role="footer" data-theme="b" data-position="fixed"> 
	<h4><?php print $userid . $verified; ?></h4>
</div>
<?php
/* Check dashboard return button */
$emailchkq = "CALL emaildupcheck ('$userid')";
$stmt = $dsn->prepare($emailchkq);
$stmt->execute();
$rs = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<form action="#" method='post' <?php if (!$rs) echo 'style="display: none;"' ?>>
	<div data-role="controlgroup">
		<input type="submit" name="deletep" value="temp delete all button" data-theme="e"/>
		<?php
		//DELETE DELETE DELETE DELETE DELETE DELETE DELETE DELETE DELETE DELETE DELETE DELETE
		if(isset($_POST['deletep'])){
		$deleteq = "CALL deleteall ('$userid')";
		$stmt = $dsn->prepare($deleteq);
		$stmt->execute();
		session_destroy();
		echo "<script type='text/javascript'>alert('Everything related to $userid was deleted.'); window.location.href = '/';</script>";
		}
		?>
	</div>
</form>

<ol id="joyRideTipContent">
	<li data-text="Next" data-id="joyplaylist" data-options="tipLocation:bottom;">
		<h4>Playlist name</h4>
		<p>Here you enter the playlist name and hit search below.</p>
	</li>
	<li data-text="Next" data-id="joymanage" data-options="tipLocation:bottom;">
		<h4>Sign in</h4>
		<p>If you already have an account, sign in here.  Forgot your password?  Hit Forgot password below.</p>
	</li>
	<li data-text="Next" data-id="joychange" data-options="tipLocation:top;">
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
