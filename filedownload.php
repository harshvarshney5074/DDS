<?php 
include('dbcon.php');
$id=$_GET['download_file'];
$query=mysqli_query($conn,"select * from entry where Sr_no='$id'");
while($row=mysqli_fetch_array($query)){
	$path=$row['File_path'];
	header('content-Disposition: attachment; filename= '.$path.'');
	header('content-type: application/octent-stream');
	header('content-length='.filesize($path));
	readfile($path);

}

?>