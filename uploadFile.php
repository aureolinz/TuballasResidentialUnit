<?php 
	
	require_once 'admin/db_connect.php';
	
	if(!isset($_FILES['file'])) {
		die("Please Select a file!");
	}
	
	$statement = $conn->prepare("UPDATE payments SET proofFilename = ?, status = 1, payment_date = ?, description = ?, comments = ?  WHERE id = ? LIMIT 1");
	$statement->bind_param('ssssi', $_FILES['file']['name'], $_POST['payment_date'], $_POST['description'], $_POST['comments'], $_POST['id']);
	
	if(!$statement->execute()) {
		die('Something went wrong! Please make sure that filename is no longer than 100 characters');
	}
	
	$fileSource = $_FILES['file']['tmp_name'];
	$fileDestination = "paymentsImages\\" . $_FILES['file']['name'];
	
	if(!move_uploaded_file($fileSource, $fileDestination)) {
		die('Upload Unsuccessful');
	}
	
	echo "success";
	
	
?>