<?php
	session_start();
	if(!isset($_SESSION['SESSION_EMAIL'])) {
		header('location: login.php');
	}
?>