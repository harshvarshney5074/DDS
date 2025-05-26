<?php

	include("dbcon.php");
	include("head.php");
	if(isset($_GET['edit_record'])){
		$get_id=$_GET['edit_record'];
		$query="select * from user where user_id='$get_id'";
		$get_pro=mysqli_query($conn,$query);
		
		while($row_pro=mysqli_fetch_array($get_pro)){
			$id=$row_pro['user_id'];
			$uname=$row_pro['username'];
			$user_type=$row_pro['user_type'];
			$pass=$row_pro['password'];
			
			
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
  <br /><br />
  <div class="container" style="width:600px;">
	<h1>Edit User Details</h1>

   <form method="post" name="inst_form" action="" onsubmit="return validateForm();">
    <div class="form-group">
	
		<label  for="inputPassword">UserName</label>
		
		<input type="text" id="inputPassword" name="user_name" value="<?php echo $uname; ?>" placeholder="User Name"class="form-control" disabled>
		
		<label  for="inputPassword">User Type</label>
		
		<?php 
		if($user_type==0){
			$user_val="Admin";
		}
		else if($user_type==1)
			$user_val="User";
		else
			$user_val="Normal User";
		
		
		?>
		<select name="utype" id="utype" class="form-control"> 
								<option value="<?php echo $user_type; ?>"><?php echo $user_val; ?></option> 
                               <option value="0">Admin</option>  
                               <option value="1">User</option> 
								<option value="2">Normal User</option> 
		</select> 
		<label  for="inputPassword">Enter New Password</label>
		
		<input type="password" id="inputPassword" name="pass"  placeholder="New Password" class="form-control" >
		
		<label  for="inputPassword">Confirm Password</label>
		
		<input type="password" id="inputPassword" name="con_pass"  placeholder="Confirm Password" class="form-control" >
		
      
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
		
		
		$utype=trim_input($_POST['utype']);
		$pass=$_POST['pass'];
		$pass1=md5($_POST['pass']);
		$con_pass=$_POST['con_pass'];
		if($pass==""){
			$update_record=mysqli_query($conn,"update user set user_type='$utype' where user_id='$get_id'");
			if($update_record){
			echo"<script>alert('Data updated successfully')</script>";
			echo"<script>window.open('index.php','_self')</script>";
		}
		}
		else if($pass==$con_pass){
		$update_record=mysqli_query($conn,"update user set user_type='$utype',password='$pass1' where user_id='$get_id'");
		if($update_record){
		echo"<script>alert('Data updated successfully')</script>";
		echo"<script>window.open('index.php','_self')</script>";
		}
		}
		else{
			echo"<script>alert('Password and Confirm password are not same')</script>";
		}
	

	}
?>
