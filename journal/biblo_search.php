<?php  
//index.php
session_start();
include('dbcon.php');
include('checkUser.php');

 ?>  
<!DOCTYPE html> 
<?php 
include('head.php');
?> 
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

 </head> 
 <body>  
<script>
function validateForm() {
    var x = document.forms["inst_form"]["email"].value;
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
        alert("Not a valid e-mail address");
        return false;
    }
}
</script> 
 
  <br /><br />  
  <div class="container" >  
   <h3 align="center">Institute List</h3>  
   <br />  
   <form method="post" action="">
   <div class="row">
   <div class="col-md-11">
   <textarea class="form-control" rows="3" name="biblo"></textarea>
   </div>
   <div class="col-md-1">
	<input type="submit" class="btn btn-primary"value="submit" name="submit">
	</div></div>
   </form>
   <?php 
   if(isset($_POST['submit'])){
	   $biblo=$_POST['biblo'];
	   $query=mysqli_query($conn,"select * from entry where Bibliographic_details like '$biblo%'");
	   
	 ?>
   <div class="table-responsive">
    <div align="right">
     <button type="button" name="age" id="age" data-toggle="modal" data-target="#add_data_Modal" class="btn btn-warning">Add</button>
    </div>
    <br />
    <div id="employee_table">
     <table id="employee_data" class="table table-hover table-striped table-bordered"> 
		<thead>
                                <tr class="info">  
                                   <th>Sr_no</th>
                                    <th>Request Date</th>
									<th>Request By</th>
									<th>Category</th>
									<th>Bibliographic Details</th>
									<th>Document Type</th>
									<th>Status</th>
									<th>Edit/view/delete</th>
									<th><i class='fa fa-download fa-lg' aria-hidden='true'></i></th>
									

									
									
                               </tr>  
							   </thead>
                               <?php  
                               while($row = mysqli_fetch_array($query))  
                               {  
                               
                              
                            $id=$row['Sr_no'];
							$file=$row['File_name'];
							
						?>
 			<tr>
				<td><?php echo $row['Sr_no']; ?></td>
				<td><?php echo $row['Req_date']; ?></td>
				<td><?php echo $row['Req_by']; ?></td>
				<td><?php echo $row['Category']; ?></td>
				<td><?php echo $row['Bibliographic_details']; ?></td>
				<td><?php echo $row['Document_type']; ?></td>
				<?php 
				$sta=$row['Status'];
				
					if($sta=='Pending'){
					?>
						<td><img src="image/red-dot.png" width="15px" height="15px"/></td>
					<?php } ?>
					<?php 
					if($sta=='Approached'){
					?>
						<td><img src="image/yellow-dot.png" width="15px" height="15px"/></td>
						<?php } ?>
					<?php
					if($sta=='Complete'){
					?>
						<td><img src="image/green-dot.png" width="15px" height="15px"/></td>
						<?php } ?>
					<?php
					if($sta=='Closed'){
					?>
						<td><img src="image/blue-dot.png" width="15px" height="15px"/></td>
						<?php } ?>
				
				
                                    <td>
									
									<!--<input type="button" name="edit" value="Edit" id="<?php echo $row["Sr_no"]; ?>" class="btn btn-info btn-xs edit_data123" />  -->
									<?php
								  if($_SESSION['type']=='0' || $_SESSION['type']=='1' ){
									  ?>
									<a href="edit_final.php?edit_record=<?php echo $row["Sr_no"]; ?>" class="btn btn-info btn-xs">Edit</a>
									
									<input type="button" name="view" value="view" id="<?php echo $row["Sr_no"]; ?>" class="btn btn-info btn-xs view_data" />
									<input type="button" name="delete" value="delete" id="<?php echo $row["Sr_no"]; ?>" class="btn btn-info btn-xs delete_data" />
								<?php 
								  }
								  else{
								?>
									 <input type="button" name="view" value="view" id="<?php echo $row["Sr_no"]; ?>" class="btn btn-info btn-xs view_data" /> 
								 <?php } ?>
								 </td>
									<td>
						
				<?php 
				if($file!=""){
					echo"<a href='filedownload.php?download_file=$id'><i class='fa fa-download fa-lg' aria-hidden='true'></i></a>";
				}
				else{
					echo"<a href='send_mail.php?file_no=$id'>mail</a>";
				}
				
				?>
				</td>									
							  </tr>  
                               <?php  
   } } 
                               ?>  
                          </table>  
    </div>
   </div>  
  </div>
 </body>  
</html>  

<div id="add_data_Modal" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">PHP Ajax Insert Data in MySQL By Using Bootstrap Modal</h4>
   </div>
   <div class="modal-body">
    <form name="inst_form" method="post" id="insert_form" onsubmit="return validateForm();">
     <label>Institute Name</label>
     <input type="text" name="institute_name" id="institute_name" class="form-control"  required/>
     <br />
     <label>Email</label>
     <input type="text" name="email" id="email" class="form-control" required />
     <br />
	 <label>Address</label>
     <input type="text" name="address" id="address" class="form-control"  required/>
     <br />
     <input type="submit" name="insert" id="insert" value="Insert" class="btn btn-success" />

    </form>
   </div>
   <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
   </div>
  </div>
 </div>
</div>


<div id="dataModal" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Institute Name</h4>
   </div>
   <div class="modal-body" id="employee_detail">
    
   </div>
   <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
   </div>
  </div>
 </div>
</div>
<script>
$(document).ready(function(){  
      $('#employee_data').DataTable({
			
} );	  
}); 
	  $(document).on('click', '.delete_data', function(){  
           var institute_id = $(this).attr("id");  
           if(institute_id != '')  
           {  
                $.ajax({  
                     url:"delete.php",  
                     method:"POST",  
                     data:{institute_id:institute_id},  
                     success:function(data){  
                          $('#employee_detail').html(data);  
                          $('#dataModal').modal('show');  
                     }  
                });  
           }            
      });  

	  	  $(document).on('click', '.edit_data', function(){  
           var edit_record = $(this).attr("id");  
           if(edit_record != '')  
           {  
                $.ajax({  
                     url:"edit.php",  
                     method:"POST",  
                     data:{edit_record:edit_record},  
                     success:function(data){  
                          $('#employee_detail').html(data);  
                          $('#dataModal').modal('show');  
                     }  
                });  
           }            
      });  


</script>
<?php
/*
include('dbcon.php');
 if(!empty($_POST)){
	$inst=$_POST['institute_name'];
	$email=$_POST['email'];
	$add=$_POST['address'];
	$check=mysqli_query($conn,"select * from institutions where institute_name='$inst'");
	$count=mysqli_num_rows($check);
	if($count>=1){
		echo"<script>alert('This entry already exists.')</script>";
	}
	
	else{
	$sql=mysqli_query($conn,"insert into institutions (institute_name,email,address) value ('$inst','$email','$add')");
	if($sql){
		echo"<script>alert('Successfully inserted')</script>";
		echo"<script>window.open('index.php','_self')</script>";
	}
	}
}

?>
*/