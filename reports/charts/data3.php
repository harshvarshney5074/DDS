<?php 
include('dbcon.php');
header('Content-Type:application/json');
$query=mysqli_query($conn,"select Document_type,count(*) as count from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)group by Document_type");

$date=array();
foreach($query as $row){
	$data[]=$row;
}

print json_encode($data);
?>
