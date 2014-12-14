<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=320, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/main.css"> 
<link rel="stylesheet" href="//code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css"/>
<script src="//code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<title>Hey DJ Play My Song - Logout</title>
</head>

<body>
<?php
/* error_reporting(E_ALL); */
/* ini_set('display_errors', '1'); */
include('db.php');
include('session.php');
/* var_dump($); */
session_destroy();
echo '<h3>You\'ll be redirected in about 3 secs. If not, click <a href="/">here</a></h3>.';
?>
<script type="text/JavaScript">setTimeout("location.href = '/';",3000);</script>
<?php
$con->close();
$dsn = null;
?>
</body>
</html>
