<?php

	include("dbcon.php");
	session_start();
	if(isset($_GET['edit_record'])){
		$get_id=$_GET['edit_record'];
		$query="select * from journal_list where S_no='$get_id'";
		$get_pro=mysqli_query($conn,$query);
		
		while($row_pro=mysqli_fetch_array($get_pro)){
			$id=$row_pro['S_no'];
			$jour_name=$row_pro['Journal_name'];
			$print_issn=$row_pro['Print_ISSN'];
			$e_issn=$row_pro['E_ISSN'];
			$source_type=$row_pro['Source_type'];
			$pub_name=$row_pro['Pub_name'];
			$main_pub=$row_pro['Main_pub'];
			$pub_country=$row_pro['Pub_country'];
			
			
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
  <div class="container" style="width:600px;">
	<h1>Edit Record</h1>

   <form method="post" name="inst_form" action="" onsubmit="return validateForm();">
    <div class="form-group">
	
		<label  for="inputPassword">Journal Name</label>
		
		<input type="text" id="inputPassword" name="jour_name" value="<?php echo $jour_name; ?>" placeholder="Institute Name"class="form-control" >
		
		<label  for="inputPassword">Print-ISSN</label>
		
		<input type="text" id="inputPassword" name="print_issn" value="<?php echo $print_issn; ?>" placeholder="Email" class="form-control" >
		
		<label  for="inputPassword">E-ISSN</label>
		
		<input type="text" id="inputPassword" name="e_issn" value="<?php echo $e_issn; ?>" placeholder="Address" class="form-control" >
			
		<label  for="inputPassword">Source Type</label>
		
		<input type="text" id="inputPassword" name="source_type" value="<?php echo $source_type; ?>" placeholder="Address" class="form-control" >

		<label  for="inputPassword">Publication Name</label>
		
		<input type="text" id="inputPassword" name="pub_name" value="<?php echo $pub_name; ?>" placeholder="Address" class="form-control" >

		<label  for="inputPassword">Main Publication</label>
		
		<input type="text" id="inputPassword" name="main_pub" value="<?php echo $main_pub; ?>" placeholder="Address" class="form-control" >

      <label  for="inputPassword">Publication Country</label>
		
		<input type="text" id="inputPassword" name="pub_country" value="<?php echo $pub_country; ?>" placeholder="Address" class="form-control" >

    </div>
    <div class="form-group">
     <input type="submit" class="btn btn-info" name="update" value="Update" />
	 <a href="index.php" class="btn btn-default" role="button">Close</a>
    </div>
   </form>
   <br />
  </div>
 </body>
</html>

<?php 

	if(isset($_POST['update'])){
		
		$jour_name1=trim_input($_POST['jour_name']);
		$print_issn=trim_input($_POST['print_issn']);
		$e_issn=trim_input($_POST['e_issn']);
		$source_type=trim_input($_POST['source_type']);
		$pub_name=trim_input($_POST['pub_name']);
		$main_pub=trim_input($_POST['main_pub']);
		$pub_country=trim_input($_POST['pub_country']);
		
		
		$update_record=mysqli_query($conn,"update journal_list set Journal_name='$jour_name1',Print_ISSN='$print_issn',E_ISSN='$e_issn',Source_type='$source_type',Pub_name='$pub_name',
		Main_pub='$main_pub',Pub_country='$pub_country' where S_no='$get_id'");
		$update_inst=mysqli_query($conn,"update entry set Journal_name='$jour_name1' where Journal_name='$jour_name'");
		
		if($update_record){
		echo"<script>alert('Data updated successfully')</script>";
		echo"<script>window.open('index.php','_self')</script>";
	}

	}
?>
