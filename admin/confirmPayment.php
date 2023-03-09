<!DOCTYPE html>
<html lang="en">
	
<?php session_start(); ?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : '' ?></title>
 	

<?php
  if(!isset($_SESSION['login_id']))
    header('location:login.php');
 include('./header.php'); 
 ?>

</head>
<style>
	body{
        background: #80808045;
	}
</style>

<body>
	<?php include 'topbar.php' ?>
	<?php include 'navbar.php' ?>
  <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body text-white">
    </div>
  </div>
  
  <main id="view-panel" >
  	<?php include('db_connect.php');?>

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
						<b>Confirm Payment</b>
					</div>
					<div class="card-body table-responsive mb-5">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Issued Date</th>
									<th class="">Due Date</th>
									<th class="">Tenant</th>
									<th class="">Invoice #</th>
									<th class="">Amount</th>
									<th class="">Status</th>
									<th class="">Image</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$invoices = $conn->query("SELECT * FROM payments WHERE id = {$_GET["id"]}");
								while($row=$invoices->fetch_assoc()):
								$name = $conn->query("SELECT * FROM tenants WHERE id = {$row['tenant_id']} ")->fetch_assoc();
								$paymentDate = $row['payment_date'];
								$description = $row['description'];
								$comments = $row['comments'];
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td>
										<?php echo date('M d, Y',strtotime($row['date_created'])) ?>
									</td>
									<td>
										<?php echo date('M d, Y',strtotime($row['due_date'])) ?>
									</td>
									<td class="">
										 <p> <b><?php echo $name['lastname']. ", " . $name['firstname'] . ", ". $name['middlename']; ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo $row['id']; ?></b></p>
									</td>
									<td class="text-right">
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
									<td class="text-center" style="color: <?php echo $color ?>">
										 <p> <a href="../paymentsImages/<?php echo $row['proofFilename'] ?>" target="_blank"><?php echo $row['proofFilename'] ?></a></p>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
					<div class="card-footer">
						<div class="form-group">
						<?php  date_default_timezone_set('Asia/Manila'); ?>
							<label class="form-label" for="payment-date">Payment Date</label>
							<input type="datetime-local" id="payment-date" name="payment-date" class="form-control" 
							value="<?php echo !empty($paymentDate) ? $paymentDate : date('Y-m-d') ." ". date('H:i'); ?>">
						</div>
						<div class="form-group">
							<label class="form-label" for="description">Description</label>
							<textarea id="description" name="description" class="form-control"><?php echo $description ?></textarea>
						</div>
						<div class="form-group">
							<label class="form-label" for="comments">Comments / Remarks</label>
							<textarea id="comments" name="comments" class="form-control"><?php echo $comments ?></textarea>
						</div>
						<a href="#0" class="btn btn-success" onclick="confirmPayment('<?php echo $_GET['id']?>')">Confirm</a>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: :150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	
	function confirmPayment(id, paymentDateId = 'payment-date', descriptionId = 'description', commentsId = 'comments') {
		if(!confirm('Do you really want to confirm this payment?'))
			return;
		
		let httpRequest = new XMLHttpRequest();
		let paymentDate = document.getElementById(paymentDateId).value.replace("T", " ");
		let description = document.getElementById(descriptionId).value;
		let comments = document.getElementById(commentsId).value;
		let formData = new FormData();
		
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
			location.replace('index.php?page=invoices');
		} 
		else {
			alert(this.responseText);
		}
	}

	httpRequest.open("POST", "approvePayment.php");
	httpRequest.send(formData);
	}
	
</script>
  	

  </main>

  
</script>	
</html>