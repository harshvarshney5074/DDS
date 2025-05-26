<?php 
session_start();
include('checkUser.php');
include('dbcon.php');
     if(isset($_POST['date_submit'])){
          $ab=$_POST['date1'];
          $cd=$_POST['date2'];
          $result=mysqli_query($conn,"select * from entry where Req_date between '$ab' and '$cd' ");
     }
	if(!isset($_POST['date_submit'])){
	     if(isset($_GET['get_date1'])){
               $ab=$_GET['get_date1'];
               $cd=$_GET['get_date2'];
               $result=mysqli_query($conn,"select * from entry where Req_date between '$ab' and '$cd' ");
					
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
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
		   <link href="css/select2.min.css" rel="stylesheet">
			<!-- Include Date Range Picker -->
			<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
		<style>		
			body {
    background-image: url("image/bg3.jpg");
	background-attachment: fixed;
   
}
.tabclass{
	background-color:white;
}
h1{
	font-size:40px;
	color:white;
	padding-top:70px;
}

</style>
<script>
	$(document).ready(function(){
		var date_input=$('input[name="date1"]'); //our date input has the name "date"
		var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
		date_input.datepicker({
			format: 'yyyy/mm/dd',
			container: container,
			todayHighlight: true,
			autoclose: true,
		})
		var date_input=$('input[name="date2"]'); //our date input has the name "date"
		var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
		date_input.datepicker({
			format: 'yyyy/mm/dd',
			container: container,
			todayHighlight: true,
			autoclose: true,
		})
		var date_input=$('input[name="req_date"]'); //our date input has the name "date"
		var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
		date_input.datepicker({
			format: 'yyyy/mm/dd',
			container: container,
			todayHighlight: true,
			autoclose: true,
		})
	})
</script>
		   
      </head>  
      <body>  
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">IITGN</a>
    </div>
    <ul class="nav navbar-nav ">
      
	  <?php 
	   if($_SESSION['type']=='0' || $_SESSION['type']=='1' ){
		   ?>
      		<li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Manage
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
			<li><a href="institutions/index.php">Institutions</a></li>
          
          <li><a href="journal/index.php">Journals</a></li>
		  <li><a href="patrons/index.php">Patrons</a></li>
		  
          
        </ul>
      </li>
	  <li><a href="biblo_search1.php">Search</a></li>
	  <li><a href="orders.php">Orders</a></li>
	   <?php } ?>
	  <li><a href="reports/index.php">Reports</a></li>
      <?php
		if($_SESSION['type']=='0'){
			echo"<li><a href='users/index.php'>Settings</a></li>";
		}

	  ?>
    </ul>
    <form method="post" action="" class="navbar-form navbar-right">
       
        <input type="text" id="date" name="date1" placeholder="YYYY/MM/DD"class="form-control" required>
        <input type="text" id="date" name="date2" placeholder="YYYY/MM/DD"class="form-control" required>

		
          <button type="submit" name="date_submit" class="btn btn-default">
            <i class="glyphicon glyphicon-search"></i>
          </button>
       <ul class="nav navbar-nav navbar-right">
      <?php
	  if(isset($_SESSION['uid'])){
	  echo "<li><a href='#'>User ".$_SESSION['uid']."</a></li>";
      echo"<li><a href='logout.php'><span class='glyphicon glyphicon-log-in'></span> Logout</a></li>";
	  }
	  ?>
    </ul>
      </div>
    </form>
  </div>
</nav>	<!--<li>
	<form method="post" action="">
				From:<input id="date" name="date1" placeholder="YYYY/MM/DD" type="text"/>
				To: <input type="text"name="date2" placeholder="YYYY/MM/DD">
	
				<input type="submit" name="submit" class="btn btn-info" value="Search">
				</form>
				
	</li>
  </div>
</nav>-->
  
            
 <div class="">  

                <h1 align="center">List of Articles</h1>  
				             
                <br />  
				
				<form method="post" action="institute_mail3.php">
				
				
                <div class="table-responsive info"> 
				<?php
					 if($_SESSION['type']=='0' || $_SESSION['type']=='1' ){
				?>
                     <div align="right">  
                          <input type="submit" value="Mail" class="btn btn-success" name="mail"> <button type="button" name="add" id="add" data-toggle="modal" data-target="#add_data_Modal" class="btn btn-warning">Add</button>  
                     </div> 
				<?php 
					 }?>
                     <br />  
					 <div class="tabclass">
                     <div id="employee_table">  
                          <table id="employee_data" class="table table-hover table-striped table-bordered"> 
							<thead>
                                <tr class="info"> 
									<th>Mail</th>								
                                   <th>ID</th>
                                    <th>Date</th>
									<th>Patron</th>
									<th>Category</th>
									<th>Bibliographic Details</th>
									<th>Journal Name</th>
									<th>Document Type</th>
									<th>Status</th>
									<th>Actions</th>
									<th><i class='fa fa-download fa-lg' aria-hidden='true'></i></th>
									

									
									
                               </tr>  
							   </thead>
                               <?php  
                               while($row = mysqli_fetch_array($result))  
                               {  
                               
                              
                                   $id=$row['Sr_no'];
							$file=$row['File_name'];
							$req_by=$row['Req_by'];
							$req=mysqli_query($conn,"select Display_name from patrons where Sr_no='$req_by'");
							$row1=mysqli_fetch_array($req);
							$req_by1=$row1['Display_name'];
							
						?>
 			<tr>
				<td><input type="checkbox" name="send[]" value="<?php echo $row['Sr_no'];?>"></td>
				<td><?php echo $row['Sr_no']; ?></td>
				<td><?php echo $row['Req_date']; ?></td>
				<td><?php echo $req_by1; ?></td>
				<td><?php echo $row['Category']; ?></td>
				<td><?php echo $row['Bibliographic_details']; ?></td>
				<td><?php echo $row['Journal_name']; ?></td>
				<td><?php echo $row['Document_type']; ?></td>
				<?php 
				$sta=$row['Status'];
				
					if($sta=='Pending'){
					?>
						<td><img src="image/red-dot.png" width="15px" height="15px" alt="pending" title="pending" /><span class='hidden'>Pending</span></td>
					<?php } ?>
					<?php 
					if($sta=='Approached'){
					?>
						<td><img src="image/yellow-dot.png" width="15px" height="15px"/><span class='hidden'>Approached</span></td>
						<?php } ?>
					<?php
					if($sta=='Complete'){
					?>
						<td><img src="image/green-dot.png" width="15px" height="15px"/><span class='hidden'>Complete</span></td>
						<?php } ?>
						<?php 
					if($sta=='Received'){
					?>
						<td><img src="image/purple.png" width="15px" height="15px"/></td>
						<?php } ?>
					<?php
					if($sta=='Closed'){
					?>
						<td><img src="image/blue-dot.png" width="15px" height="15px"/><span class='hidden'>Closed</span></td>
						<?php } ?>
				
				
                                    <td>
									<center>
									<!--<input type="button" name="edit" value="Edit" id="<?php echo $row["Sr_no"]; ?>" class="btn btn-info btn-xs edit_data123" />  -->
									<?php
								  if($_SESSION['type']=='0' || $_SESSION['type']=='1' ){
									  ?>
									<a href="edit_final1.php?edit_record=<?php echo $row["Sr_no"];?>&date1=<?php echo $ab;?>&date2=<?php echo $cd;?>" class="btn btn-info btn-xs">Edit</a><br/>
									
									<input type="button" name="view" value="View" id="<?php echo $row["Sr_no"]; ?>" class="btn btn-success btn-xs view_data" /><br/>
									<input type="button" name="delete" value="Delete" id="<?php echo $row["Sr_no"]; ?>" class="btn btn-danger btn-xs delete_data" />
								<?php 
								  }
								  else{
								?>
									 <input type="button" name="view" value="view" id="<?php echo $row["Sr_no"]; ?>" class="btn btn-info btn-xs view_data" /> 
								 <?php } ?>
								 </td>
								 </center>
									<td>
						
				<?php 
				if($file!=""){
					echo"<a href='filedownload.php?download_file=$id'><i class='fa fa-download fa-lg' aria-hidden='true'></i></a>";
				}
				
				
				?>
				</td>									
							  </tr>  
                               <?php  
                               }  
                               ?>  
                          </table>  
                     </div>  
                </div>  
				</form>
           </div>  
      </body>  
 </html>  
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
                     <form method="post" id="insert_form">  
                          <label for="inputPassword">Date</label>
		
							<input type="text" class="form-control" id="req_date" name="req_date" placeholder="Request Date(YYYY/MM/DD)" required>
		
		
		<label  for="inputPassword">Patron</label>
		
		<input type="text" name="req_by" id="req_by" class="form-control " autocomplete="off" placeholder="Request By" required/>
		
		<label for="inputPassword">Category</label>
		<select name="category" id="category" class="form-control">  
                               <?php 
								$doc=mysqli_query($conn,"select * from patron_type");
								while($get=mysqli_fetch_array($doc)){
									
									
									?>
									<option value="<?php echo $get['Patron_type'];?>"><?php echo $get['Patron_type'];?></option>  
								<?php 
								}
                              ?>                           </select>  
			
		<br/>
		<div class="row">
		<div class="col-md-6">
		<label for="inputPassword">Bibliographic Details | Document Type | Journal Name</label>
		</div>
		<div class="col-md-6">
		<input type="text" id="journal_name1" class="form-control " autocomplete="off" placeholder="Journal Name" />
		</div>
		</div>
		<table id="dynamic">
<tr>
<td width="98%">
		<select name="document_type[]" id="document_type" class="form-control"> 

								<?php 
								$doc=mysqli_query($conn,"select * from document_type");
								while($get=mysqli_fetch_array($doc)){
									
									
									?>
									<option value="<?php echo $get['Document_type'];?>"><?php echo $get['Document_type'];?></option>  
								<?php 
								}
                              ?> 
                          </select>  <br/>

<input type="text" name="journal_name[]" id="country" class="form-control " autocomplete="off" placeholder="Journal Name" /><br/>

<textarea  placeholder="Bibliographic details" class="form-control" id="biblio_det" name="name[]"></textarea><br/>

		
		
		
		
		
</td>	

<td width="2%">
<button type="button" name="add" id="add_input"class="btn btn-primary btn-xs"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
</td>

</tr>
</table>

<script>
$(document).ready(function(){
	var i=1;
	$('#add_input').click(function(){
		i++;
		$('#dynamic').append('<tr id="row'+i+'"><td width="98%"><select name="document_type[]" id="document_type" class="form-control"> <?php $doc=mysqli_query($conn,"select * from document_type");while($get=mysqli_fetch_array($doc)){?><option value="<?php echo $get['Document_type'];?>"><?php echo $get['Document_type'];?></option>  <?php } ?>  </select> <br/>  <input type="text" class="form-control" class="journal_name" name="journal_name[]" placeholder="Journal name"><br/><textarea  class="form-control" name="name[]" placeholder="Bibliographic Details"></textarea><br/></td><td width="2%"><button type="button" name="remove" id="'+i+'" class="btn btn-primary btn-xs btn_remove"><i class="fa fa-minus-circle" aria-hidden="true"><br/></i></button></td> </tr>');
	});
	$(document).on('click', '.btn_remove', function(){
		var button_id = $(this).attr("id");
		$('#row'+button_id+'').remove();
	});
	
});
</script>		
			
		
		
	
		<label for="inputPassword">Status</label>
		<select name="status" id="status" class="form-control">  
                               <option value="Pending">Pending</option>  
                               <option value="Approached">Approached</option> 
								<option value="Complete">Complete</option>
								<option value="Closed">Closed</option>								
                          </select>
<!--
		<input type="radio" name="status" value="Pending" checked><img src="image/red-dot.png" width="15px" height="15px"/>
		<input type="radio" name="status" value="Approached"><img src="image/yellow-dot.png"width="15px" height="15px"/>
		<input type="radio" name="status" value="Complete"><img src="image/green-dot.png"width="15px" height="15px"/>
		<input type="radio" name="status" value="Closed"><img src="image/blue-dot.png" width="15px" height="15px"/>
-->
		<input type="hidden" name="employee_id" id="employee_id" />
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
      $('#employee_data').DataTable({
			
			"stateSave":true,
			"aaSorting": [[1, 'desc']],
			 dom: 'Bfrtip',
        buttons: [
           
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
} );	  
}); 
 $(document).ready(function(){  
      $('#add').click(function(){  
           $('#insert').val("Insert");  
           $('#insert_form')[0].reset();  
      });  
      $(document).on('click', '.edit_data123', function(){  
           var employee_id = $(this).attr("id");  
           $.ajax({  
                url:"fetch.php",  
                method:"POST",  
                data:{employee_id:employee_id},  
                dataType:"json",  
                success:function(data){  
                     $('#req_date').val(data.name);  
                     $('#req_by').val(data.address);  
                     $('#category').val(data.gender);  
                     $('#biblio_det').val(data.designation);  
                     $('#age').val(data.age);  
                     $('#employee_id').val(data.id);  
                     $('#insert').val("Update");  
                     $('#add_data_Modal').modal('show');  
                }  
           });  
      });  
      $('#insert_form').on("submit", function(event){  
           event.preventDefault();  
           if($('#req_date').val() == "")  
           {  
                alert("Name is required");  
           }  
           else if($('#req_by').val() == '')  
           {  
                alert("Address is required");  
           }  
           else if($('#category').val() == '')  
           {  
                alert("Designation is required");  
           }  
           else if($('#biblio_det').val() == '')  
           {  
                alert("Age is required");  
           }  
		    else if($('#document_type').val() == '')  
           {  
                alert("Age is required");  
           }  
		    else if($('#journal_name').val() == '')  
           {  
                alert("Age is required");  
           }  
           else  
           {  
                $.ajax({  
                     url:"insert.php",  
                     method:"POST",  
                     data:$('#insert_form').serialize(),  
                     beforeSend:function(){  
                          $('#insert').val("Inserting");  
                     },  
                     success:function(data){  
                          $('#insert_form')[0].reset();  
                          $('#add_data_Modal').modal('hide');  
                          $('#employee_table').html(data);  
                     }  
                });  
           }  
      });  
      $(document).on('click', '.view_data', function(){  
           var employee_id = $(this).attr("id");  
           if(employee_id != '')  
           {  
                $.ajax({  
                     url:"select.php",  
                     method:"POST",  
                     data:{employee_id:employee_id},  
                     success:function(data){  
                          $('#employee_detail').html(data);  
                          $('#dataModal').modal('show');  
                     }  
                });  
           }            
      });  
	  $(document).on('click', '.delete_data', function(){  
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
	  	  $(document).on('click', '.addinst_data', function(){  
           var employee_id = $(this).attr("id");  
           if(employee_id != '')  
           {  
                $.ajax({  
                     url:"add_inst.php",  
                     method:"POST",  
                     data:{employee_id:employee_id},  
                     success:function(data){  
                          $('#employee_detail').html(data);  
                          $('#dataModal').modal('show');  
                     }  
                });  
           }            
      });  
	   
	  $(document).on('click', '.edit1_data', function(){  
           var employee_id = $(this).attr("id");  
           if(employee_id != '')  
           {  
                $.ajax({  
                     url:".php",  
                     method:"POST",  
                     data:{employee_id:employee_id},  
                      dataType:"json",  
					success:function(data){  
                     $('#req_date').val(data.req_date);  
                     $('#req_by').val(data.req_by);  
                     $('#category').val(data.category);  
                     $('#biblio_det').val(data.biblio_det);  
                     $('#status').val(data.status);  
                     $('#employee_id').val(data.id);  
                     $('#insert').val("Update");  
                     $('#add_data_Modal').modal('show');  
                }  
                });  
           }            
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
  $('#req_by').typeahead({
  source: function(query, result)
  {
   $.ajax({
    url:"fetch2.php",
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
 	 $('#journal_name1').typeahead({
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
