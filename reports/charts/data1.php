<?php 
include('dbcon.php');
header('Content-Type:application/json');
$query=mysqli_query($conn,"select institute_name,receive_count from institutions");

$date=array();
foreach($query as $row){
	$data[]=$row;
}

print json_encode($data);
?>
