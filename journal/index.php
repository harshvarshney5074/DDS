<?php 
session_start();
include('dbcon.php');

include('checkUser.php');
?>  

<head>
  <title>DDS</title>  
		  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
		   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script> 
		    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>  
           <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>            
           <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" />  
		   <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css" /> 
		   <script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
		   <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> 
		   <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
		   <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
		   <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
		  	<!-- Include Date Range Picker -->
			<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

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

<div class="container">
<br/><br/><br/>
<h1><center>Master List of Journals</h1>
<div align="right">  
                          <button type="button" name="add" id="add" data-toggle="modal" data-target="#add_data_Modal" class="btn btn-warning">Add</button>  
                     </div>
					 <br/><br/>
<form  method="post" action="">
 <div class="row"><center>
   <div class="col-md-2">
   </div>
   <div class="col-md-7">
<input class="form-control"type="text" id="country" name="journal_name">
</div>
<div class="col-md-1">
<input  type="submit" value="Search" name="submit">
<br/>
</div></div>
</form>
  
<?php 
if(isset($_POST['submit'])){
	$jour=$_POST['journal_name'];
	$query=mysqli_query($conn,"select * from journal_list where Journal_name like '%$jour%'");
	?>
	<table id="employee_data" class="table table-hover table-striped table-bordered">
	<thead>
                                <tr class="info">  
                                   <th>Journal Name</th>
                                    <th>Print_ISSN</th>
									<th>E-ISSN</th>
									<th>Publication Name</th>
									<th>Main Publication</th>
									<th>Publication Country</th>
									
									
									<th>Actions</th>
									

									
									
                               </tr>  
							   </thead>
							   <tbody>
	<?php 
	while($row=mysqli_fetch_array($query)){
		?>
		<tr>
		<td><?php echo $row['Journal_name']; ?></td>
		<td><?php echo $row['Print_ISSN']; ?></td>
		<td><?php echo $row['E_ISSN']; ?></td>
		<td><?php echo $row['Pub_name']; ?></td>
		<td><?php echo $row['Main_pub']; ?></td>
		<td><?php echo $row['Pub_country']; ?></td>
		
		<td><a href="edit11.php?edit_record=<?php echo $row["S_no"]; ?>" class="btn btn-info btn-xs">Edit</a>
		 <a href="delete_record.php?delete_record=<?php echo $row["S_no"]; ?>"class="btn btn-info btn-xs" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>							
			</td>
		</tr>
		<?php
}}?>
	
	</tbody>
	
	</table>
 <div id="dataModal" class="modal fade">  
      <div class="modal-dialog">  
           <div class="modal-content">  
                <div class="modal-header">  
                     <button type="button" class="close" data-dismiss="modal">&times;</button>  
                     <h4 class="modal-title">Entry Details</h4>  
                </div>  
                <div class="modal-body" id="employee_detail">  
                </div>  
                <div class="modal-footer">  
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                </div>  
           </div>  
      </div>  
 </div>  
 <div id="add_data_Modal" class="modal fade">  
      <div class="modal-dialog">  
           <div class="modal-content">  
                <div class="modal-header">  
                     <button type="button" class="close" data-dismiss="modal">&times;</button>  
                     <h4 class="modal-title"><center>New Entry</h4>  
                </div>  
                <div class="modal-body">  
                     <form method="post"  action="" id="insert_form">  
                          <label for="inputPassword">Journal Name*</label>
		
							<input type="text" class="form-control" id="req_date" name="journal_name" placeholder="Journal Name" required>
		
		
		<label  for="inputPassword">Print-ISSN</label>
		
		<input type="text" class="form-control"id="req_by" name="print_issn" placeholder="Print-ISSN" >
		<label  for="inputPassword">E-ISSN</label>
		
		<input type="text" class="form-control"id="req_by" name="e_issn" placeholder="E-ISSN" >
		<label  for="inputPassword">Publication Name*</label>
		
		
		<input type="text" class="form-control"id="req_by" name="pub_name" placeholder="Publication Name" required>
		<label  for="inputPassword">Main Publication</label>
		
		<input type="text" class="form-control"id="req_by" name="main_pub" placeholder="Main Publication" >
	<label  for="inputPassword">Publication Country</label>
		
		<input type="text" class="form-control"id="req_by" name="pub_country" placeholder="Publication Country" >

		
		
		<br/><br/>
		<input type="submit" name="insert" id="insert" value="Insert" class="btn btn-success" />				  
					  
                     </form>  
                </div>  
                <div class="modal-footer">  
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                </div>  
           </div>  
      </div>  
 </div> 
 <script> 
 $(document).ready(function(){  
	  $(document).on('click', '.delete_data1', function(){  
           var employee_id = $(this).attr("id");  
           if(employee_id != '')  
           {  
                $.ajax({  
                     url:"delete.php",  
                     method:"POST",  
                     data:{employee_id:employee_id},  
                     success:function(data){  
                          $('#employee_detail').html(data);  
                          $('#dataModal').modal('show');  
                     }  
                });  
           }            
      });  

}); 
</script>
<script>  
 $(document).ready(function(){  
      $('#employee_data').DataTable({
} );
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

<?php

if(isset($_POST['insert'])){
	$jour=$_POST['journal_name'];
	$print=$_POST['print_issn'];
	$eissn=$_POST['e_issn'];
	
	$pub_name=$_POST['pub_name'];
	$main_pub=$_POST['main_pub'];
	$pub_country=$_POST['pub_country'];
	echo" $jour $print $eissn $source_type $pub_name $main_pub $pub_country";
	$query=mysqli_query($conn,"insert into journal_list (Journal_name,Print_ISSN,E_ISSN,Pub_name,Main_pub,Pub_country) values ('$jour','$print',
	'$eissn','$pub_name','$main_pub','$pub_country')");
	
}


 ?>

