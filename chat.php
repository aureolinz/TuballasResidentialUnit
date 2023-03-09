<?php
	require_once 'give-access.php';
	$page = 'chat';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once('header.php'); ?>
	<link href="assets/css/chat.css" rel="stylesheet">
</head>	
<body>

	<?php require_once('topbar.php'); ?>
	<?php require_once('navbar.php'); ?>
	
	<main id="view-panel">
		<div class="chat-results mt-5" id="chat-results">
		
		</div>
		<div class="chatbox-container">
			<form onsubmit="return false">
				<input type="text" name="chatbox" id="chatbox" autocomplete="off">
				<input type="submit" value="Send" onclick="insertXML(document.getElementById('chatbox').value)">
			</form>
		</div>
	</main>
	
	<script> sessionStorage.setItem("email","<?php echo $_SESSION["SESSION_EMAIL"] ?>"); </script>
	<script src="assets/js/chat.js"></script>
	
</body>
</html>