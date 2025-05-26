<?php
include('dbcon.php'); 
		if(isset($_GET['delete_record'])){
		$delete_pro=$_GET['delete_record'];
		$sel_pro=mysqli_query($conn,"delete from user where user_id='$delete_pro'");
		
		
		if($sel_pro){
			
			echo "<script>window.open('index.php','_self')</script>";
			}
		
		
	 }
?>