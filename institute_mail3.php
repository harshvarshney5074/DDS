<?php 

include("dbcon.php");
if(isset($_POST['mail'])){
	
	$body='<p>Dear Sir/Madam, <br/><br/>Could you please arrange to send the following article(s) if available in your collection.</p>The bibliographic details of the article(s) is/are given below:<br/>';
	
	$trun=mysqli_query($conn,"truncate table send_instentry");
	foreach($_POST['send'] as $send_id){
	$send_m=mysqli_query($conn,"select * from entry where Sr_no='$send_id'");
	$row=mysqli_fetch_array($send_m);
	$req_date=$row['Req_date'];
	$send_entry_no=mysqli_query($conn,"insert into send_instentry (entry_id,req_date) values ('$send_id','$req_date')");
	
	$biblo=$row['Bibliographic_details'];
		
	$body.='<br/>'.$biblo.'<br/>';
	}
	$body.='<p>Regards,<br/>Library Services<br/>Indian Institute of Technology Gandhinagar<br/>Palaj | Gandhinagar - 382355 | Gujarat | INDIA<br/>Tel: + 91-079-2395 2099<br/>Email: <a href="mailto:libraryservices@iitgn.ac.in">libraryservices@iitgn.ac.in</a><br/>Website: <a href="http://www.iitgn.ac.in">http://www.iitgn.ac.in</a></p>';    
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
 <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea' });</script>
 <body>
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
	<a href="index.php"><i class="fa fa-times" style="width:20px; align:right;"aria-hidden="true"></i></a>
	</div>
	<br/>

	<br/>
   <form method="post" name="f1" id="framework_form" action="send_mail1.php" enctype="multipart/form-data">
    <div class="form-group">
	<div class="row">
		
		
	
	
 
	   <label>Request Institutions </label><br/>
	   
     <select id="framework1" name="framework1[]" multiple class="form-control" >
     <?php 
		$query3="select * from institutions";
		$getlist=mysqli_query($conn,$query3);
		while($rowcats=mysqli_fetch_array($getlist)){
	 ?>
     
      <option value="<?php echo $rowcats['Sr_no'];?>"><?php echo $rowcats['institute_name'];?></option>

		<?php }?>
     </select>

	 <div class="row">
	<div class="col-md-6">

	<label  for="inputPassword">Cc:</label>
	<input type="text" class="form-control"id="req_by" name="Cc" >
	</div>
	<div class="col-md-6">
	<label  for="inputPassword">Bcc:</label>
	<input type="text" class="form-control"id="req_by" name="Bcc" >
	</div></div>
	<label  for="inputPassword">Subject:</label>
	<input type="text" class="form-control"id="req_by" name="sub" >
	
	<label  for="inputPassword">Body:</label>
	<textarea name="Body" class="form-control" rows="15"><?php echo $body; ?></textarea>
	<br/><center>

    </div>
    <div class="form-group">
     <input type="submit" class="btn btn-info" name="send_inst" value="Send" />
	 <a href="index.php" class="btn btn-default" role="button">Close</a>
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
