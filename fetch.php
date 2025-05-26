 <?php  
 //fetch.php  
 include('dbcon.php');
 if(isset($_POST["employee_id"]))  
 {  
      $result = mysqli_query($conn,"SELECT * FROM entry WHERE Sr_no = '".$_POST["employee_id"]."'");  
        
      $row = mysqli_fetch_array($result);  
      echo json_encode($row);  
 }  
 ?>
 