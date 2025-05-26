<?php 
include('dbcon.php');
header('Content-Type:application/json');
$query=mysqli_query($conn,"select Journal_name,Count from report_journal");

$data=array();
foreach($query as $row){
	$data[]=$row;
}

print json_encode($data);
?>
