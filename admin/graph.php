<?php
include 'db_connect.php';
$data = array();
for($counter = 1; $counter<=12; $counter++) {
	if($_GET['type'] == 1) {
		$qry = "SELECT SUM(amount) FROM payments WHERE status = 2 AND YEAR(payment_date) = {$_GET['year']} AND MONTH(payment_date) = $counter";
	}
	else if($_GET['type'] == 2) {
		$qry = "SELECT SUM(amount) FROM payments WHERE tenant_id IN (SELECT id FROM tenants WHERE type = 'Room') AND status = 2 AND YEAR(payment_date) = {$_GET['year']} AND MONTH(payment_date) = $counter";
	}
	else if($_GET['type'] == 3) {
		$qry = "SELECT SUM(amount) FROM payments WHERE tenant_id IN (SELECT id FROM tenants WHERE type = 'Bedspacer') AND status = 2 AND YEAR(payment_date) = {$_GET['year']} AND MONTH(payment_date) = $counter";
	}
	else {
		die();
	}
	$total = $conn->query($qry)->fetch_row()[0];
	array_push($data,isset($total) ? floatval($total) : 0 );
}
//Include necessary files to draw line chart

require_once ('jpgraph-4.4.1/src/jpgraph.php');

require_once ('jpgraph-4.4.1/src/jpgraph_line.php');

//Set the data

//$data = array(10,6,16,23,11,9,5);
$xdata = array("January", "February", "March", "April", "May", "June", 
"July", "August", "September", "October", "November", "December");

//Declare the graph object

$graph = new Graph(900,500);

//Clear all

$graph->ClearTheme();

//Set the scale

$graph->SetScale('textlin');

//Set the linear plot

$linept=new LinePlot($data);

//Set the line color

$linept->SetColor('green');
/*$linept->mark->SetType(MARK_FILLEDCIRCLE);
$linept->mark->SetColor('blue');
$linept->mark->SetFillColor('red');*/

//Add the plot to create the chart

$graph->Add($linept);

//

$graph->xaxis->SetTickLabels($xdata);

$graph->yaxis->HideZeroLabel();
$graph->ygrid->SetFill(true,'#FFF@0.8','#EEE@0.8');

$graph->title->Set("{$_GET['year']} Income Report");
$graph->title->SetFont(FF_VERDANA,FS_NORMAL, 12);
//$graph->yaxis->title->Set("Income");

$graph->SetMargin(50, 50, 50, 50);
//Display the chart

$graph->Stroke();

?>
