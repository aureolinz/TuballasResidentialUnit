<?php 
include 'db_connect.php'; 
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM payments where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
    $$k=$val;
}
}
?>
<div class="container-fluid">
    <form action="" id="manage-payment" onsubmit="addPayment(); return false;">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg"></div>
        <div class="form-group">
            <label for="" class="control-label">Tenant</label>
            <select name="tenant_id" id="tenant_id" class="custom-select select2" onchange="getDuedate(this.value); getDownpayment(this.value)">
                <option value=""></option>

            <?php 
            $tenant = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM tenants where status = 1 order by name asc");
            while($row=$tenant->fetch_assoc()):
            ?>
            <option value="<?php echo $row['id'] ?>" <?php echo isset($tenant_id) && $tenant_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['name']) ?></option>
            <?php endwhile; ?>
            </select>
        </div>
		<div class="form-group">
            <label for="payment-type" class="control-label">Payment Type</label>
            <input type="text" class="form-control" name="payment-type" id="payment-type" value="Rent">
        </div>
        <div class="form-group" id="details">
            
        </div>

        <div class="form-group">
            <label for="due-date" class="control-label">Due Date</label>
            <input type="date" class="form-control" name="due-date" id="due-date">
        </div>
        <div class="form-group">
            <label for="amount" class="control-label">Amount to pay: </label>
            <input type="number" class="form-control text-right" step="any" name="amount" id="amount">
        </div>
</div>
    </form>
</div>
<div id="details_clone" style="display: none">
    <div class='d'>
        <large><b>Details</b></large>
        <hr>
        <p>Tenant: <b class="tname"></b></p>
        <p>Monthly Rental Rate: <b class="price"></b></p>
        <p>Outstanding Balance: <b class="outstanding"></b></p>
        <p>Total Paid: <b class="total_paid"></b></p>
        <p>Rent Started: <b class='rent_started'></b></p>
        <p>Payable Months: <b class="payable_months"></b></p>
        <hr>
    </div>
</div>
<script>
    $(document).ready(function(){
        if('<?php echo isset($id)? 1:0 ?>' == 1)
             $('#tenant_id').trigger('change') 
    })
	
	function getDuedate(tenantId, duedateElement = 'due-date') {
		let httpRequest = new XMLHttpRequest();
	
		httpRequest.onreadystatechange = function() {
			if(this.readyState != 4) {
				return;
			}
			if(this.status != 200) {
				return;
			}
			document.getElementById(duedateElement).value = this.responseText;
		}
	
		httpRequest.open("POST", "getDuedate.php");
		httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		httpRequest.send("tenantId="+encodeURIComponent(tenantId));
	}
	
	function getDownpayment(houseNo,downpaymentElement = 'amount') {
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
	httpRequest.send("houseNo="+encodeURIComponent(houseNo));
}
 
</script>