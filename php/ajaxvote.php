<?php
/**
 * jQuery Voting System
 * @link http://www.w3bees.com/2013/09/voting-system-with-jquery-php-and-mysql.html
 */
error_reporting(E_ALL); 
ini_set('display_errors', '1'); 
include('db.php');
# start new session
session_start();
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (isset($_POST['postid']) AND isset($_POST['action'])) {
		$postId = $_POST['postid'];
		
		if ($_POST['action'] === 'up' ){
			# check if already voted, if found voted then return
			if (isset($_SESSION['vote'][$postId])) {
			echo "<script type='text/javascript'>alert('Invalid entry.  If you believe you have an account, use Forgot password.');</script>";
			return;
			}
			$updatevote = "CALL uprating('$postId')";
			$dsn->exec($updatevote);
			# set session with post id as true
			$_SESSION['vote'][$postId] = true;
			# close db connection
		}
		elseif ($_POST['action'] === 'down' ) {
			# check if already voted, if found voted then return
			if (isset($_SESSION['vote'][$postId])) return;
			$updatevote = "CALL downrating('$postId')";
			$dsn->exec($updatevote);
			# set session with post id as true
			$_SESSION['vote'][$postId] = true;
			# close db connection
		}
		elseif ($_POST['action'] === 'del' ){
			$deleteq = "CALL deleteonesong('$postId')";
			$dsn->exec($deleteq);
		} 
		elseif ($_POST['action'] === 'res' ) {
			$resetq = "CALL resetonesong('$postId')";
			$dsn->exec($resetq);
		}
		else
		{
		}
	}
}
# db connection close

$con->close();
$dsn = null;
?>