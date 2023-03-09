<?php

	require_once 'db_connect.php';
	
	if(isset($_POST['houseNo'])) {
		$statement = $conn->prepare("SELECT house_id, type FROM tenants WHERE id = ? LIMIT 1");
		$statement->bind_param('i', $_POST['houseNo']);
		$statement->execute();
		$statement->bind_result($_POST['houseId'], $type);
		$statement->fetch();
		$statement->free_result();
	}
	
	if($type == 'Room') {
		$statement = $conn->prepare("SELECT price FROM houses WHERE id = ? LIMIT 1");
		$statement->bind_param('i', $_POST['houseId']);
	}
	else {
		$statement = $conn->prepare("SELECT amount FROM bedspacer WHERE bedspacer_id = ? LIMIT 1");
		$statement->bind_param('i', $_POST['houseId']);
	}
	
	if(!$statement->execute()) {
		die('Something went wrong!');
	}
	
	$statement->bind_result($result);
	$statement->fetch();
	
	echo $result;

?>