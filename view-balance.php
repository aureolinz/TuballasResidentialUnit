<?php
	require_once 'give-access.php';
	$page = 'viewBalance';
	$totalBalance = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once('header.php'); ?>
</head>	
<body>

	<?php require_once('topbar.php'); ?>
	<?php require_once('navbar.php'); ?>
	
	<main id="view-panel" >
		<div class="container-fluid">
			<div class="col-lg-12">
				<div class="row mb-4 mt-4">
					<div class="col-md-12">
				
					</div>
				</div>
			<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Your Balance</b>
					</div>
						<div class="card-body table-responsive mb-5">
							<table class="table table-condensed table-bordered table-hover">
								<thead>
									<tr>
										<th class="text-center">#</th>
										<th class="">Issued Date</th>
										<th class="">Due Date</th>
										<th class="">Invoice #</th>
										<th class="">Amount</th>
										<th class="">Status</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									require_once 'admin/db_connect.php';
									$i = 1;
									$email = $_SESSION['SESSION_EMAIL'];
									$tenant_id = $conn->query("SELECT id FROM tenants WHERE email = '{$email}' LIMIT 1")->fetch_assoc()['id'];
									$invoices = $conn->query("SELECT * FROM payments WHERE tenant_id = '{$tenant_id}' AND (status = 0 OR status = 1) ");
									while($row=$invoices->fetch_assoc()):
									
									?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class="text-center">
											<?php echo date('M d, Y',strtotime($row['date_created'])) ?>
										</td>
										<td class="text-center">
											<p> <b><?php echo date('M d, Y',strtotime($row['due_date'])) ?></b></p>
										</td>
										<td class="text-center">
											<p> <b><?php echo $row['id']; ?></b></p>
										</td>
										<td class="text-center">
										<?php $totalBalance += $row['amount']; ?>
											<p> <b><?php echo number_format($row['amount'],2) ?></b></p>
										</td>
										<?php
											if($row['status'] == 0 || $row['status'] == 1) {
												$color = "red";
												$status = $row['status'] == 0 ? 'Not yet paid' : 'Not yet confirmed';
											}
											else if($row['status'] == 2) {
												$color = "green";
												$status = "Paid";
											}
										?>
										<td class="text-center" style="color: <?php echo $color ?>">
											<p> <b><?php echo $status ?></b></p>
										</td>
										<?php if($status == "Not yet paid"): ?>
										<td class="text-center">
											<button class="btn btn-sm btn-outline-primary" type="button" data-id="<?php echo $row['id'] ?>" onclick="location.href='<?php echo "pay-action.php?id={$row['id']}" ?>'">Pay</button>
										</td>
										<?php endif ?>
									</tr>
									<?php endwhile; ?>
								</tbody>
							</table>
							<div>
								<h3>Total Balance: <?php echo $totalBalance; ?></h3>
								<h4 id="due-date">Next Due date:</h4>
							</div>
						</div>
					</div>
				</div>
				<!-- Table Panel -->
				</div>
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
		document.getElementById(duedateElement).innerHTML = "Next Due Date: <b>"+this.responseText+"</b>";
	}
	
	httpRequest.open("POST", "admin/getDuedate.php");
	httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	httpRequest.send("tenantId="+encodeURIComponent(tenantId));
}
window.onload = getDuedate('<?php echo $tenant_id; ?>');
</script>
</body>	
</html>