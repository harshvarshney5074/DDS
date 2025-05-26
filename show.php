<html>  
 <!DOCTYPE html>  
 
      <head>  
           <title>DDS</title>  
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
		   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script> 
		    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>  
           <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>            
           <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" />  
		   <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css" /> 
		   <script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
		   <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> 
		   <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
		   <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
		   <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
		   <link href="css/select2.min.css" rel="stylesheet">
			<!-- Include Date Range Picker -->
			<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
		<style>		
	<style>
	.table{
		width:50%;
	}
	
	
	</style>
 </head> 
 <body>  
<?php
require('PHPMailer-master/PHPMailerAutoload.php');
include('dbcon.php');
include('head1.php');
if(isset($_GET['order_no'])){
	$order_no=$_GET['order_no'];
	$user=mysqli_query($conn,"select * from orders where order_id='$order_no'");
	$row=mysqli_fetch_array($user);
	$user_email=$row['username'];
	
	$username=$row['Display_name'];
	$sql=mysqli_query($conn,"select * from entry where order_id='$order_no'");
	$num=mysqli_num_rows($sql);
	$sql1=mysqli_query($conn,"select * from entry where order_id='$order_no' and Status!='Complete'");
	$num1=mysqli_num_rows($sql1);
?>
<div class="container">
<div class="container">
<br/><br/><br/>
<center>
<h3>Basket Number-<?php echo"$order_no";?></h3>
<form action="show3.php" method="post">
	<table class="table table-bordered">
		<tr>
		<td>Send</td>
		<td>Sr_no</td>
		<td>Bibliographic Details</td>
		<td>Status</td>	
		</tr>
		<?php 
		while($row=mysqli_fetch_array($sql)){
			?>
			<tr>
			<td><input type="checkbox" name="send[]" value="<?php echo $row['Sr_no'];?>"></td>
			<td><?php echo $row['Sr_no'];?></td>
			<td><?php echo $row['Bibliographic_details'];?></td>
			<td><?php echo $row['Status'];?></td>
			</tr>
			<?php
}}
		?>
	</table>
	<input type="hidden" value="<?php echo $order_no; ?>" name="order_no">
	<input type="hidden" value="<?php echo $user_email; ?>" name="useremail">
	<input type="hidden" value="<?php echo $username; ?>" name="username">
	<input type="submit" value="submit" name="submit">
	</form>
<?php 

		?>