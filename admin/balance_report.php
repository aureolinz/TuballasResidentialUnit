<?php include 'db_connect.php' ?>
<style>
	.on-print{
		display: none;
	}
</style>
<noscript>
	<style>
		.text-center{
			text-align:center;
		}
		.text-right{
			text-align:right;
		}
		table{
			width: 100%;
			border-collapse: collapse
		}
		tr,td,th{
			border:1px solid black;
		}
		@page { size: auto;  margin: 0mm; }
	</style>
</noscript>
<div class="container-fluid">
	<div class="col-lg-12 mt-3">
		<div class="card">
			<div class="card-body">
				<div class="col-md-12">
					<hr>
						<div class="row">
							<div class="col-md-12 mb-2">
							<button class="btn btn-sm btn-block btn-success col-md-2 ml-1 float-right" type="button" id="print"><i class="fa fa-print"></i> Print</button>
							</div>
						</div>
					<div id="report">
						<div class="on-print">
							 <p><center>Rental Balances Report</center></p>
							 <p><center>As of <b><?php echo date('F ,Y') ?></b></center></p>
						</div>
						<div class="row table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>Tenant</th>
										<th>House #</th>
										<th>Monthly Rate</th>
										<th>Balance</th>
										<th>Last Payment</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$i = 1;
									// $tamount = 0;
									$tenants =$conn->query("SELECT t.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as name,h.house_no,h.price FROM tenants t inner join houses h on h.id = t.house_id where t.status = 1 order by h.house_no desc ");
									if($tenants->num_rows > 0):
									while($row=$tenants->fetch_assoc()):
										$balance = $conn->query("SELECT SUM(amount) AS a FROM payments WHERE tenant_id = {$row['id']} AND (status = 0 OR status = 1)");
										$balance = $balance->fetch_assoc()['a'];
										//die("SELECT payment_date FROM payments WHERE tenant_id = {$row['id']} AND status = 2 ORDER BY payment_date DESC");
										$last_payment = $conn->query("SELECT payment_date FROM payments WHERE tenant_id = {$row['id']} 
										AND status = 2 ORDER BY payment_date DESC")->fetch_assoc()['payment_date'];
									?>
									<tr>
										<td><?php echo $i++ ?></td>
										<td><?php echo ucwords($row['name']) ?></td>
										<td><?php echo $row['house_no'] ?></td>
										<td class="text-right"><?php echo number_format($row['price'],2) ?></td>
										<td class="text-right"><?php echo number_format(floatval($balance),2); ?></td>
										<td><?php echo $last_payment; ?></td>
									</tr>
								<?php endwhile; ?>
								<?php else: ?>
									<tr>
										<th colspan="9"><center>No Data.</center></th>
									</tr>
								<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#print').click(function(){
		var _style = $('noscript').clone()
		var _content = $('#report').clone()
		var nw = window.open("","_blank","width=800,height=700");
		nw.document.write(_style.html())
		nw.document.write(_content.html())
		nw.document.close()
		nw.print()
		setTimeout(function(){
		nw.close()
		},500)
	})
	$('#filter-report').submit(function(e){
		e.preventDefault()
		location.href = 'index.php?page=payment_report&'+$(this).serialize()
	})
</script>