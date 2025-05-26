<!DOCTYPE html>
<html>
 <head>
  <title>DDS</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script> 

<script>tinymce.init({ selector:'textarea' });</script>
<style>
	body{
	
	align:center;
	}
</style>
</head>
<body>
<center>
<br/><br/>
<h1>Send mail</h1>
</center>
<div class="row">
<div class="col-md-3">
</div>
<div class="col-md-6">

<?php 
include('head1.php');
include("dbcon.php");
if(isset($_POST['submit'])){
	
	$del_attach=mysqli_query($conn,"truncate table attachments");
	$order_no=$_POST['order_no'];
	$useremail=$_POST['useremail'];
	
	$username=$_POST['username'];
	$body='Dear '.$username;
	$body.=', <p>Please find attached the requested article(s).</p>';
	foreach($_POST['send'] as $send_id){
	$send_m=mysqli_query($conn,"select * from entry where Sr_no='$send_id'");
	$row=mysqli_fetch_array($send_m);
	$file_path=$row['File_path'];
	$file_name=$row['File_name'];
	
	$attach=mysqli_query($conn,"insert into attachments (Send_id,File_name,File_path) values ('$send_id','$file_name','$file_path')");
	$biblo=$row['Bibliographic_details'];
	$body.='<ul><li>'.$biblo.'</li></ul>';;
        
	}
	$body.='<br/>';
    $check=mysqli_query($conn,"select * from entry where order_id='$order_no' and (Status='Pending' or Status='Approached')");
	
	$check_count=mysqli_num_rows($check);
	if($check_count>0){
		$body.='<p>The following remaining documents(s) we will arrange soon.</p>';
		$i=1;
		while($row1=mysqli_fetch_array($check)){
			$body.='<ul><li>'.$row1["Bibliographic_details"].'</li></ul>';
		}
        }

$body .='<br/>Regards,<br/>Library Services<br/>Indian Institute of Technology Gandhinagar<br/>Palaj | Gandhinagar - 382355 | Gujarat | INDIA<br/>Tel: + 91-079-2395 2099<br/>Email: <a href="mailto:libraryservices@iitgn.ac.in">libraryservices@iitgn.ac.in</a><br/>Website: <a href="http://www.iitgn.ac.in">http://www.iitgn.ac.in</a>
<br/>------------------------------------------------------------------------------------------------------------------------<br/>
For outside Libraries: 
<br/><font color="blue"><b>Copyright Guidelines:</b></font> <font color="red">Kindly note that the attached document(s) is/are being sent to you are meant to be used for the academic and research purpose only. These must be printed and given to the requested end user. Under no circumstances, this/these paper(s) is/are circulated electronically or hosted on a public system. All  these documents are copyrighted, hence we request for the compliance.</font>
<br/>For our own users & individuals  from outside:  
<br/><font color="blue"><b>Copyright Guidelines:</b></font> <font color="red">Kindly note that the attached document(s) is/are being sent to you are meant to be used for your academic and research purpose only. Under no circumstances, this/these paper(s) is/are circulated electronically or hosted on a public system. All  these documents are copyrighted, hence we request for the compliance.</font>
';      
    
}
?>

<form method="post" action="show4.php">
	<label  for="inputPassword">To:</label>
	<input type="text" class="form-control"id="req_by" name="To" value="<?php echo $useremail; ?>">
	<div class="row">
	<div class="col-md-6">

	<label  for="inputPassword">Cc:</label>
	<input type="text" class="form-control"id="req_by" name="Cc" >
	</div>
	<div class="col-md-6">
	<label  for="inputPassword">Bcc:</label>
	<input type="text" class="form-control"id="req_by" name="Bcc" >
	</div></div>
	<label  for="inputPassword">Subject:</label>
	<input type="text" class="form-control"id="req_by" value="Requested Papers" name="sub" >
	<label  for="inputPassword">Body:</label>
	<textarea name="Body" class="form-control" rows="15"><?php echo $body; ?></textarea>
	<input type="hidden" name="order_no" value="<?php echo $order_no; ?>">
	<br/><center>
	<input type="submit" class="btn btn-primary btn-block" value="Send"name="send1">
	<br/>
</form>
</div>
</div>