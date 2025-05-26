<?php 
include('dbcon.php');
header('Content-Type:application/json');
$query=mysqli_query($conn,"select Program_name,count(*) as count from entry,patrons where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and entry.Req_by=patrons.Sr_no  group by Program_name	order by Program_name");

$date=array();
foreach($query as $row){
	$data[]=$row;
}

print json_encode($data);
?>
