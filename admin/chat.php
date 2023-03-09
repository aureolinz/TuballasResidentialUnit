<?php 
	$_SESSION['SESSION_EMAIL'] = $_GET['email'];
	require_once 'db_connect.php';
	
	$results = $conn->query("SELECT firstname, middlename, lastname FROM tenants WHERE email = '{$_SESSION['SESSION_EMAIL']}'")->fetch_assoc();
	$fullname = '';
	$fullname .= empty($results['firstname']) ? "" : $results['firstname'];
	$fullname .= empty($results['middlename']) ? "" : " ".$results['middlename'];
	$fullname .= empty($results['lastname']) ? "" : " ".$results['lastname'];
	echo "<script>sessionStorage.setItem('{$_SESSION['SESSION_EMAIL']}', '{$fullname}');</script>";
				
?>

<link rel="stylesheet" href="../assets/css/chat.css">

<div class="chat-results mt-5" id="chat-results">
		
</div>
<div class="chatbox-container">
	<form onsubmit="return false">
		<input type="text" name="chatbox" id="chatbox" autocomplete="off">
		<input type="submit" value="Send" onclick="insertXML(document.getElementById('chatbox').value)">
	</form>
</div>
<script src="../assets/js/chat-admin.js"></script>