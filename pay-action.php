<?php
	require_once 'give-access.php';
	$page = 'toPay';
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
						<b></b>
					</div>
					<div class="card-body table-responsive mb-5">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="">Issued Date</th>
									<th class="">Due Date</th>
									<th class="">Invoice #</th>
									<th class="">Amount</th>
									<th class="">Status</th>
								</tr>
							</thead>
							<tbody>
								<?php
								require_once 'admin/db_connect.php';
								$id = $_GET['id'];
								$invoices = $conn->query("SELECT * FROM payments WHERE id = '{$id}' ");
								while($row=$invoices->fetch_assoc()):
									
								?>
								<tr>
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
										 <p> <b><?php echo number_format($row['amount'],2) ?></b></p>
									</td>
									<td class="text-center" style="color: <?php echo $row['status'] ? 'green' : 'red'?>">
										 <p> <b><?php echo $row['status'] ? 'Paid' : 'Not yet paid'?></b></p>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
					<div class="card-footer">
						<div class="form-group">
							<input type="file" id="file" class="">
						</div>
						<div class="form-group">
						<?php  date_default_timezone_set('Asia/Manila'); ?>
							<label class="form-label" for="payment-date">Payment Date</label>
							<input type="datetime-local" id="payment-date" name="payment-date" class="form-control" value="<?php echo date('Y-m-d') ." ". date('H:i'); ?>">
						</div>
						<div class="form-group">
							<label class="form-label" for="description">Description</label>
							<textarea id="description" name="description" class="form-control"></textarea>
						</div>
						<div class="form-group">
							<label class="form-label" for="comments">Comments / Remarks</label>
							<textarea id="comments" name="comments" class="form-control"></textarea>
						</div>
						<a href="#0" onclick="uploadFile('<?php echo $_GET['id']; ?>')" class="btn btn-success">Upload Proof of payment</a>
						
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
	</main>
	<script>
		function uploadFile(id ,fileId = 'file', paymentDateId = 'payment-date', descriptionId = 'description', commentsId = 'comments') {
			let files = document.getElementById(fileId).files;
			let paymentDate = document.getElementById(paymentDateId).value.replace("T", " ");
			let description = document.getElementById(descriptionId).value;
			let comments = document.getElementById(commentsId).value;
			let httpRequest = new XMLHttpRequest();
	
			let formData = new FormData();
			formData.append("file", files[0]);
			formData.append('id', id);
			formData.append('payment_date', paymentDate);
			formData.append('description', description);
			formData.append('comments', comments);
	
			httpRequest.onreadystatechange = function() {
			if(this.readyState != 4) {
				return;
			}
			if(this.status != 200) {
				return;
			}
			if(this.responseText == "success") {
				alert("Successful");
				location.replace('to-pay.php');
			} 
			else {
				alert(this.responseText);
			}
		}
	
		httpRequest.open("POST", "uploadFile.php");
		httpRequest.send(formData);
		}
	</script>
  
</body>	
</html>