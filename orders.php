<?php  
//index.php
session_start();
include('dbcon.php');
include('checkUser.php');

$query="SELECT 
        * 
    FROM 
        orders
    WHERE 
		sent!=1 
	AND
        MONTH(date) = MONTH(CURRENT_DATE) 
    AND 
        YEAR(date) = YEAR(CURRENT_DATE)"; 
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
						<!-- Include Date Range Picker -->
			<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

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
</nav>
<?php 
	if(isset($_POST['date_submit'])){
					$ab=$_POST['date1'];
					$cd=$_POST['date2'];
					$result=mysqli_query($conn,"select * from orders where date between '$ab' and '$cd' ");
				}
				
?>
 <body>  

 <br/>
  <br /><br />  
  <div class="container" style="width:900px;">  
   <h3 align="center">List of Basket</h3>  
   <br />  
   <div class="table-responsive">
   
    <br />
    <div id="employee_table">
     <table id="employee_data" class="table table-hover table-striped table-bordered"> 
	<thead>
      <tr>
       <th>Basket Number</th> 
		<th>Date</th> 	   
       <th>Username</th>
	   <th>Patron Name</th>
	   <th>Status</th>
	   <th>View</th>
      </tr>
	  </thead>
      <?php
      while($row = mysqli_fetch_array($result))
      {
      ?>
      <tr>
		
       <td><?php echo $row["order_id"]; ?></td>
	   <td><?php echo $row["date"]; ?></td>
       <td><?php echo $row["username"]; ?></td>
	    <td><?php echo $row["Display_name"]; ?></td>
	   <td>
	   <?php if($row["sent"]==0)
		   echo "Incomplete";
			else if($row["sent"]==1)
				echo"Complete";
			else
				echo "Partially Complete";?>
		</td>
	   <td><a href="show.php?order_no=<?php echo $row["order_id"]; ?>" class="btn btn-info btn-xs " />Show</a></td>
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
<script>
$(document).ready(function(){  
      $('#employee_data').DataTable({
			"stateSave":true,
			"aaSorting": [[0, 'desc']],
} );	  
});
</script>
