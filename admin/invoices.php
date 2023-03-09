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
						<b>List of Payments</b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-md-2 float-right" href="javascript:void(0)" id="new_invoice">
					<i class="fa fa-plus"></i> Create new invoice
				</a></span>
					</div>
					<div class="card-body table-responsive mb-5">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Issued Date</th>
									<th class="">Due Date</th>
									<th class="">Payment Date</th>
									<th class="">Tenant</th>
									<th class="">Invoice #</th>
									<th class="">Amount</th>
									<th class="">Description</th>
									<th class="">Comments</th>
									<th class="">Status</th>
									<th class="">Action</th>
									<!--<th class="text-center">Action</th>-->
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$invoices = $conn->query("SELECT p.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as name FROM payments p inner join tenants t on t.id = p.tenant_id where t.status = 1 order by date(p.date_created) desc ");
								while($row=$invoices->fetch_assoc()):
									
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td>
										<?php echo date('M d, Y',strtotime($row['date_created'])) ?>
									</td>
									<td>
										<?php echo date('M d, Y',strtotime($row['due_date'])) ?>
									</td>
									<td>
										<?php echo !empty($row['payment_date']) ? date('M d, Y h:i A',strtotime($row['payment_date'])) : "" ?>
									</td>
									<td class="">
										 <p> <b><?php echo ucwords($row['name']) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo $row['id']; ?></b></p>
									</td>
									<td class="text-right">
										 <p> <b><?php echo number_format($row['amount'],2) ?></b></p>
									</td>
									<td class="">
										 <p> <?php echo ucwords(htmlentities($row['description'])) ?></p>
									</td>
									<td class="">
										 <p> <?php echo ucwords($row['comments']) ?></p>
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
									<?php if($status != "Paid"): ?>
									<td class="text-center">
										<button class="btn btn-sm btn-outline-primary" onclick="location.href='confirmPayment.php?id=<?php echo $row['id']; ?>'">Confirm</button>
									</td>
									<?php endif; ?>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
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
	
	$('#new_invoice').click(function(){
		uni_modal("New invoice","manage_payment.php","mid-large")
		
	})
	$('.edit_invoice').click(function(){
		uni_modal("Manage invoice Details","manage_payment.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_invoice').click(function(){
		_conf("Are you sure to delete this invoice?","delete_invoice",[$(this).attr('data-id')])
	})
	
	function delete_invoice($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_payment',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	
	function addPayment() {
	start_load();
	let httpRequest = new XMLHttpRequest();
	let tenantId = encodeURIComponent(document.getElementById('tenant_id').value);
	let amount = encodeURIComponent(document.getElementById('amount').value);
	let dueDate = encodeURIComponent(document.getElementById('due-date').value);
	let paymentType = encodeURIComponent(document.getElementById('payment-type').value);
	
	httpRequest.onreadystatechange = function() {
		console.log(this.responseText);
		if(this.readyState != 4) {
			return;
		}
		if(this.status != 200) {
			return;
		}
		if(this.responseText==1){
			alert_toast("Data successfully added",'success')
			setTimeout(function(){
						location.reload()
			},1500)

		}
	}
	
	httpRequest.open("POST", "ajax.php?action=save_payment");
	httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	httpRequest.send("tenant_id=" +tenantId+ "&amount=" +amount+"&due_date="+dueDate+"&payment_type="+paymentType);
	}
</script>