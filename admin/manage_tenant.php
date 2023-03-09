<?php 
include 'db_connect.php'; 
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM tenants where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
	$$k=$val;
}
}
?>
<div class="container-fluid">
	<form action="" id="manage-tenant" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="row form-group">
			<div class="col-md-4">
				<label for="" class="control-label">Last Name</label>
				<input type="text" class="form-control" name="lastname"  value="<?php echo isset($lastname) ? $lastname :'' ?>" required>
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">First Name</label>
				<input type="text" class="form-control" name="firstname"  value="<?php echo isset($firstname) ? $firstname :'' ?>" required>
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Middle Name</label>
				<input type="text" class="form-control" name="middlename"  value="<?php echo isset($middlename) ? $middlename :'' ?>">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-4">
				<label for="" class="control-label">Suffix</label>
				<input type="text" class="form-control" name="suffix"  value="<?php echo isset($suffix) ? $suffix :'' ?>">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Email</label>
				<input type="email" class="form-control" name="email"  value="<?php echo isset($email) ? $email :'' ?>" required>
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Contact #</label>
				<input type="text" class="form-control" name="contact"  value="<?php echo isset($contact) ? $contact :'' ?>" required>
			</div>
			
		</div>
		<div class="form-group row">
			<div class="col-md-4">
				<label for="" class="control-label">Government ID <?php echo isset($id) ? '(Leave Blank if not changing.)' : '' ?></label>
				<input type="file" class="" name="government_id" required>
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Contact Person</label>
				<input type="text" class="form-control" name="contact_person"  value="<?php echo isset($contact_person) ? $contact_person :'' ?>">
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Emergency Contact</label>
				<input type="text" class="form-control" name="emergency_contact"  value="<?php echo isset($emergency_contact) ? $emergency_contact :'' ?>">
			</div>
		</div>
		<?php if(!isset($id)): ?>
		<div class="form-group row">
			<div class="col-md-4">
				<input type="hidden" name="type"  value="Room">
				<label for="" class="control-label">Room</label>
				<select name="house_id" id="" class="custom-select select2"  onchange="getDownpayment(this.value)">
					<option value=""></option>
					<?php 
					$house = $conn->query("SELECT * FROM houses where id not in (SELECT house_id from tenants where status = 1 AND type = 'Room') ".(isset($house_id)? " or id = $house_id": "" )." ");
					while($row= $house->fetch_assoc()):
					?>
					<option value="<?php echo $row['id'] ?>" <?php echo isset($house_id) && $house_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['house_no'] ?></option>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="col-md-4">
				<label for="downpayment" class="control-label">Downpayment</label>
				<input type="number" class="form-control" name="downpayment" value="" id="downpayment" required>
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">Registration Date</label>
				<input type="date" class="form-control" name="date_in"  value="<?php echo isset($date_in) ? date("Y-m-d",strtotime($date_in)) :'' ?>" required>
			</div>
		</div>
		<?php endif; ?>
	</form>
</div>
<script>
	
	$('#manage-tenant').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_tenant',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully saved.",'success')
						setTimeout(function(){
							location.reload()
						},500)
				} else if(resp==2) {
					alert_toast("Email already taken!",'danger')
						setTimeout(function(){
							location.reload()
						},1000)
				} else {
					//alert_toast("Data not saved.",'danger')
					alert_toast(resp,'danger')
						setTimeout(function(){
							location.reload()
						},5000)
				}
			}
		})
	})
	
	function getDownpayment(houseId,downpaymentElement = 'downpayment') {
	let httpRequest = new XMLHttpRequest();
	
	httpRequest.onreadystatechange = function() {
		if(this.readyState != 4) {
			return;
		}
		if(this.status != 200) {
			return;
		}
		document.getElementById(downpaymentElement).value = this.responseText;
	}
	
	httpRequest.open("POST", "getDownpayment.php");
	httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	httpRequest.send("houseId="+encodeURIComponent(houseId));
}
</script>