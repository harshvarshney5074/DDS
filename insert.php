  <head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
		   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           
		    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
           <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>            
           <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />  
	
  </head>
  <?php
  function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
  <?php  
 include('dbcon.php');
 if(!empty($_POST))  
 {  
      $output = '';  
      $message = '';  
      $req_date =test_input($_POST["req_date"]);  
      $req_by =  test_input($_POST["req_by"]);
	  $req_by1 = explode("-",$req_by);
	$req_by2=$req_by1[0];
      $category =test_input($_POST["category"]);  
      $biblio_det = $_POST["name"]; 
	  $status = test_input($_POST["status"]); 	  
      $document_type =$_POST["document_type"]; 
	  $journal_name=$_POST["journal_name"];
      if($_POST["employee_id"] != '')  
      {  
          $query = "  
          UPDATE entry   
          SET Req_date='$req_date',   
          Req_by='$req_by',   
          Category='$category',   
          Bibliographic_details = '$biblio_det',   
          Journal_name = '$journal_name',
          Status='$status'
          WHERE id='".$_POST["employee_id"]."'";  
          $message = 'Data Updated';  
      }   
      else  
      {  
			$number = count($_POST["name"]);
			$ab=mysqli_query($conn,"select * from patrons where Sr_no='$req_by2'");
			$get=mysqli_fetch_array($ab);
			$re=$get['Email_id'];
			$name=$get['Display_name'];
			if(function_exists('date_default_timezone_set')) {
				date_default_timezone_set("Asia/Kolkata");
			}
			
			 $todaydate = date('Y-m-d'); 
			$get_prev_coun=mysqli_query($conn,"select * from entry where Req_by='$req_by2' and Req_date='$todaydate'");
			$get_pre=mysqli_num_rows($get_prev_coun);
			if($get_pre>0){
			$get_prev=mysqli_query($conn,"select max(order_id) as max_id from entry where Req_by='$req_by2' and Req_date='$todaydate'");
			
				$get_order=mysqli_fetch_array($get_prev);
				$order_id=$get_order['max_id'];
			}
			else{
			$order=mysqli_query($conn,"insert into orders (username,Display_name,date) value ('$re','$name','$req_date')");
			$order1=mysqli_query($conn,"select max(order_id) as order_id from orders");
			$row=mysqli_fetch_array($order1);
			$order_id=$row['order_id'];
			}
if($number >0)
{
for($i=0; $i<$number; $i++)
 {
	 $name=$_POST["name"][$i];
	 $document_type=$_POST["document_type"][$i];
	 $journal_name=$_POST["journal_name"][$i];
	
 
			
           $query =mysqli_query($conn,
		   "  
           INSERT INTO entry(Req_date, Req_by, Category,Bibliographic_details, Journal_name,Document_type,Status,order_id,entry_date)  
           VALUES('$req_date', '$req_by2', '$category', '$name','$journal_name', '$document_type','$status','$order_id',now())");  
           $message = 'Data Inserted';  
      } 
}	  
      if($query)  
      {  
           $output .= '<label class="text-success">' . $message . '</label>'; 
			
		   echo"<script>window.open('index.php','_self');</script>";
           
 }  
 }}
 ?>
 