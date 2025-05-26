
<?php

	include("dbcon.php");
	
	if(isset($_GET['edit_record'])){
		$get_id=$_GET['edit_record'];
		$get_date1=$_GET['date1'];
		$get_date2=$_GET['date2'];
		$get_pro=mysqli_query($conn,"select * from entry where Sr_no='$get_id'");
			
		while($row_pro=mysqli_fetch_array($get_pro)){
			$id=$row_pro['Sr_no'];
			$req_date=$row_pro['Req_date'];
			$req_date=$row_pro['Req_date'];
			$req_by=$row_pro['Req_by'];
			
							$req=mysqli_query($conn,"select Display_name from patrons where Sr_no='$req_by'");
							$row1=mysqli_fetch_array($req);
							$req_by=$row1['Display_name'];
			$req_cat=$row_pro['Category'];
			$jour=$row_pro['Journal_name'];
			$biblio_det=$row_pro['Bibliographic_details'];
			$status=$row_pro['Status'];
			$file_n=$row_pro['File_name'];
		}  
	 
	}
?>
<!DOCTYPE html>
<html>
 <head>
  <title>DDS</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  
 
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
<script language="JavaScript">
<!--

function enable_text(status)
{
status=!status;	
	document.f1.fil.disabled = status;
}
//-->
</script>
<style>


input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
textarea, select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}



input[type=submit]:hover {
    background-color: #45a049;
}

.form {
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 20px;
	margin:10%;
	margin-top:5%;
	
	
}
h1{
	margin-top:5%;
	text-align:center;
}
.close{
	margin-left:90%;
}

</style>




 
 </head>
 <body onload=enable_text(false);>
 <nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">IITGN</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="index.php">Home</a></li>
      
      
	  
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <?php
	  if(isset($_SESSION['uid'])){
      echo"<li><a href='../logout.php'><span class='glyphicon glyphicon-log-in'></span> Logout</a></li>";
	  }
	  ?>
    </ul>
  </div>
</nav>
  
  <br /><br />
  <div class="container" style="width:600px;">
  
	<h1>Edit Record</h1>
	<div class="close">
	<a href="index1.php?get_date1=<?php echo $get_date1?>&get_date2=<?php echo $get_date2?>"><i class="fa fa-times" style="width:20px; align:right;"aria-hidden="true"></i></a>
	</div>
	<br/>
	      <label>Requested Institutions</label><br/>
	 Already requested:
	 <?php 
		$getcats=mysqli_query($conn,"select institutions.institute_name as institute_name,institute_list.institute_name as institute_id from institutions,institute_list where institute_list.entry_id=$get_id and institute_list.institute_name=institutions.Sr_no");
		while($rowcats=mysqli_fetch_array($getcats)){
					
					echo $rowcats['institute_name'].',';
					}
					
	 
	 
	 ?>
	 		<form method="post" action="">
		<div class="row">
		<div class="col-md-11">
		<select id="framework" name="framework[]" multiple class="form-control" >
	 <?php 
		$getlist=mysqli_query($conn,"select * from institutions where Sr_no not in (select institute_name from institute_list where entry_id=$get_id)");
		while($rowcats=mysqli_fetch_array($getlist)){
	 ?>
     
      <option value="<?php echo $rowcats['Sr_no'];?>"><?php echo $rowcats['institute_name'];?></option>
		<?php }?>
     </select>
	 </div>
	 <div class="col-md-1">
	 <input type="submit" value="Add"class="btn btn-primary btn-xs"name="insti">
	</div>
	</div>
		
		
		</form>
	<?php
	if(isset($_POST['insti'])){
		$number = count($_POST["framework"]);

if($number > 0)
{
 for($i=0; $i<$number; $i++)
 {
	 $name=$_POST["framework"][$i];
	 
 
 $query1 = mysqli_query($conn,"INSERT INTO institute_list(institute_name,entry_id,req_date) VALUES ('$name','$get_id','$req_date')");
 
}

}

	} 
	 
	 
	?> 
	<br/>
   <form method="post" name="f1" id="framework_form" action="" enctype="multipart/form-data">
    <div class="form-group">
	<div class="row">
			<div class="col-sm-4">
		<label  for="inputPassword">Request Date</label>
		
		<input type="text" id="inputPassword" name="req_date" value="<?php echo $req_date; ?>" placeholder="Request Date">
		</div>
		<div class="col-sm-4">
		<label  for="inputPassword">Request By</label>
		
		<input type="text" id="inputPassword" name="req_by" value="<?php echo $req_by; ?>" placeholder="Request By">
		</div>
		<div class="col-sm-4">
		<label  for="inputPassword">Category</label>
		
		<select name="category" id="category" class="form-control"> 
								<option value="<?php echo $req_cat; ?>"><?php echo $req_cat; ?></option> 
                              <?php 
								$doc=mysqli_query($conn,"select * from patron_type");
								while($get=mysqli_fetch_array($doc)){
									
									
									?>
									<option value="<?php echo $get['Patron_type'];?>"><?php echo $get['Patron_type'];?></option>  
								<?php 
								}
                              ?>    
                          </select> 		</div>
		</div>
		<label for="inputPassword">Journal Name</label>
		<input type="text" name="journal_name" id="country" class="form-control " value="<?php echo $jour; ?>" autocomplete="off" placeholder="Journal Name" />
      <label for="inputPassword">Bibliographic Details</label>
		<textarea rows="4" cols="150" name="biblio_det" placeholder="Bibliographic details" required><?php echo $biblio_det; ?>
		</textarea>
		
		
		<label for="country">Status</label>
    <select id="status" name="status">
			<option value="<?php echo $status; ?>"><?php echo $status; ?></option>
		    <option value="Pending">Pending</option>
			<option value="Approached">Approached</option>
			<option value="Complete">Complete</option>
			<option value="Closed">Closed</option>
    </select>
	<input type="hidden" value="<?php echo $get_id; ?>">
	

	
 
	   <label>Received from </label><br/>
	   Already received:
	 <?php 
		$query2="select institutions.institute_name as institute_name,receive_list.institute_name as institute_id from institutions,receive_list where receive_list.entry_id=$get_id and receive_list.institute_name=institutions.Sr_no";
		$getcats=mysqli_query($conn,$query2);
		while($rowcats=mysqli_fetch_array($getcats)){
					
					echo $rowcats['institute_name'].',';
		}
		 ?>
  
     <select id="framework1" name="framework1[]" multiple class="form-control" >
     <?php 
		$query3="select institutions.institute_name as institute_name,institute_list.institute_name as institute_id from institutions,institute_list where institute_list.entry_id=$get_id and institute_list.institute_name=institutions.Sr_no";
		$getlist=mysqli_query($conn,$query3);
		while($rowcats=mysqli_fetch_array($getlist)){
	 ?>
     
      <option value="<?php echo $rowcats['institute_id'];?>"><?php echo $rowcats['institute_name'];?></option>

		<?php }?>
     </select>

	<input type="checkbox" name=others onclick="enable_text(this.checked)" value="1"><label>Attach file</label>
	<input type="file" name="fil" value="Browse" required><br/>

	
	

    </div>
    <div class="form-group">
     <input type="submit" class="btn btn-info" name="update" value="Update" />
	 <a href="index1.php?get_date1=<?php echo $get_date1?>&get_date2=<?php echo $get_date2?>" class="btn btn-default" role="button">Close</a>
    </div>
   </form>
   <br />
 
  </div>
 </body>
</html>

<script>
$(document).ready(function(){
 $('#framework').multiselect({
  nonSelectedText: 'Select Institute Name',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'100%'
 });
 $('#framework1').multiselect({
  nonSelectedText: 'Select Institute Name',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'100%'
 });
 
 $('#framework_form').on('update', function(event){
  event.preventDefault();
  var form_data = $(this).serialize();
  $.ajax({
   url:"",
   method:"POST",
   data:form_data,
   success:function(data)
   {
    $('#framework option:selected').each(function(){
     $(this).prop('selected', false);
    });
    $('#framework').multiselect('refresh');
	$('#framework1 option:selected').each(function(){
     $(this).prop('selected', false);
    });
    $('#framework1').multiselect('refresh');
    
   }
  });
 });
 
 
});
</script>

 <script> 
$(document).ready(function(){
 
 $('#country').typeahead({
  source: function(query, result)
  {
   $.ajax({
    url:"fetch1.php",
    method:"POST",
    data:{query:query},
    dataType:"json",
    success:function(data)
    {
     result($.map(data, function(item){
      return item;
     }));
    }
   })
  }
 });
});
</script>
<?php 

	if(isset($_POST['update'])){
		
		$req_date=trim_input($_POST['req_date']);
		$req_by=trim_input($_POST['req_by']);
		$category=trim_input($_POST['category']);
		$biblio_det=trim_input($_POST['biblio_det']);
		$jour=$_POST['journal_name'];
		$status=trim_input($_POST['status']);
		$check=$_POST['others'];
		if($check=='1'){
		$temp = explode(".", $_FILES["fil"]["name"]);
		$newfilename = $id . '.' . end($temp);
		move_uploaded_file($_FILES["file"]["tmp_name"], "./Files/" . $newfilename);
		$fnm=$_FILES['fil']['name'];
		$dst='./Files/'.$newfilename;

		
		move_uploaded_file($_FILES['fil']['tmp_name'],$dst);
			
				
		$update_record=mysqli_query($conn,"update entry set Req_date='$req_date',Category='$category',Bibliographic_details='$biblio_det',Journal_name='$jour',Status='Received',File_name='$newfilename',File_path='$dst' where Sr_no='$get_id'");
		}
	else{
		$update_record=mysqli_query($conn,"update entry set Req_date='$req_date',Category='$category',Bibliographic_details='$biblio_det',Journal_name='$jour',Status='$status' where Sr_no='$get_id'");
	}

$number = count($_POST["framework1"]);

if($number > 0)
{
 for($i=0; $i<$number; $i++)
 {
	 $name=$_POST["framework1"][$i];
	 
 
 $query = mysqli_query($conn,"INSERT INTO receive_list(institute_name,entry_id,req_date) VALUES ('$name','$get_id','$req_date')");
 
}
}
	if($query || $query1 || $update_record){
		echo"<script>alert('Data updated successfully')</script>";
		echo"<script>window.open('index1.php?get_date1=$get_date1&get_date2=$get_date2','_self')</script>";
	}

	}
?>