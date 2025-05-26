<?php
include('dbcon.php');
 if(!empty($_POST)){
	$inst=$_POST['institute_name'];
	$email=$_POST['email'];
	$add=$_POST['address'];
	$check=mysqli_query($conn,"select * from institutions where institute_name='$inst'");
	$count=mysqli_num_rows($check);
	if($count>=1){
		echo"<script>alert('This entry already exists.')</script>";
	}
	
	else{
	$sql=mysqli_query($conn,"insert into institutions (institute_name,email,address) value ('$inst','$email','$add')");
	if($sql){
		echo"<script>alert('Successfully inserted')</script>";
		echo"<script>window.open('index.php','_self')</script>";
	}
	}
}

?>
