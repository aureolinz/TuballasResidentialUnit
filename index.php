<?php
	require_once 'give-access.php';
	$page = 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once('header.php'); ?>
	<style>
		.dashboard-item {
			width: 30%;
			color: white;
			height: 200px;
			display: flex;
			justify-content: center;
			align-items: center;
			font-size: 20px;
			border-radius: 15px;
			cursor: pointer;
			font-weight: 600;
		}
		@media screen and (max-width: 1000px) {
			.dashboard-item {
				width: 90%;
				margin-bottom: 10px;
			}
		}
	</style>
</head>	
<body>

	<?php require_once('topbar.php'); ?>
	<?php require_once('navbar.php'); ?>
	
	<main id="view-panel" >
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="d-flex justify-content-around flex-wrap">
			<div class="card dashboard-item" style="background-color: hsl(240, 100%, 50%);" onclick="location.href='view-balance.php'">
			<?php
				$balance = $conn->query("SELECT SUM(amount) AS a FROM payments WHERE tenant_id = '{$tenant_id}' AND (status = 0 OR status = 1) ");
				$balance = $balance->num_rows > 0 ? $balance->fetch_assoc()['a'] : 0;
			?>
				Balance <?php echo "<b>" .number_format(floatval($balance), 2). "</b>"; ?>
			</div>
			<div class="card dashboard-item" id="due-date" style="background-color: hsl(240, 100%, 70%);" onclick="location.href='view-balance.php'">
				Due Date
			</div>
			<div class="card dashboard-item" style="background-color: hsl(240, 100%, 30%);" onclick="location.href='announcements.php'">
				Announcements: <?php echo "<b style='background: red; border-radius: 50%; padding: 4px 10px;'>".count($notifArray). "</b>"; ?>
			</div>
		
		</div>
	</main>
<script>
function getDuedate(tenantId, duedateElement = 'due-date') {
	let httpRequest = new XMLHttpRequest();
	
	httpRequest.onreadystatechange = function() {
		if(this.readyState != 4) {
			return;
		}
		if(this.status != 200) {
			return;
		}
		document.getElementById(duedateElement).innerHTML = "Due Date : <b>"+this.responseText+"</b>";
	}
	
	httpRequest.open("POST", "admin/getDuedate.php");
	httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	httpRequest.send("tenantId="+encodeURIComponent(tenantId));
}
<?php $tenant_id = $conn->query("SELECT id FROM tenants WHERE email = '{$_SESSION['SESSION_EMAIL']}' LIMIT 1")->fetch_assoc()['id']; ?>
window.onload = getDuedate('<?php echo $tenant_id; ?>');
</script>
</body>	
</html>