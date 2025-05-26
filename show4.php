<html>  
 <head>  
  <title>DDS</title>  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
		   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           
		    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
           <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>            
           <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />  

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
if(isset($_POST['send1'])){
		$order_no=$_POST['order_no'];
		$to=$_POST['To'];
		$cc=$_POST['Cc'];
		$bcc=$_POST['Bcc'];
		$sub=$_POST['sub'];
		$body=$_POST['Body'];	
$m=new PHPMailer;
$m->isSMTP();

$m->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);                                     // Set mailer to use SMTP

$m->SMTPAuth=true;


$m->Host='smtp.gmail.com';
$m->Username='libraryservices@iitgn.ac.in';
$m->Password='IITGN@321';
$m->SMTPSecure='tls';
$m->Port=587;
$m->From='libraryservices@iitgn.ac.in';
$m->FromName='Library Services';
//$m->addReplyTo('jagritibajpai2606@gmail.com','Reply Address');

		
$m->addAddress($to,'');
$m->addCC($cc,'');
$m->addBCC($bcc,'');
$m->isHTML(true);
$m->Body=$body;
$count=0;

$get_attach=mysqli_query($conn,"select * from attachments");
while($row=mysqli_fetch_array($get_attach)){
	$file_path=$row['File_path'];
	$file_name=$row['File_name'];
	$maxsize=24*1024*1024;
	if(filesize($file_name)>$maxsize){
		$count++;
	}
	
	
	$m->addAttachment($file_path,$file_name);
}



$m->Subject=$sub;

if($count>0){
	echo"<script>alert('File size greater than 25 MB');</script>";
	echo"<script>window.open('orders.php','_self');</script>";
	
}

else{
if($m->send()){
	$get_status=mysqli_query($conn,"select * from attachments");
	while($fet1=mysqli_fetch_array($get_status)){
		$send_id=$fet1['Send_id'];
		$update=mysqli_query($conn,"update entry set Status='Complete',Sent_date=now() where Sr_no='$send_id' and Status='Received'");
		
	}
	$count=mysqli_query($conn,"select * from entry where order_id='$order_no' and (Status='Pending' or Status='Approached')");
	$count1=mysqli_num_rows($count);
	if($count1>=1)
		$sent=mysqli_query($conn,"update orders set sent='2' where order_id='$order_no'" );
	else
		$sent=mysqli_query($conn,"update orders set sent='1' where order_id='$order_no'" );

	echo"<script>alert('Mail sent successfully $order_no');</script>";
	echo"<script>window.open('orders.php','_self');</script>";
}
else{
	echo $m->ErrorInfo;
}
}
}