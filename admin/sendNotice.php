<?php

	require_once 'sendEmail.php';
	
	sendEmail($_POST['email'], $_POST['message'], $_POST['subject']);
	
	die("1");

?>