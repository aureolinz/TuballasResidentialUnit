<?php
	require_once 'give-access.php';
	$page = 'announcements';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once('header.php'); ?>
</head>	
<body>

	<?php require_once('topbar.php'); ?>
	<?php require_once('navbar.php'); ?>
	
	<main id="view-panel">
		<?php
			require_once 'admin/db_connect.php';
			if(!isset($_GET['id'])):
			$results = $conn->query("SELECT * FROM announcement");
		?>
		<div class="row card p-3 m-2">
			<div class="card-header">
				<h1>Announcements</h1>
			</div>
			<div class="card-body">
				<?php while($row = $results->fetch_assoc()): ?>
				<div class="card p-2 mb-3 d-flex flex-row justify-content-between align-items-center">
					<a href="announcements.php?id=<?php echo $row['id']; ?>" class=""><?php echo $row['title'] ?></a>
					<?php echo in_array($row['id'], $notifArray) ? '<b style="color: white; background: red; border-radius: 50%; padding: 10px;">new</b>' : ''; ?>
				</div>
				<?php endwhile; ?>
			</div>
		</div>
		<?php else:
			$statement = $conn->prepare("SELECT * FROM announcement WHERE id = ?");
			$statement->bind_param('i', $_GET['id']);
			if($statement->execute()) {
				$statement->bind_result($id, $title, $message, $date);
				$statement->fetch();
				$statement->free_result();
				$tenant_id = $conn->query("SELECT id FROM tenants WHERE email = '{$_SESSION['SESSION_EMAIL']}'")->fetch_assoc()['id'];
				$notifications = $conn->query("SELECT * FROM notification WHERE tenant_id = {$tenant_id} AND type = 'announcement' 
				AND notification_id = {$_GET['id']}");
				if($notifications->num_rows <= 0) {
					$conn->query("INSERT INTO notification(tenant_id, type, notification_id, status) 
					VALUES('{$tenant_id}', 'announcement', '{$_GET['id']}', 1)");
				} else {
					$notif_row = $notifications->fetch_assoc(); 
					if($notif_row['status'] == 0) {
						$conn->query("UPDATE notification SET status = 1 WHERE id = {$notif_row['id']}");
					}
				}
			}
			$message = htmlentities($message,ENT_QUOTES, 'UTF-8');
			$message = str_replace(" ", "&#160;", $message);
			$message = str_replace("\n", "<br>", $message);
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
		<?php endif; ?>
	</main>
	
</body>
</html>