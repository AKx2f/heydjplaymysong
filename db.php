<?php
/* error_reporting(E_ALL); */
/* ini_set('display_errors', '1'); */
$h = "localhost";
$u = "djkabau1_test";
$p = "2S%1%-GZNlIg";
$db = "djkabau1_hdjpms";
$con = new mysqli($h, $u, $p, $db);
$dsn = new PDO('mysql:host=localhost;dbname=djkabau1_hdjpms', $u, $p);
$dsn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
if ($con->connect_errno) {
    echo "Opps some thing went wrong, failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
?>