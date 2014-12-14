<?php
session_start();
	if(!isset($_SESSION['userid']) && empty($_SESSION['userid'])) {
		echo "<script type='text/javascript'>alert('Please login or create an account.  You are now being redirected to the homepage.'); window.location.href = '/';</script>";	
	}
?>