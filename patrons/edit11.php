<?php

	include("dbcon.php");
	session_start();
	if(isset($_GET['edit_record'])){
		$get_id=$_GET['edit_record'];
		$query="select * from patrons where Sr_no='$get_id'";
		$get_pro=mysqli_query($conn,$query);
		
		while($row_pro=mysqli_fetch_array($get_pro)){
			$id=$row_pro['Sr_no'];
			$roll_no=$row_pro['Roll_no'];
			$display_name=$row_pro['Display_name'];
			$email_id=$row_pro['Email_id'];
			$discipline=$row_pro['Discipline'];
			$program_name=$row_pro['Program_name'];
			$status=$row_pro['Status'];
			
			
		}  
	 
	}
?>
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
 
 
<?php
  function trim_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>



 
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
    var x = document.forms["inst_form"]["email_id"].value;
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
        alert("Not a valid e-mail address");
        return false;
    }
}
</script> 
  <br /><br />
  <div class="container" style="width:600px;">
	<h1>Edit Record</h1>

   <form method="post" name="inst_form" action="" onsubmit="return validateForm();">
    <div class="form-group">
	
		<label  for="inputPassword">Roll No</label>
		
		<input type="text" id="inputPassword" name="roll_no" value="<?php echo $roll_no; ?>" placeholder="Institute Name"class="form-control" >
		
		<label  for="inputPassword">Display Name</label>
		
		<input type="text" id="inputPassword" name="display_name" value="<?php echo $display_name; ?>" placeholder="Email" class="form-control" >
		
		<label  for="inputPassword">Email Id</label>
		
		<input type="text" id="inputPassword" name="email_id" value="<?php echo $email_id; ?>" placeholder="Address" class="form-control" >
		
		<label  for="inputPassword">Discipline</label>
		
		<input type="text" id="inputPassword" name="discipline" value="<?php echo $discipline; ?>" placeholder="Address" class="form-control" >
		
		<label  for="inputPassword">Program Name</label>
		
		<input type="text" id="inputPassword" name="program_name" value="<?php echo $program_name; ?>" placeholder="Address" class="form-control" >
		
		<label>Status</label>
	 <select name="status" class="form-control">
		<option value="<?php echo $status; ?>"><?php echo $status; ?></option>
		<option value="Active">Active</option>
		<option value="Inactive">Inactive</option>
	 </select>
		
      
    </div>
    <div class="form-group">
     <input type="submit" class="btn btn-info" name="update" value="Update" />
    </div>
   </form>
   <br />
  </div>
 </body>
</html>

<?php 

	if(isset($_POST['update'])){
		
		$roll_no=trim_input($_POST['roll_no']);
		$display_name=trim_input($_POST['display_name']);
		$email_id=$_POST['email_id'];
		$discipline=$_POST['discipline'];
		$program_name=$_POST['program_name'];
		$status=$_POST['status'];
		
		$update_record=mysqli_query($conn,"update patrons set Roll_no='$roll_no',Display_name='$display_name',Email_id='$email_id',Discipline='$discipline',Program_name='$program_name',Status='$status' where Sr_no='$get_id'");
		
		if($update_record){
		echo"<script>alert('Data updated successfully')</script>";
		echo"<script>window.open('index.php','_self')</script>";
	}

	}
?>
