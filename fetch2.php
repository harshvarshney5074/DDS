<?php
//fetch.php
include('dbcon.php');
$request = mysqli_real_escape_string($conn, $_POST["query"]);
$query = "
 SELECT * FROM patrons WHERE Display_name LIKE '%".$request."%' or Email_id LIKE '%".$request."%'
";

$result = mysqli_query($conn, $query);

$data = array();

if(mysqli_num_rows($result) > 0)
{
 while($row = mysqli_fetch_assoc($result))
 {
  $data[] = $row["Sr_no"].'-'.$row["Display_name"].'('.$row["Email_id"].')';
  
 }
 echo json_encode($data);
}

?>
