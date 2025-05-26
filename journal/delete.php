  <?php  
 if(isset($_GET["delete_record"]))  
 {  
        
      include('dbcon.php'); 
	  $id=$_GET["delete_record"];
      
    ?>    
      <div class="table-responsive">  
           <table class="table table-bordered">  
				<p> Are you sure you want to delete?</p>
				
				<a href="delete_record.php?delete_record=<?php echo $id; ?>" class="btn btn-danger" role="button">Delete</a>
           </table>  
      </div>  
 <?php       
       
 }  
?>
 

