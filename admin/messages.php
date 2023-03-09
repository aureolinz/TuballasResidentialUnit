<link rel="stylesheet" href="../assets/css/messages.css">
<div class="content">
	<h1 class="message-title">Messages</h1>
	<div class="chatcontainer">
	
	<?php 		 
		$chats = scandir("../chatMessagesXML/");
		require_once 'db_connect.php';
		for($counter = 2; $counter < count($chats); $counter++) {
			$email = substr($chats[$counter], 0, -4 );
			$results = $conn->query("SELECT firstname, middlename, lastname FROM tenants WHERE email = '{$email}'")->fetch_assoc();
			$fullname = '';
			$fullname .= empty($results['firstname']) ? "" : $results['firstname'];
			$fullname .= empty($results['middlename']) ? "" : " ".$results['middlename'];
			$fullname .= empty($results['lastname']) ? "" : " ".$results['lastname'];
			echo "<li onclick = goToChat('". $chats[$counter] ."')>" .  $fullname. "</li>";
				
		}
	?>  
	

	</div>

     
	<script>
		function goToChat(email) {
			sessionStorage.setItem("email", email);
			location.assign("index.php?page=chat&email="+email.slice(0, email.length - 4));
		}
	</script>
	
</div>
	