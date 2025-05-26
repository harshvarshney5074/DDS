<?php  
//index.php
session_start();
include('dbcon.php');
$query = "SELECT * FROM user";
$result = mysqli_query($conn, $query);
 ?>  
<!DOCTYPE html> 
<?php 

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

 
  <br /><br />  
  <div class="container" style="width:800px;">  
   <h3 align="center">User Details</h3>  
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
       <th>Username</th>  
       <th>Usertype</th>
		<th>Last Login</th>
	   <th>Edit/Delete</th>
      </tr>
	  </thead>
      <?php
      while($row = mysqli_fetch_array($result))
      {
		  $utype=$row["user_type"];
		  	if($utype=="0")
				$uval="Admin";
			else if($utype=="1")
				$uval="User";
			else
				$uval="Normal User";
		 
      ?>
      <tr>
       <td><?php echo $row["username"]; ?></td>
       <td><?php echo $uval; ?></td>
	   <td><?php echo $row["last_login"]; ?></td>
	   <td><a href="edit11.php?edit_record=<?php echo $row["user_id"]; ?>" class="btn btn-info btn-xs " />Change</a>
	   <input type="button" name="delete" value="delete" id="<?php echo $row["user_id"]; ?>" class="btn btn-info btn-xs delete_data" /></td>
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
    <h4 class="modal-title">User Details</h4>
   </div>
   <div class="modal-body">
    <form name="inst_form" method="post" id="insert_form" onsubmit="return validateForm();">
     <label>UserName</label>
     <input type="text" name="user_name" id="user_name" class="form-control"  required/>
     <br />
     <label>UserType</label>
    
	 <select name="user_type" id="user_type" class="form-control"> 

                        <option value="0">Admin</option>  
                        <option value="1">User</option> 
						<option value="2">Normal User</option> 
		</select> 
     <br />
	 <label>Password</label>
     <input type="password" name="password" id="password" class="form-control"  required/>
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
    <h4 class="modal-title">User Details</h4>
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

	  $(document).on('click', '.delete_data', function(){  
           var user_id = $(this).attr("id");  
           if(user_id != '')  
           {  
                $.ajax({  
                     url:"delete.php",  
                     method:"POST",  
                     data:{user_id:user_id},  
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
	$uname=$_POST['user_name'];
	$utype=$_POST['user_type'];

	$password=md5($_POST['password']);
	$check=mysqli_query($conn,"select * from user where username='$uname'");
	$count=mysqli_num_rows($check);
	if($count>=1){
		echo"<script>alert('This username already exists.')</script>";
	}
	
	else{
	$sql=mysqli_query($conn,"insert into user (username,user_type,password) value ('$uname','$utype','$password')");
	if($sql){
		echo"<script>alert('Successfully added')</script>";
		echo"<script>window.open('index.php','_self')</script>";
	}
	}
}

?>