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
<title>Hey DJ Play My Song - Create Playlist</title>
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
	echo "<script type='text/javascript'>alert('Please verify your account.  You are now being redirected to the Dashboard.'); window.location.href = 'dashboard.php';</script>";
}
?>
<div data-role="header" data-theme="b">
	<button onclick="window.location='manageplaylist.php'" data-icon="back" data-theme="b">Back</button>
	<h1>Create playlist</h1>
	<button onclick="window.location='logout.php'" data-icon="delete" data-theme="b">Logout</button>
</div>
<form action="#" method='post'>
	<input type="text" id="joyplaylist" name="newplaylistname" placeholder="playlist name" data-theme="b"/>
	<input type="submit" name="gocheck" value="Continue.." data-theme="b" data-icon="check"/>
</form>
<?php
if(isset($_POST['gocheck'])){
	if (!empty($_POST['newplaylistname'])){
		/* playlist name check */
		$newplaylistname = $_POST['newplaylistname'];
		$newplaylistname = stripslashes($newplaylistname);
		/* Playlist check and collect */
		$getplaylistq = "CALL getplaylistname ('$newplaylistname')";
		$stmt = $dsn->prepare($getplaylistq);
		$stmt->execute();
		$rs2 = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$rs2){
			$ok = 'ok';
			$_SESSION['ok'] = $ok;
			$_SESSION['newplaylistname'] = $newplaylistname;
			echo "<script type='text/javascript'>window.location.href = 'uploadplaylist.php';</script>";
		}
		else{
			echo "<script type='text/javascript'>alert('This playlist name is already taken.  Try again.');</script>";
		}
	}
	else{
		echo "<script type='text/javascript'>alert('Please enter a playlist name before continuing.');</script>";
	}
}
?>
<div data-role="footer" data-theme="b" data-position="fixed"> 
	<h4><?php print $userid; ?></h4> 
</div>
<ol id="joyRideTipContent">
	<li data-text="Ok" data-id="joyplaylist" data-options="tipLocation:bottom;">
		<h4>Playlist name</h4>
		<p>Type in a unique playlist name you would like to use such as johnsmithbirthdayparty2013.</p>
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