<?php 
	require_once '../giveAccess.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="styles/style.css">
		<title>Chat system</title>
	</head>
	
	<body>
		<nav class="topnav">
			<h1><a href="../../">Back</a></h1>
			<h1><a href="#0">Chat</a></h1>
		</nav>
		<div class="chat-results" id="chat-results">
		
		</div>
		<div class="chatbox-container">
			<form onsubmit="return false">
				<input type="text" name="chatbox" id="chatbox" autocomplete="off">
				<input type="submit" value="Send" onclick="insertXML(document.getElementById('chatbox').value)">
			</form>
		</div>
	</body>
	<script> sessionStorage.setItem("email","<?php echo $_SESSION["SESSION_EMAIL"] ?>"); </script>
	<script src="scripts/chat.js"></script>
</html>