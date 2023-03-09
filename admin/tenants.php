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
						<b>List of Tenant</b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-3 ml-5 float-right" href="javascript:void(0)" id="new_bedspacer_tenant">
					<i class="fa fa-plus"></i> New Bedspacer Tenant
				</a></span>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_tenant">
					<i class="fa fa-plus"></i> New Room Tenant
				</a></span>
					</div>
					<div class="card-body table-responsive mb-5">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Due Date</th>
									<th class="">Name</th>
									<th class="">Email</th>
									<th class="">Contact #</th>
									<th class="">Contact Person</th>
									<th class="">Emergency Contact #</th>
									<th class="">Government ID</th>
									<th class="">Type</th>
									<th class="">Room / Bedspacer Rented</th>
									<th class="">Monthly Rate</th>
									<!--<th class="">Outstanding Balance</th>
									<th class="">Last Payment</th>-->
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								//$tenant = $conn->query("SELECT t.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as name,h.house_no,h.price FROM tenants t inner join houses h on h.id = t.house_id where t.status = 1 order by h.house_no desc ");
								$tenant = $conn->query("SELECT t.*,concat(t.lastname,', ',t.firstname,' ',t.middlename, t.suffix) as name FROM tenants t where t.status = 1");
								while($row=$tenant->fetch_assoc()):
								?>
								<?php
									if($row['type'] == 'Room') {
										$house = $conn->query("SELECT * FROM houses WHERE id = {$row['house_id']}")->fetch_assoc();
									}
									else if($row['type'] == 'Bedspacer'){
										$house = $conn->query("SELECT bedspacer_no as house_no, amount as price FROM bedspacer WHERE bedspacer_id = {$row['house_id']}")->fetch_assoc();
									}
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<?php
										$due_date = new DateTime($row['date_in']);
									?>
									<td class="text-center"><?php echo $due_date->modify("+30 days")->format('Y-M-d')  ?>
									<?php $message = "This notice is to inform you that rent for the month of {$due_date->format('M-Y')} in the amount of {$house['price']} needs to be paid 3 days before the due date.<br>'+
									'Rent is to be paid by cash or via gcash and made payable to James Vince Tuballas.<br><br> Thank you,<br><br> Landlord"; ?>
									<button class="btn btn-danger btn-sm" 
									onclick="sendNotice('<?php echo $row['email'] ?>','<?php echo $message ?>', 'Rent Notice')">Send Notice</button></td>
									<td>
										<?php echo ucwords($row['name']) ?>
									</td>
									<td>
										<?php echo ($row['email']) ?>
									</td>
									<td>
										<?php echo ($row['contact']) ?>
									</td>
									<td>
										<?php echo ($row['contact_person']) ?>
									</td>
									<td>	
										<?php echo ($row['emergency_contact']) ?>
									</td>
									<td>
										<a href="../governmentIdImages/<?php echo ($row['government_id']) ?>" target="_blank">View</a>
									</td>
									<td>
										<?php echo ($row['type']) ?>
									</td>
									<td class="">
										 <p> <b><?php echo $house['house_no'] ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo number_format($house['price'],2) ?></b></p>
									</td>
									<td class="text-center">
										<!--<button class="btn btn-sm btn-outline-primary view_payment" type="button" data-id="<?php echo $row['id'] ?>" >View</button>-->
										<button class="btn btn-sm btn-outline-primary edit_tenant" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
										<button class="btn btn-sm btn-outline-danger delete_tenant" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php unset($house); ?>
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
	
	$('#new_tenant').click(function(){
		uni_modal("New Room Tenant","manage_tenant.php","mid-large")
		
	})
	$('#new_bedspacer_tenant').click(function(){
		uni_modal("New Bedspacer Tenant","manage_bedspacer_tenant.php","mid-large")
		
	})

	$('.view_payment').click(function(){
		uni_modal("Tenants Payments","view_payment.php?id="+$(this).attr('data-id'),"large")
		
	})
	$('.edit_tenant').click(function(){
		uni_modal("Manage Tenant Details","manage_tenant.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_tenant').click(function(){
		_conf("Are you sure to delete this Tenant?","delete_tenant",[$(this).attr('data-id')])
	})
	
	function delete_tenant($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_tenant',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},500)

				}
			}
		})
	}
	
	function sendNotice(email, message, subject) {
		start_load();
		let httpRequest = new XMLHttpRequest();
	
		httpRequest.onreadystatechange = function() {
			if(this.readyState != 4) {
				return;
			}
			if(this.status != 200) {
				return;
			}
			if(this.responseText==1){
				alert_toast("Send Notice",'success')
				setTimeout(function(){
							location.reload()
				},1500)

			}
			else {
				alert_toast(this.responseText,'danger')
				setTimeout(function(){
							location.reload()
				},5000)
			}
		}
	
		httpRequest.open("POST", "sendNotice.php");
		httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		httpRequest.send("email="+email+"&message="+message+"&subject="+subject);
	}
</script>