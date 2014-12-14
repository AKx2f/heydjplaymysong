<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/

$userid = $_GET["userid"];
$userid = stripslashes($userid);

// Define a destination
$targetFolder = '/uploads/' ; // Relative to the root and should match the upload folder in the uploader script

if (file_exists($_SERVER['DOCUMENT_ROOT'] . $targetFolder  . $userid . "_" . $_POST['filename'])) {
	echo 1;
} else {
	echo 0;
}
?>