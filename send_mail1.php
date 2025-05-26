<?php 
require 'PHPMailer-master/PHPMailerAutoload.php';
include("dbcon.php");
if(isset($_POST['send_inst'])){
	$body=$_POST['Body'];
	$Cc=$_POST['Cc'];
	$Bcc=$_POST['Bcc'];
	$sub=$_POST['sub'];
	$number = count($_POST["framework1"]);
	$get_inst=mysqli_query($conn,"select * from send_instentry");
	
if($number > 0)
{
while($row=mysqli_fetch_array($get_inst)){
	$entry_id=$row['entry_id'];
	$request_date=$row['req_date'];
 for($i=0; $i<$number; $i++)
 {
	 $name=$_POST["framework1"][$i];
	 
 
 $query = mysqli_query($conn,"INSERT INTO institute_list(institute_name,entry_id,req_date) VALUES ('$name','$entry_id','$request_date')");
 
 
 
}
$update_status=mysqli_query($conn,"update entry set Status='Approached' where Sr_no='$entry_id'");
}

for($i=0; $i<$number; $i++)
 {
	 $name=$_POST["framework1"][$i];
	 
 
 
 $get_email=mysqli_query($conn,"select email from institutions where Sr_no='$name'");
 $k1=mysqli_fetch_array($get_email);
 $email=$k1['email'];
 sendEmail($email, $body,$Cc,$Bcc,$sub);
 
}
}

}
function sendEmail($email,$body,$Cc,$Bcc,$sub){

$mail = new PHPMailer;
                          // Enable verbose debug output

$mail->isSMTP(); 
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);                                     // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'libraryservices@iitgn.ac.in';                 // SMTP username
$mail->Password = 'IITGN@321';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('libraryservices@iitgn.ac.in', 'Library Services');
$mail->addAddress($email,'');     // Add a recipient
$mail->addCC($Cc,'');
$mail->addBCC($Bcc,'');

//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = $sub;
$mail->Body    = $body;


if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
	
    echo '<script>alert("Message has been sent");</script>';
	echo"<script>window.open('index.php','_self')</script>";

}

}
   