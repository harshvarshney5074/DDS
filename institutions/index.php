<?php  
//index.php
session_start();
include('dbcon.php');
include('checkUser.php');
$query = "SELECT * FROM institutions";
$result = mysqli_query($conn, $query);
 ?>  
<!DOCTYPE html> 
<html>  
 <head>  
  <title>DDS</title>  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
		   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
          <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>  
           <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>            
           <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" />  
		   <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css" /> 
		   <script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
		   <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> 
		   <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
		   <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
		   <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
 </head> 
 <body> 
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="../index.php">IITGN</a>
    </div>
    <ul class="nav navbar-nav ">
      
	  <?php 
	   if($_SESSION['type']=='0' || $_SESSION['type']=='1' ){
		   ?>
      		<li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Manage
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
			<li><a href="../institutions/index.php">Institutions</a></li>
          
          <li><a href="../journal/index.php">Journals</a></li>
		  <li><a href="../patrons/index.php">Patrons</a></li>
		  
          
        </ul>
      </li>
	  <li><a href="../biblo_search1.php">Search</a></li>
	  <li><a href="../orders.php">Orders</a></li>
	   <?php } ?>
	  <li><a href="../reports/index.php">Reports</a></li>
      <?php
		if($_SESSION['type']=='0'){
			echo"<li><a href='../users/index.php'>Settings</a></li>";
		}

	  ?>
    </ul>
   
       <ul class="nav navbar-nav navbar-right">
      <?php
	  if(isset($_SESSION['uid'])){
	  echo "<li><a href='#'>User ".$_SESSION['uid']."</a></li>";
      echo"<li><a href='../logout.php'><span class='glyphicon glyphicon-log-in'></span> Logout</a></li>";
	  }
	  ?>
    </ul>
      </div>
   
  </div>
</nav>
 
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
  <div class="container" style="width:800px;">  
   <h3 align="center">Institute List</h3>  
   <br />  
   <div class="table-responsive">
    <div align="right">
     <button type="button" name="age" id="age" data-toggle="modal" data-target="#add_data_Modal" class="btn btn-warning">Add</button>
    </div>
    <br />
    <div id="employee_table">
     <table id="employee_data" class="table table-hover table-striped table-bordered"> 
	<thead>
      <tr>
       <th>Institution Name</th>  
       <th>Email</th>
	   <th>Status</th>
	   <th>Remarks</th>
	   <th>Edit/Delete</th>
      </tr>
	  </thead>
      <?php
      while($row = mysqli_fetch_array($result))
      {
      ?>
      <tr>
       <td><?php echo $row["institute_name"]; ?></td>
       <td><?php echo $row["email"]; ?></td>
	   <td><?php echo $row["Status"]; ?></td>
	   <td><?php echo $row["Remarks"]; ?></td>
	   <td><a href="edit11.php?edit_record=<?php echo $row["Sr_no"]; ?>" class="btn btn-info btn-xs " />Edit</a>
	   <input type="button" name="delete" value="delete" id="<?php echo $row["Sr_no"]; ?>" class="btn btn-info btn-xs delete_data" /></td>
	   </tr>
      <?php
      }
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
    <h4 class="modal-title">New Entry</h4>
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
     
	<label>Status</label>
	 <select name="status" class="form-control">
		<option value="Active">Active</option>
		<option value="Inactive">Inactive</option>
	 </select>
	 <label>Remarks</label>
     <input type="text" name="remarks" id="address" class="form-control"/>
	 
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
			
			"stateSave":true,
			"aaSorting": [[0, 'asc']],
			 dom: 'Bfrtip',
        buttons: [
           
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
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
include('dbcon.php');
 if(!empty($_POST)){
	$inst=$_POST['institute_name'];
	$email=$_POST['email'];
	$add=$_POST['address'];
	$status=$_POST['status'];
	$remarks=$_POST['remarks'];
	$check=mysqli_query($conn,"select * from institutions where institute_name='$inst'");
	$count=mysqli_num_rows($check);
	if($count>=1){
		echo"<script>alert('This entry already exists.')</script>";
	}
	
	else{
	$sql=mysqli_query($conn,"insert into institutions (institute_name,email,address,Status,Remarks) values ('$inst','$email','$add','$status','$remarks')");
	if($sql){
		echo"<script>alert('Successfully inserted')</script>";
		echo"<script>window.open('index.php','_self')</script>";
	}
	}
}

?>