<?php  
 if(isset($_POST["employee_id"]))  
 {  
        
      include('dbcon.php'); 
	  $id=$_POST["employee_id"];
      
?>    
     <div class="table-responsive">  
          <table class="table table-bordered">  
				<p> Are you sure you want to delete?</p>
				
				<a href="delete_record.php?delete_record=<?php echo $id;?>" class="btn btn-danger" role="button">Delete</a>
          </table>  
     </div>  
<?php       
       
 }  
?>
 

