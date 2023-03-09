<?php

$fileContents = '<?xml version="1.0"?>
<?php header ("Content-Type:text/xml");
session_start();
	
if(!isset($_SESSION["SESSION_EMAIL"])){
	die("You cant access this page!");
}
?>
<root>

</root>';
				
	$file = '../chatMessagesXML/' . $email . '.php';
	file_put_contents($file,$fileContents);
				
?>