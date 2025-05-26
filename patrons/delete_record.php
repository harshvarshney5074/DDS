<?php
include('dbcon.php'); 
		if(isset($_GET['delete_record'])){
		$delete_pro=$_GET['delete_record'];
		$inst_name=mysqli_query($conn,"select * from patrons where Sr_no='$delete_pro'");
		$row=mysqli_fetch_array($inst_name);
		
		$sel_pro=mysqli_query($conn,"delete from patrons where Sr_no='$delete_pro'");
		
				
		
		if($sel_pro){
			
			echo "<script>window.open('index.php','_self')</script>";
			}
		
		
	 }
?>