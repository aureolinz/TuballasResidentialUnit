<?php 
	require_once 'db_connect.php';
	if(!isset($_GET['action'])) {
		$results = $conn->query("SELECT * FROM announcement");
	}
	$action = isset($_GET['action']) ? $_GET['action'] : '';
	if(isset($_POST['add'])) {
		if($action == 'edit') {
			$statement = $conn->prepare("UPDATE announcement SET title = ?, message = ?, date = NOW() WHERE id = ?");
			$statement->bind_param('ssi', $_POST['title'], $_POST['message'], $_GET['id']);
			if($statement->execute()) {
				$conn->query("UPDATE notification SET status = 0 WHERE type = 'announcement' AND notification_id = {$_GET['id']};");
				echo "<script> alert('Successful'); location.href = 'index.php?page=announcements'; </script>";
			}
		} else if($action == 'add'){
			$statement = $conn->prepare("INSERT INTO announcement (title, message) VALUES(?, ?)");
			$statement->bind_param('ss', $_POST['title'], $_POST['message']);
			if($statement->execute())
				echo "<script> alert('Successful'); location.href = 'index.php?page=announcements'; </script>";
		}
	}
?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<?php if($action == 'add' || $action == 'edit'): ?>
		<?php 
			if(isset($_GET['id'])) {
				$statement = $conn->prepare("SELECT * FROM announcement WHERE id = ?");
				$statement->bind_param('i', $_GET['id']);
				if($statement->execute()) {
					$statement->bind_result($id, $title, $message, $date);
					$statement->fetch();
				}
			}
		?>
		<div class="row card p-3">
			<div class="">
				<h1>Create Announcement</h1>
			</div>
			<form class="" action="index.php?page=announcements&action=<?php echo $action; 
			echo isset($_GET['id']) ?  "&id={$_GET['id']}" : ''; ?>" method="POST">
				<label class="form-label">Title</label>
				<input type="text" maxlength="50" class="form-control" placeholder="Enter title" name="title"
				value="<?php echo isset($title) ? $title : ''; ?>" >
				<label class="form-label">Message</label>
				<textarea class="form-control mb-2" rows="12" maxlength="3000" name="message" placeholder="Enter your message"><?php echo isset($message) ? $message : ''; ?></textarea>
				<input type="submit" class="btn btn-primary" name="add">
			</form>
		</div>
		<?php elseif($action == 'show'): ?>
		<?php 
			$statement = $conn->prepare("SELECT * FROM announcement WHERE id = ?");
			$statement->bind_param('i', $_GET['id']);
			if($statement->execute()) {
				$statement->bind_result($id, $title, $message, $date);
				$statement->fetch();
			}
			$message = htmlentities($message,ENT_QUOTES, 'UTF-8');
			$message = str_replace(" ", "&#160;", $message);
			$message = str_replace("\n", "<br>", $message);
			//$message = str_replace("\t", "<pre>&#9;</pre>", $message);
			
		?>
		<div class="row card p-3">
			<div class="card-header">
				<h1><?php echo $title; ?></h1>
			</div>
			<div class="card-body" style="width: 100%;">
				<p><?php echo $message; ?></p>
			</div>
			<div class="card-footer">
				<h5>Last Edited: <b><?php echo $date; ?></b></h5>
			</div>
		</div>
		<?php else: ?>
		<div class="row card p-3">
			<div class="card-header">
				<h1>Announcements</h1>
			</div>
			<div class="card-body">
				<?php while($row = $results->fetch_assoc()): ?>
				<div class="card p-2 mb-3 d-flex flex-row justify-content-between align-items-center">
					<a href="index.php?page=announcements&action=show&id=<?php echo $row['id']; ?>" class=""><?php echo $row['title'] ?></a>
					<button class="btn btn-primary" onclick="location.href='index.php?page=announcements&action=edit&id=<?php echo $row['id']; ?>'">Edit</button>
				</div>
				<?php endwhile; ?>
			</div>
			<div class="card-footer">
				<button class="btn btn-success" onclick="location.href='index.php?page=announcements&action=add'">Add new</button>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>