<?php
$date1 = new DateTime("2010-07-06");
$date2 = new DateTime("2010-07-09");

$diff = $date2->diff($date1)->format("%a");
echo "$diff";
?>

<?php 
include('dbcon.php');
$sql=mysqli_query($conn,"SELECT DATEDIFF(`Sent_date`, `entry_date`) as diff from entry where Sr_no=47");
$row=mysqli_fetch_array($sql);
echo "$row[diff]";