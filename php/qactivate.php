<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=320, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" href="//code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css"/>
<script src="//code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<title>Hey DJ Play My Song - Quick Activate</title>
</head>

<body>
<?php
/* error_reporting(E_ALL); */
/* ini_set('display_errors', '1'); */
include('db.php');
/* var_dump($rs); */
/* Collecting the activation code */
$activationnum = $_GET["confirm"];
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
	echo "<script type='text/javascript'>alert('Account activated.  You may now login.'); window.location.href = '/';</script>";
}
else{
	echo "<script type='text/javascript'>alert('Invalid link.  Reset your account.'); window.location.href = '/';</script>";
}
$con->close();
$dsn = null;
?>
</body>
</html>