<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=320, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" href="//code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css"/>
<script src="//code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<title>Hey DJ Play My Song</title>
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
var_dump($userid);
$row = 0;
if (($handle = fopen("itunes.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $c=0;
		$row++;
        for ($c=0; $c < 1; $c++) {
			$songname = $data[0];
			$songartist = $data[1];
        }
		echo $songname . "<br />\n";
		echo $songartist . "<br />\n";
		
		$createsongq = "CALL createsong('hello', '$songname', '$songartist', '0', '$userid')";
		$rs = $dsn->exec($createsongq);
		var_dump($rs);
    }
    fclose($handle);
}
else{
	echo "<script type='text/javascript'>alert('Upload failed.');</script>";
}
?>
</form>
<?php
$con->close();
$dsn = null;
?>
</body>
</html>