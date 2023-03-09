<?php

	require_once 'db_connect.php';
	
	$statement = $conn->prepare("SELECT date_in FROM tenants WHERE id = ? LIMIT 1");
	$statement->bind_param('i', $_POST['tenantId']);
	
	if(!$statement->execute()) {
		die('Something went wrong!');
	}
	
	$statement->bind_result($result);
	$statement->fetch();
	
	$addedDays = 30;
	$date = new DateTime($result);
	
	while(strtotime($result) < time()) {
		$date->modify("+{$addedDays} days");
		$result = $date->format('Y-m-d');
	}
	echo $result;

?>