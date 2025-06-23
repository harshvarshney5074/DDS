<?php
include('dbcon.php');
$request = mysqli_real_escape_string($conn, $_POST["query"]);
$query = "
 SELECT * FROM journal_list WHERE Journal_name LIKE '%".$request."%'
";

$result = mysqli_query($conn, $query);

$data = array();

if(mysqli_num_rows($result) > 0)
{
 while($row = mysqli_fetch_assoc($result))
 {
  $data[] = $row["Journal_name"].'('.$row["Pub_name"].')';
  
 }
 echo json_encode($data);
}

?>
