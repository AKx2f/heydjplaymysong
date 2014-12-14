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
<title>Hey DJ Play My Song - Manage playlist</title>
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
/* GET LIST GET LIST GET LIST GET LIST GET LIST GET LIST GET LIST GET LIST GET LIST GET LIST*/
$gtallplist2delete = "CALL getallplaylist2delete('$userid')";
$stmt = $dsn->prepare($gtallplist2delete);
$stmt->execute();
?>
<div data-role="header" data-theme="b">
	<button onclick="window.location='dashboard.php'" data-icon="back" data-theme="b">Back</button>
	<h1>Manage playlist</h1>
	<button onclick="window.location='logout.php'" data-icon="delete" data-theme="b">Logout</button>
</div>
<form>
<ul data-role="listview" data-inset="true" data-corners="false">	
<?php
		while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)){
			$plistname= $rs['PLAYLISTNAME'];
			?>
			<li>Playlist name: <?php echo $rs['PLAYLISTNAME']; ?></li>	
			<?php
		}
	?>
</ul>
</form>
<form action="#" method='post'>
	<div data-role="controlgroup">
		<input type="text" id="joyplaylist" name="playlistname" placeholder="playlist name" data-theme="b">
		<input type="submit" name="resetp" value="Reset" data-icon="minus" data-theme="b"/>
		<input type="submit" name="deletep" value="Delete" data-icon="delete" data-theme="e"/>
		<?php
		$stmt = null;
		/* RESET RESET RESET RESET RESET RESET RESET RESET RESET RESET RESET RESET RESET RESET */
		if(isset($_POST['resetp'])){
			$resetplaylist = $_POST['playlistname'];
			$resetplaylist = stripslashes($resetplaylist);
			/* Check email */
			$manageplaylistcheck = "CALL manageplaylistcheck ('$userid', '$resetplaylist')";
			$stmtr = $dsn->prepare($manageplaylistcheck);
			$stmtr->execute();
			$rsr = $stmtr->fetch(PDO::FETCH_ASSOC);
			if ($rsr){
				$stmtr->closeCursor();
				$resetq = "CALL resetplaylist ('$resetplaylist')";
				$stmtrr = $dsn->prepare($resetq);
				$stmtrr->execute();
				$rsrx = $stmtrr->fetch(PDO::FETCH_ASSOC);
				echo "<script type='text/javascript'>alert('Playlist $resetplaylist votes were resetted to 0.'); window.location.href = 'manageplaylist.php';</script>";
			}
			else{
				echo "<script type='text/javascript'>alert('Invalid entry.  Make sure to enter the playlist listed above.');</script>";
			}
		}
		/* DELETE DELETE DELETE DELETE DELETE DELETE DELETE DELETE DELETE DELETE DELETE DELETE */
		if(isset($_POST['deletep'])){
			/* email and password sent from form */
			$deleteplaylist = $_POST['playlistname'];
			$deleteplaylist = stripslashes($deleteplaylist);
			/* Check email */
			$manageplaylistcheck = "CALL manageplaylistcheck ('$userid', '$deleteplaylist')";
			$stmtd = $dsn->prepare($manageplaylistcheck);
			$stmtd->execute();
			$rsd = $stmtd->fetch(PDO::FETCH_ASSOC);
			if ($rsd){
				$stmtd->closeCursor();
				$deleteq = "CALL deleteplaylist ('$deleteplaylist')";
				$stmtdd = $dsn->prepare($deleteq);
				$stmtdd->execute();
				echo "<script type='text/javascript'>alert('Playlist $deleteplaylist was deleted.'); window.location.href = 'manageplaylist.php';</script>";
			}
			else{
				echo "<script type='text/javascript'>alert('Invalid entry.  Make sure to enter the playlist listed above.');</script>";
			}
		}
		?>
		<a href="createplaylist.php" id="joycreate" data-role="button" data-theme="b" data-icon="plus">Create playlist</a>
	</div>
</form>
<div data-role="footer" data-theme="b" data-position="fixed"> 
	<h4><?php print $userid; ?></h4>
</div>
<ol id="joyRideTipContent">
	<li data-text="Next" data-id="joyplaylist" data-options="tipLocation:bottom;">
		<h4>Playlist name</h4>
		<p>Type in any playlist names listed above (if any were created by you).  Below you can delete it or reset all its votes.</p>
	</li>
	<li data-text="Next" data-id="joycreate" data-options="tipLocation:top;">
		<h4>Create playlist</h4>
		<p>This link will bring you to the page where you can create a playlist.</p>
	</li>
	<li data-text="Ok" data-options="tipLocation:bottom;">
		<p>You can reset and delete individual songs once you search your playlist (homepage or dashboard).  You are required to be logged on and be the owner of that playlist name.</p>
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