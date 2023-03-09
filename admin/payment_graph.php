<?php
	$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
	$type = isset($_GET['type']) ? $_GET['type'] : 1;
?>
<style>
	.graph-container {
		text-align: center;
	}
	.graph {
		margin-top: 20px;
		max-width: 100%;
		height: auto;
	}
</style>
<form id="filter-report" class="mt-3">
	<div class="row form-group">
		<label class="control-label col-md-2 text-md-right">Year of: </label>
		<input type="number" name="year" class='from-control col-md-2' value="<?php echo ($year) ?>">
		<label class="control-label col-md-2 text-md-right">Report Type: </label>
		<select class="col-md-2" name="type">
			<option value="1" <?php echo $type == 1 ? "selected" : "" ?>>Room and Bedspacer Payments Report</option>
			<option value="2" <?php echo $type == 2 ? "selected" : "" ?>>Room Payments Report</option>
			<option value="3" <?php echo $type == 3 ? "selected" : "" ?>>Bedspacer Payments Report</option>
		</select>
		<button class="btn btn-sm btn-block btn-primary col-md-2 ml-md-5">Filter</button>
	</div>
</form>
<!--<iframe src="graph.php" class="graph"></iframe>-->
<div class="graph-container">
<img src="graph.php?type=<?php echo $type; ?>&year=<?php echo $year; ?>" class="graph">
</div>
<script>
$('#filter-report').submit(function(e){
		e.preventDefault()
		location.href = 'index.php?page=payment_graph&'+$(this).serialize()
	})
</script>