<?php 
include('dbcon.php');
header('Content-Type:application/json');
$query=mysqli_query($conn,"select Discipline,count(*) as count from entry,patrons where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and entry.Req_by=patrons.Sr_no  group by Discipline order by Discipline");

$date=array();
foreach($query as $row){
	$data[]=$row;
}

print json_encode($data);
?>
