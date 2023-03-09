<?php 
	
	require_once 'db_connect.php';
	
	$today = new DateTime('Now');
	$today = date_format($today, 'Y-m-d');
	
	$statement = $conn->prepare("UPDATE payments SET status = 2, payment_date = ?, description = ?, comments = ? WHERE id = ? LIMIT 1");
	$statement->bind_param('sssi', $_POST['payment_date'], $_POST['description'], $_POST['comments'], $_POST['id']);
	
	if(!$statement->execute()) {
		die('Something went wrong! Please make sure that filename is no longer than 100 characters');
	}
	
	echo "success";
	
	
?>