<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=320, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/main.css"> 
<link rel="stylesheet" href="//code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css"/>
<script src="//code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script src="js/jquery.uploadifive.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/modernizr.mq.js"></script>
<script type="text/javascript" src="js/jquery.joyride-2.1.js"></script>
<style type="text/css">
.uploadifive-button {
	float: left;
	margin-right: 10px;
}
#queue {
	border: 1px solid #E5E5E5;
	height: 60px;
	overflow: auto;
	margin-bottom: 10px;
	padding: 0 3px 3px;
	width: 300px;
}
</style>
<title>Hey DJ Play My Song - Upload playlist</title>
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
$newplaylistname = $_SESSION['newplaylistname'];
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
if(!isset($_SESSION['ok']) && empty($_SESSION['ok'])) {
		echo "<script type='text/javascript'>alert('Please login or create an account.  You are now being redirected to the homepage.'); window.location.href = 'create.php';</script>";	
}
?>
<div data-role="header" data-theme="b">
	<button onclick="window.location='createplaylist.php'" data-icon="back" data-theme="b">Back</button>
	<h1>Upload playlist</h1>
	<button onclick="window.location='logout.php'" data-icon="delete" data-theme="b">Logout</button>
</div>
<form>
	<div id="queue"></div>
	<input id="file_upload" name="file_upload" type="file" multiple="false">
	<br/><br/>
	<a type="button" href="javascript:$('#file_upload').uploadifive('upload')" data-theme="b">Upload</a>
	<a style="position: relative; top: 8px;" href="javascript:$('#file_upload').uploadifive('upload')">Upload Files</a>
</form>
<script type="text/javascript">
	<?php $timestamp = time();?>
	$(function() {
		$('#file_upload').uploadifive({
			'auto'             : false,
			'checkScript'      : 'check-exists.php',
			'formData'         : {
								   'timestamp' : '<?php echo $timestamp;?>',
								   'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
								 },
			'queueID'          : 'queue',
			'uploadScript'     : 'uploadplaylist.php',
			'onUploadComplete' : function(file, data) { console.log(data); }
		});
	});
</script>
<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/

// Set the uplaod directory
$uploadDir = '/uploads/';

// Set the allowed file extensions
$fileTypes = array('csv'); // Allowed file extensions

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];
	$uploadDir  = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
	$targetFile = $uploadDir . $userid . "_" . $_FILES['Filedata']['name'];

	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {

		// Save the file
		move_uploaded_file($tempFile, $targetFile);
		/* IN BEFORE THE LOCK YES!!! */
		$row = 0;
		if (($handle = fopen($targetFile, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$c=0;
				$row++;
				for ($c=0; $c < 1; $c++) {
					$songname = $data[0];
					$songartist = $data[1];
				}
				echo $songname . "<br />\n";
				echo $songartist . "<br />\n";
				
				$createsongq = "CALL createsong('$newplaylistname', '$songname', '$songartist', '0', '$userid')";
				$rs = $dsn->exec($createsongq);
			}
			fclose($handle);
		}
		else{
			echo "<script type='text/javascript'>alert('Upload failed.');</script>";
		}
		echo 1;

	} else {

		// The file type wasn't allowed
		echo 'Invalid file type.';
	}
}
?>
<div data-role="footer" data-theme="b" data-position="fixed"> 
	<h4><?php print $userid; ?></h4> 
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