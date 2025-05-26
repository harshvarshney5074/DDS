<?php
include('dbcon.php'); 
		if(isset($_GET['delete_record'])){
		$delete_pro=$_GET['delete_record'];
		$inst_name=mysqli_query($conn,"select * from journal_list where S_no='$delete_pro'");
		$row=mysqli_fetch_array($inst_name);
		$int=$row['Journal_name'];
		
		$sel_pro=mysqli_query($conn,"delete from journal_list where S_no='$delete_pro'");
		
		
		if($sel_pro){
			
			echo "<script>window.open('index.php','_self')</script>";
			}
		
		
	 }
?>