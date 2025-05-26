<!DOCTYPE html>
<?php 
session_start();
include('dbcon.php');
include('checkUser.php');


$sql1=mysqli_query($conn,"select Sr_no from entry where Category='Student'");
$sql2=mysqli_query($conn,"select Sr_no from entry where Category='Faculty'");
$sql3=mysqli_query($conn,"select Sr_no from entry where Category='Other Institutions'");
$sql4=mysqli_query($conn,"select Sr_no from entry where Category='Corporate Members'");
$sql5=mysqli_query($conn,"select Sr_no from entry where Category='Institute Members'");
$s1=mysqli_num_rows($sql1);
$s2=mysqli_num_rows($sql2);
$s3=mysqli_num_rows($sql3);
$s4=mysqli_num_rows($sql4);
$s5=mysqli_num_rows($sql5);

?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DDS|Report</title>

    <!-- Bootstrap -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
		   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           
		    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
           <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>            
           <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />  
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
   
    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
	<style>
	.tile_count{
		padding-top:10px;
	}
	
	</style>
<!-- categories wise pie chart -->

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
	  <li><a href="../reports/charts/bargraph.php">Statistics</a></li>
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

    <div class="container body">
      <div class="main_container">
 
        <!-- top navigation -->

        <!-- /top navigation -->
<?php 
	$sql1=mysqli_query($conn,"select Sr_no from entry");
	$sql12=mysqli_num_rows($sql1);
	$sql2=mysqli_query($conn,"select Sr_no from entry where Status='Pending'");
	$sql22=mysqli_num_rows($sql2);
	$sql3=mysqli_query($conn,"select Sr_no from entry where Status='Approached'");
	$sql32=mysqli_num_rows($sql3);
	$sql4=mysqli_query($conn,"select Sr_no from entry where Status='Complete'");
	$sql42=mysqli_num_rows($sql4);
	$sql5=mysqli_query($conn,"select Sr_no from entry where Status='Closed'");
	$sql52=mysqli_num_rows($sql5);
	$sql6=mysqli_query($conn,"select Sr_no from entry where Category='Faculty'");
	$sql62=mysqli_num_rows($sql6);
	$sql7=mysqli_query($conn,"select Sr_no from entry where Category='Student'");
	$sql72=mysqli_num_rows($sql7);

?>
<script>
	$.noConflict();
	jQuery(document).ready(function ($) {
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
		$('#employee_data3').DataTable({
			
			
			"lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
						 dom: 'Bflit',
        buttons: [
           
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
		});
		$('#employee_data').DataTable({
			
			"aaSorting": [[2, 'desc']],
			"lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
						 dom: 'Bflit',
        buttons: [
           
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
		});
		$('#employee_data1').DataTable({
			"aaSorting": [[1, 'desc']],
			"aaSorting": [[2, 'desc']],
			"lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
						 dom: 'Bflit',
        buttons: [
           
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
		});
				$('#employee_data6').DataTable({
			
			"aaSorting": [[2, 'desc']],
			"lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
						 dom: 'Bflit',
        buttons: [
           
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
		});
		
		$('#employee_data2').DataTable({
			
			"aaSorting": [[2, 'desc']],
			"lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
						 dom: 'Bflit',
        buttons: [
           
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
		}); 
$('#employee_data7').DataTable({
			
			"aaSorting": [[2, 'desc']],
			"lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
						 dom: 'Bflit',
        buttons: [
           
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
		}); 
$('#employee_data8').DataTable({
			
			"aaSorting": [[2, 'desc']],
			"lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
						 dom: 'Bflit',
        buttons: [
           
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
		}); 
$('#employee_data9').DataTable({
			
			"aaSorting": [[2, 'desc']],
			"lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
						 dom: 'Bflit',
        buttons: [
           
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
		}); 		
		    $(document).on('click', '.view_data', function(){  
            
                $.ajax({  
                     url:"",  
                     method:"POST",  
                     data:{},  
                     success:function(data){  
                          $('#employee_detail').html(data);  
                          $('#dataModal').modal('show');  
                     }  
                });  
                      
      }); 
	})

</script>
<br/><br/>
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
          <div class="row tile_count">
            <div class="col-md-2 col-sm-3 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-plus-circle"></i> Total Entries</span>
              <div class="count"><?php echo"$sql12"?></div>
              
            </div>
            <div class="col-md-2 col-sm-3 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-clock-o"></i>Total Pending</span>
              <div class="count red"><?php echo"$sql22"?></div>
                 </div>
            <div class="col-md-2 col-sm-3 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-arrow-circle-right"></i> Total Approached</span>
              <div class="count aero"><?php echo"$sql32"?></div>
             
            </div>
            <div class="col-md-2 col-sm-3 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-check-circle"></i> Total Completed</span>
              <div class="count green"><?php echo"$sql42"?></div>
             
            </div>
            <div class="col-md-2 col-sm-3 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-window-close"></i> Total Closed</span>
              <div class="count blue"><?php echo"$sql52"?></div>
             
            </div>
            <div class="col-md-1 col-sm-3 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Faculty</span>
              <div class="count"><?php echo"$sql62"?></div>
              
            </div>
			<div class="col-md-1 col-sm-3 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Students</span>
              <div class="count"><?php echo"$sql72"?></div>
              
            </div>
          </div>
          <!-- /top tiles -->

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="dashboard_graph">

                <div class="row x_title">
                  <div class="col-md-6">
                    
                  </div>
                  <div class="col-md-6">
<!-- 
 <form method="post" action="" >
		
		<input type="radio" name="category" value="Faculty" checked> Faculty
		<input type="radio" name="category" value="Student"> Student
		
		<select name="type">
		<option value="Pending">Pending</option>
		<option value="Approached">Approached</option>
		<option value="Complete">Complete</option>
		<option value="Closed">Closed</option>
		
				
		</select>
		
		

        <input type="text" id="date" name="date1" placeholder="YYYY/MM/DD" >
        <input type="text" id="date" name="date2" placeholder="YYYY/MM/DD" >

		
          <button type="submit" name="submit" class="btn btn-default">
            <i class="glyphicon glyphicon-search"></i>
          </button>
		 
		  </form>
		  -->
                    </div>
                  </div>
<?php 
	if(isset($_POST['user_submit'])){
		$date1=$_POST['date1'];
		$date2=$_POST['date2'];
		$r1=mysqli_query($conn,"select Sr_no from entry  where Category='Student' and Req_date between '$date1' and '$date2'");
$r2=mysqli_query($conn,"select Sr_no from entry  where Category='Student' and Status='Pending' and Req_date between '$date1' and '$date2'");
$r3=mysqli_query($conn,"select Sr_no from entry where Category='Student' and Status='Approached' and Req_date between '$date1' and '$date2'");
$r4=mysqli_query($conn,"select Sr_no from entry  where Category='Student' and Status='Complete' and Req_date between '$date1' and '$date2'");
$r5=mysqli_query($conn,"select Sr_no from entry  where Category='Student' and Status='Closed' and Req_date between '$date1' and '$date2'");
$R1=mysqli_num_rows($r1);
$R2=mysqli_num_rows($r2);
$R3=mysqli_num_rows($r3);
$R4=mysqli_num_rows($r4);
$R5=mysqli_num_rows($r5);
/* faculty */
$s1=mysqli_query($conn,"select Sr_no from entry  where Category='Faculty' and Req_date between '$date1' and '$date2'");
$s2=mysqli_query($conn,"select Sr_no from entry  where Category='Faculty' and Status='Pending' and Req_date between '$date1' and '$date2'");
$s3=mysqli_query($conn,"select Sr_no from entry where Category='Faculty' and Status='Approached' and Req_date between '$date1' and '$date2'");
$s4=mysqli_query($conn,"select Sr_no from entry  where Category='Faculty' and Status='Complete' and Req_date between '$date1' and '$date2'");
$s5=mysqli_query($conn,"select Sr_no from entry  where Category='Faculty' and Status='Closed' and Req_date between '$date1' and '$date2'");
$S1=mysqli_num_rows($s1);
$S2=mysqli_num_rows($s2);
$S3=mysqli_num_rows($s3);
$S4=mysqli_num_rows($s4);
$S5=mysqli_num_rows($s5);
/* other institutions */
$t1=mysqli_query($conn,"select Sr_no from entry  where Category='Other Institutions' and Req_date between '$date1' and '$date2'");
$t2=mysqli_query($conn,"select Sr_no from entry  where Category='Other Institutions' and Status='Pending' and Req_date between '$date1' and '$date2'");
$t3=mysqli_query($conn,"select Sr_no from entry where Category='Other Institutions' and Status='Approached'and Req_date between '$date1' and '$date2'");
$t4=mysqli_query($conn,"select Sr_no from entry  where Category='Other Institutions' and Status='Complete'and Req_date between '$date1' and '$date2'");
$t5=mysqli_query($conn,"select Sr_no from entry  where Category='Other Institutions' and Status='Closed'and Req_date between '$date1' and '$date2'");
$T1=mysqli_num_rows($t1);
$T2=mysqli_num_rows($t2);
$T3=mysqli_num_rows($t3);
$T4=mysqli_num_rows($t4);
$T5=mysqli_num_rows($t5);
/* Corporate Members */
$u1=mysqli_query($conn,"select Sr_no from entry  where Category='Corporate Members'and Req_date between '$date1' and '$date2'");
$u2=mysqli_query($conn,"select Sr_no from entry  where Category='Corporate Members' and Status='Pending'and Req_date between '$date1' and '$date2'");
$u3=mysqli_query($conn,"select Sr_no from entry where Category='Corporate Members' and Status='Approached' and Req_date between '$date1' and '$date2'");
$u4=mysqli_query($conn,"select Sr_no from entry  where Category='Corporate Members' and Status='Complete'and Req_date between '$date1' and '$date2'");
$u5=mysqli_query($conn,"select Sr_no from entry  where Category='Corporate Members' and Status='Closed'and Req_date between '$date1' and '$date2'");
$U1=mysqli_num_rows($u1);
$U2=mysqli_num_rows($u2);
$U3=mysqli_num_rows($u3);
$U4=mysqli_num_rows($u4);
$U5=mysqli_num_rows($u5);
/* Institution members */
$v1=mysqli_query($conn,"select Sr_no from entry  where Category='Institute Members'and Req_date between '$date1' and '$date2'");
$v2=mysqli_query($conn,"select Sr_no from entry  where Category='Institute Members' and Status='Pending'and Req_date between '$date1' and '$date2'");
$v3=mysqli_query($conn,"select Sr_no from entry where Category='Institute Members' and Status='Approached'and Req_date between '$date1' and '$date2'");
$v4=mysqli_query($conn,"select Sr_no from entry  where Category='Institute Members' and Status='Complete'and Req_date between '$date1' and '$date2'");
$v5=mysqli_query($conn,"select Sr_no from entry  where Category='Institute Members' and Status='Closed'and Req_date between '$date1' and '$date2'");
$V1=mysqli_num_rows($v1);
$V2=mysqli_num_rows($v2);
$V3=mysqli_num_rows($v3);
$V4=mysqli_num_rows($v4);
$V5=mysqli_num_rows($v5);

	
	
	
	
	
	}

else{

$r1=mysqli_query($conn,"select Sr_no from entry  where Category='Student' and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$r2=mysqli_query($conn,"select Sr_no from entry  where Category='Student' and Status='Pending'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$r3=mysqli_query($conn,"select Sr_no from entry where Category='Student' and Status='Approached'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$r4=mysqli_query($conn,"select Sr_no from entry  where Category='Student' and Status='Complete'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$r5=mysqli_query($conn,"select Sr_no from entry  where Category='Student' and Status='Closed'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$R1=mysqli_num_rows($r1);
$R2=mysqli_num_rows($r2);
$R3=mysqli_num_rows($r3);
$R4=mysqli_num_rows($r4);
$R5=mysqli_num_rows($r5);
/* faculty */
$s1=mysqli_query($conn,"select Sr_no from entry  where Category='Faculty'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$s2=mysqli_query($conn,"select Sr_no from entry  where Category='Faculty' and Status='Pending'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$s3=mysqli_query($conn,"select Sr_no from entry where Category='Faculty' and Status='Approached'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$s4=mysqli_query($conn,"select Sr_no from entry  where Category='Faculty' and Status='Complete'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$s5=mysqli_query($conn,"select Sr_no from entry  where Category='Faculty' and Status='Closed'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$S1=mysqli_num_rows($s1);
$S2=mysqli_num_rows($s2);
$S3=mysqli_num_rows($s3);
$S4=mysqli_num_rows($s4);
$S5=mysqli_num_rows($s5);
/* other institutions */
$t1=mysqli_query($conn,"select Sr_no from entry  where Category='Other Institutions'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$t2=mysqli_query($conn,"select Sr_no from entry  where Category='Other Institutions' and Status='Pending'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$t3=mysqli_query($conn,"select Sr_no from entry where Category='Other Institutions' and Status='Approached'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$t4=mysqli_query($conn,"select Sr_no from entry  where Category='Other Institutions' and Status='Complete'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$t5=mysqli_query($conn,"select Sr_no from entry  where Category='Other Institutions' and Status='Closed'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$T1=mysqli_num_rows($t1);
$T2=mysqli_num_rows($t2);
$T3=mysqli_num_rows($t3);
$T4=mysqli_num_rows($t4);
$T5=mysqli_num_rows($t5);
/* Corporate Members */
$u1=mysqli_query($conn,"select Sr_no from entry  where Category='Corporate Members'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$u2=mysqli_query($conn,"select Sr_no from entry  where Category='Corporate Members' and Status='Pending'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$u3=mysqli_query($conn,"select Sr_no from entry where Category='Corporate Members' and Status='Approached'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$u4=mysqli_query($conn,"select Sr_no from entry  where Category='Corporate Members' and Status='Complete'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$u5=mysqli_query($conn,"select Sr_no from entry  where Category='Corporate Members' and Status='Closed'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$U1=mysqli_num_rows($u1);
$U2=mysqli_num_rows($u2);
$U3=mysqli_num_rows($u3);
$U4=mysqli_num_rows($u4);
$U5=mysqli_num_rows($u5);
/* Institution members */
$v1=mysqli_query($conn,"select Sr_no from entry  where Category='Institute Members'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$v2=mysqli_query($conn,"select Sr_no from entry  where Category='Institute Members' and Status='Pending'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$v3=mysqli_query($conn,"select Sr_no from entry where Category='Institute Members' and Status='Approached'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$v4=mysqli_query($conn,"select Sr_no from entry  where Category='Institute Members' and Status='Complete'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$v5=mysqli_query($conn,"select Sr_no from entry  where Category='Institute Members' and Status='Closed'and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$V1=mysqli_num_rows($v1);
$V2=mysqli_num_rows($v2);
$V3=mysqli_num_rows($v3);
$V4=mysqli_num_rows($v4);
$V5=mysqli_num_rows($v5);
}
?>	
			  
<?php 

if(isset($_POST['submit'])){
	
	$category=$_POST['category'];
	$status=$_POST['type'];
	$date1=$_POST['date1'];
	$date2=$_POST['date2'];
	if($date1!='' && $date2!=''){
	$sqla=mysqli_query($conn,"select * from entry where Category='$category' and Status='$status' and Req_date between '$date1' and '$date2' ");
	$sql1count=mysqli_num_rows($sqla);
	}
	else{
	$sqla=mysqli_query($conn,"select * from entry where Category='$category' and Status='$status'");
	$sql1count=mysqli_num_rows($sqla);

	}
	
}



?>
<?php 
	if(isset($_POST['submit'])){ 
?>
   <h3> Report:</h3>
  
	<div class="row">
	<div class="col-md-6">
	From:<span class="badge"><?php echo"$date1" ?></span> <br/>To:<span class="badge"><?php echo"$date2" ?> </span><br/>
	Status:<span class="badge"><?php echo"$status" ?> </span>
	</div>
	<div class="col-md-6">
	Count:<span class="badge"><?php echo"$sql1count" ?></span> <br/>
	Category:<span class="badge"><?php echo"$category" ?></span> <br/>
	</div>
	</div>
	<?php } ?>	
<!--- new thing-->
            <div class="row">
              
               <div class="panel-group">
				<div class="panel panel-default">
				<div class="panel-heading">
				<div class="row">
				<div class="col-md-3">
				Request by Category 
				</div>
				<div class="col-md-9">
				
				<form method="post" action="">
				        <input type="text" id="date" name="date1" placeholder="YYYY/MM/DD" >
						<input type="text" id="date" name="date2" placeholder="YYYY/MM/DD" >

		
						<button type="submit" name="user_submit" class="btn btn-default">
						<i class="glyphicon glyphicon-search"></i>
						</button>
				</form>
				</div>
				</div>
				</div>
				<div class="panel-body">
					<table id="employee_data3" class="table">
					<thead>
						<tr>
							<th>Category</th>
							<th>Total</th>
							<th>Pending</th>
							<th>Approached</th>
							<th>Complete</th>
							<th>Closed</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Student</td>
							<td><?php echo"$R1"; ?></td>
							<td><?php echo"$R2"; ?></td>
							<td><?php echo"$R3"; ?></td>
							<td><?php echo"$R4"; ?></td>
							<td><?php echo"$R5"; ?></td>
						</tr>
						<tr>
							<td>Faculty</td>
							<td><?php echo"$S1"; ?></td>
							<td><?php echo"$S2"; ?></td>
							<td><?php echo"$S3"; ?></td>
							<td><?php echo"$S4"; ?></td>
							<td><?php echo"$S5"; ?></td>
						</tr>
						<tr>
							<td>Other Institutions</td>
							<td><?php echo"$T1"; ?></td>
							<td><?php echo"$T2"; ?></td>
							<td><?php echo"$T3"; ?></td>
							<td><?php echo"$T4"; ?></td>
							<td><?php echo"$T5"; ?></td>
						</tr>
						<tr>
							<td>Corporate Members</td>
							<td><?php echo"$U1"; ?></td>
							<td><?php echo"$U2"; ?></td>
							<td><?php echo"$U3"; ?></td>
							<td><?php echo"$U4"; ?></td>
							<td><?php echo"$U5"; ?></td>
						</tr>
						<tr>
							<td>Institute Members</td>
							<td><?php echo"$V1"; ?></td>
							<td><?php echo"$V2"; ?></td>
							<td><?php echo"$V3"; ?></td>
							<td><?php echo"$V4"; ?></td>
							<td><?php echo"$V5"; ?></td>
						</tr>
					</tbody>
					
					
					
					</table>
				
				
				
				
				</div>
			   </div>
                </div>
              </div>

			  <div class="row">
			 <div class="panel panel-default">
				<div class="panel-heading">
				<div class="row">
				<div class="col-md-3">
				Request by Journals
				</div>
				<div class="col-md-9">
				
				<form method="post" action="">
				        <input type="text" id="date" name="date1" placeholder="YYYY/MM/DD" >
						<input type="text" id="date" name="date2" placeholder="YYYY/MM/DD" >

		
						<button type="submit" name="journal_submit" class="btn btn-default">
						<i class="glyphicon glyphicon-search"></i>
						</button>
				</form>
				</div>
				</div>

				</div>
				<div class="panel-body">
				<table id="employee_data" class="table">
				<thead>
				<th>Sno</th>
				<th>Journal Name</th>
				<th>Count</th>
				<th>Pending</th>
				<th>Approached</th>
				<th>Complete</th>
				<th>Closed</th>
				</thead>
				<?php 
				if(isset($_POST['journal_submit'])){
					$date1=$_POST['date1'];
					$date2=$_POST['date2'];
					$trunc=mysqli_query($conn,"truncate table report_journal");
					$j1=mysqli_query($conn,"select Journal_name,count(*) as count from entry where Req_date between '$date1' and '$date2' group by Journal_name order by Journal_name");
					$i=0;
					while($row=mysqli_fetch_array($j1)){
						$i++;
						$journal=$row['Journal_name'];
						$count=$row['count'];
						$sql2=mysqli_query($conn,"select Sr_no from entry where Req_date between '$date1' and '$date2' and Journal_name='$journal' and Status='Pending'");
						$sql22=mysqli_num_rows($sql2);
						$sql3=mysqli_query($conn,"select Sr_no from entry where Req_date between '$date1' and '$date2' and Journal_name='$journal' and Status='Approached'");
						$sql23=mysqli_num_rows($sql3);
						$sql4=mysqli_query($conn,"select Sr_no from entry where Req_date between '$date1' and '$date2' and Journal_name='$journal' and Status='Complete'");
						$sql24=mysqli_num_rows($sql4);
						$sql5=mysqli_query($conn,"select Sr_no from entry where Req_date between '$date1' and '$date2' and Journal_name='$journal' and Status='Closed'");
						$sql25=mysqli_num_rows($sql5);
						$journal=mysqli_query($conn,"insert into report_journal (Journal_name,Count) values('$journal','$count')");
					?>										
					<tr>
					<td><?php echo"$i";?></td>
					<td><?php echo"$row[Journal_name]";?></td>
					<td><?php echo"$count";?></td>
					<td><?php echo"$sql22";?></td>
					<td><?php echo"$sql23";?></td>
					<td><?php echo"$sql24";?></td>
					<td><?php echo"$sql25";?></td>
					</tr>
					
					<?php 
					}
				}
				
				else
				{
					$trunc=mysqli_query($conn,"truncate table report_journal");
					$j1=mysqli_query($conn,"select Journal_name,count(*) as count from entry  WHERE MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) group by Journal_name order by Journal_name");
					$i=0;
					while($row=mysqli_fetch_array($j1)){
						$i++;
						
						$journal=$row['Journal_name'];
						$count=$row['count'];
						$sql2=mysqli_query($conn,"select Sr_no from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and Journal_name='$journal' and Status='Pending'");
						$sql22=mysqli_num_rows($sql2);
						$sql3=mysqli_query($conn,"select Sr_no from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and Journal_name='$journal' and Status='Approached'");
						$sql23=mysqli_num_rows($sql3);
						$sql4=mysqli_query($conn,"select Sr_no from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and Journal_name='$journal' and Status='Complete'");
						$sql24=mysqli_num_rows($sql4);
						$sql5=mysqli_query($conn,"select Sr_no from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and Journal_name='$journal' and Status='Closed'");
						$sql25=mysqli_num_rows($sql5);
						$journal=mysqli_query($conn,"insert into report_journal (Journal_name,Count) values('$journal','$count')");
					?>
					<tr>
					<td><?php echo"$i";?></td>
					<td><?php echo"$row[Journal_name]";?></td>
					<td><?php echo"$count";?></td>
					<td><?php echo"$sql22";?></td>
					<td><?php echo"$sql23";?></td>
					<td><?php echo"$sql24";?></td>
					<td><?php echo"$sql25";?></td>
					</tr>
					
					<?php 
					}
				}
				
				?>
				</table>
				</div>
				</div>
				</div>
				
				<div class="row">
				<!-- second col-->
				
				
				<div class="panel panel-default">
				<div class="panel-heading">
				<div class="row">
				<div class="col-md-3">
				Request by Libraries
				</div>
				<div class="col-md-9">
				
				<form method="post" action="">
				        <input type="text" id="date" name="date1" placeholder="YYYY/MM/DD" >
						<input type="text" id="date" name="date2" placeholder="YYYY/MM/DD" >

		
						<button type="submit" name="institute_submit" class="btn btn-default">
						<i class="glyphicon glyphicon-search"></i>
						</button>
				</form>
				</div>
				</div>

				</div>

				
				<div class="panel-body">
				<table id="employee_data1" class="table">
				<thead>
				
				<th>Institute Name</th>
				<th>Send Count</th>
				<th>Receive Count</th>
				</thead>
				<tbody>
				<?php 
				if(isset($_POST['institute_submit'])){
					$date1=$_POST['date1'];
					$date2=$_POST['date2'];
				$sql=mysqli_query($conn,"select * from institutions");
							 while($row=mysqli_fetch_array($sql)){
								 
	 $a=$row["institute_name"];
	 $a1=$row["Sr_no"];
	 
	 $req_inst=mysqli_query($conn,"select * from institute_list where institute_name='$a1' and req_date between '$date1' and '$date2'");
	 $req_count=mysqli_num_rows($req_inst);
	 $rec_inst=mysqli_query($conn,"select * from receive_list where institute_name='$a1'and req_date between '$date1' and '$date2'");
	 $rec_count=mysqli_num_rows($rec_inst);
	$update=mysqli_query($conn,"update institutions set send_count='$req_count' , receive_count='$rec_count' where institute_name='$a'");
		  ?>
		  
	 <tr>
	 <td><?php echo"$a";?></td>
	 <td><?php echo"$req_count";?></td>
	 <td><?php echo"$rec_count";?></td>
	 </tr>
				<?php }} 
			
		
			else
			{
					
	$sql=mysqli_query($conn,"select * from institutions");
	 while($row=mysqli_fetch_array($sql)){
								 
	 $a=$row["institute_name"];
	 $a1=$row["Sr_no"];
	 $req_inst=mysqli_query($conn,"select * from institute_list where institute_name='$a1' and MONTH(req_date) = MONTH(CURRENT_DATE) AND YEAR(req_date) = YEAR(CURRENT_DATE)");
	 $req_count=mysqli_num_rows($req_inst);
	 $rec_inst=mysqli_query($conn,"select * from receive_list where institute_name='$a1'and MONTH(req_date) = MONTH(CURRENT_DATE) AND YEAR(req_date) = YEAR(CURRENT_DATE)");
	 $rec_count=mysqli_num_rows($rec_inst);
	$update=mysqli_query($conn,"update institutions set send_count='$req_count' , receive_count='$rec_count' where institute_name='$a'");
	?>
			<tr>
	 <td><?php echo"$a";?></td>
	 <td><?php echo"$req_count";?></td>
	 <td><?php echo"$rec_count";?></td>
	 </tr>		
	 <?php	}	}
	?>
 	
	

		</tbody>
	</table>		
				
				
				
				
				
				
				</div>
				
			</div>
			</div>
			
<!-- next col -->
			  <div class="row">
			 <div class="panel panel-default">
				<div class="panel-heading">
				<div class="row">
				<div class="col-md-3">
				Request by Document Type
				</div>
				<div class="col-md-9">
				
				<form method="post" action="">
				        <input type="text" id="date" name="date1" placeholder="YYYY/MM/DD" >
						<input type="text" id="date" name="date2" placeholder="YYYY/MM/DD" >

		
						<button type="submit" name="doctype_submit" class="btn btn-default">
						<i class="glyphicon glyphicon-search"></i>
						</button>
				</form>
				</div>
				</div>

				</div>
				<div class="panel-body">
				<table id="employee_data9" class="table">
				<thead>
				<th>Sno</th>
				<th>Document Type</th>
				<th>Count</th>
				<th>Pending</th>
				<th>Approached</th>
				<th>Complete</th>
				<th>Closed</th>
				</thead>
				<?php 
				if(isset($_POST['doctype_submit'])){
					$date1=$_POST['date1'];
					$date2=$_POST['date2'];
					
					$j1=mysqli_query($conn,"select Document_type,count(*) as count from entry where Req_date between '$date1' and '$date2' group by Document_type order by Document_type");
					$i=0;
					while($row=mysqli_fetch_array($j1)){
						$i++;
						$doctype=$row['Document_type'];
						$count=$row['count'];
						$sql2=mysqli_query($conn,"select Sr_no from entry where Req_date between '$date1' and '$date2' and Document_type='$doctype' and Status='Pending'");
						$sql22=mysqli_num_rows($sql2);
						$sql3=mysqli_query($conn,"select Sr_no from entry where Req_date between '$date1' and '$date2' and Document_type='$doctype' and Status='Approached'");
						$sql23=mysqli_num_rows($sql3);
						$sql4=mysqli_query($conn,"select Sr_no from entry where Req_date between '$date1' and '$date2' and Document_type='$doctype' and Status='Complete'");
						$sql24=mysqli_num_rows($sql4);
						$sql5=mysqli_query($conn,"select Sr_no from entry where Req_date between '$date1' and '$date2' and Document_type='$doctype' and Status='Closed'");
						$sql25=mysqli_num_rows($sql5);
						
					?>										
					<tr>
					<td><?php echo"$i";?></td>
					<td><?php echo"$row[Document_type]";?></td>
					<td><?php echo"$count";?></td>
					<td><?php echo"$sql22";?></td>
					<td><?php echo"$sql23";?></td>
					<td><?php echo"$sql24";?></td>
					<td><?php echo"$sql25";?></td>
					</tr>
					
					<?php 
					}
				}
				
				else
				{
					
					$j1=mysqli_query($conn,"select Document_type,count(*) as count from entry  WHERE MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) group by Document_type order by Document_type");
					$i=0;
					while($row=mysqli_fetch_array($j1)){
						$i++;
						
						$doctype=$row['Document_type'];
						$count=$row['count'];
						$sql2=mysqli_query($conn,"select Sr_no from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and Document_type='$doctype' and Status='Pending'");
						$sql22=mysqli_num_rows($sql2);
						$sql3=mysqli_query($conn,"select Sr_no from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and Document_type='$doctype' and Status='Approached'");
						$sql23=mysqli_num_rows($sql3);
						$sql4=mysqli_query($conn,"select Sr_no from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and Document_type='$doctype' and Status='Complete'");
						$sql24=mysqli_num_rows($sql4);
						$sql5=mysqli_query($conn,"select Sr_no from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and Document_type='$doctype' and Status='Closed'");
						$sql25=mysqli_num_rows($sql5);
						
					?>
					<tr>
					<td><?php echo"$i";?></td>
					<td><?php echo"$row[Document_type]";?></td>
					<td><?php echo"$count";?></td>
					<td><?php echo"$sql22";?></td>
					<td><?php echo"$sql23";?></td>
					<td><?php echo"$sql24";?></td>
					<td><?php echo"$sql25";?></td>
					</tr>
					
					<?php 
					}
				}
				
				?>
				</table>
				</div>
				</div>
				</div>
			
			<div class="row">
			
			<div class="panel panel-default">
				<div class="panel-heading">
				<div class="row">
				<div class="col-md-3">
				Request by Users
				</div>
				<div class="col-md-9">
				
				<form method="post" action="">
				        <input type="text" id="date" name="date1" placeholder="YYYY/MM/DD" >
						<input type="text" id="date" name="date2" placeholder="YYYY/MM/DD" >

		
						<button type="submit" name="user_submit" class="btn btn-default">
						<i class="glyphicon glyphicon-search"></i>
						</button>
				</form>
				</div>
				</div>
				</div>
				<div class="panel-body">
				<table id="employee_data2" class="table">
				<thead>
				
				<th>Sno</th>
				<th>Patron</th>
				<th>Count</th>
				<th>Pending</th>
				<th>Approached</th>
				<th>Complete</th>
				<th>Closed</th>
				</thead>
				<tbody>
				<?php 
				if(isset($_POST['user_submit'])){
					$date1=$_POST['date1'];
					$date2=$_POST['date2'];
                $i=0;          
				$sql=mysqli_query($conn,"select Req_by,count(Req_by) as count from entry where Req_date between '$date1' and '$date2' group by Req_by order by Req_by");
							 while($row=mysqli_fetch_array($sql)){
								$req_by=$row['Req_by'];
								$req=mysqli_query($conn,"select Display_name from patrons where Sr_no='$req_by'");
								$row1=mysqli_fetch_array($req);
								$req_by1=$row1['Display_name'];
								$a=$req_by1;
								$i++;
								$b=$row["count"];
								$sql2=mysqli_query($conn,"select Sr_no from entry where Req_date between '$date1' and '$date2' and Req_by='$req_by' and Status='Pending'");
						$sql22=mysqli_num_rows($sql2);
						$sql3=mysqli_query($conn,"select Sr_no from entry where Req_date between '$date1' and '$date2' and Req_by='$req_by' and Status='Approached'");
						$sql23=mysqli_num_rows($sql3);
						$sql4=mysqli_query($conn,"select Sr_no from entry where Req_date between '$date1' and '$date2' and Req_by='$req_by' and Status='Complete'");
						$sql24=mysqli_num_rows($sql4);
						$sql5=mysqli_query($conn,"select Sr_no from entry where Req_date between '$date1' and '$date2' and Req_by='$req_by' and Status='Closed'");
						$sql25=mysqli_num_rows($sql5);
	 
	 
						?>
				<tr>
				<td><?php echo"$i";?></td>
				<td><?php echo"$a";?></td>
				<td><?php echo"$b";?></td>
				<td><?php echo"$sql22";?></td>
					<td><?php echo"$sql23";?></td>
					<td><?php echo"$sql24";?></td>
					<td><?php echo"$sql25";?></td>
	 
				</tr>
	
				<?php
				}}

				else{
		         $i=0;          
				$sql=mysqli_query($conn,"select Req_by,count(*) as count from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) group by Req_by order by Req_by");
				while($row=mysqli_fetch_array($sql)){
				 $req_by=$row['Req_by'];
								$req=mysqli_query($conn,"select Display_name from patrons where Sr_no='$req_by'");
								$row1=mysqli_fetch_array($req);
								$req_by1=$row1['Display_name'];
								$a=$req_by1;
				$i++;
				$b=$row["count"];
				$sql2=mysqli_query($conn,"select Sr_no from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and Req_by='$req_by' and Status='Pending'");
						$sql22=mysqli_num_rows($sql2);
						$sql3=mysqli_query($conn,"select Sr_no from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and Req_by='$req_by' and Status='Approached'");
						$sql23=mysqli_num_rows($sql3);
						$sql4=mysqli_query($conn,"select Sr_no from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and Req_by='$req_by' and Status='Complete'");
						$sql24=mysqli_num_rows($sql4);
						$sql5=mysqli_query($conn,"select Sr_no from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and Req_by='$req_by' and Status='Closed'");
						$sql25=mysqli_num_rows($sql5);
	 
				?>
				<tr>
				<td><?php echo"$i";?></td>
				<td><?php echo"$a";?></td>
				<td><?php echo"$b";?></td>
				<td><?php echo"$sql22";?></td>
					<td><?php echo"$sql23";?></td>
					<td><?php echo"$sql24";?></td>
					<td><?php echo"$sql25";?></td>
	 
				</tr>
	
				<?php
				}
				}	

				?>
		</tbody>
	</table>		
					</div>
			</div>
			
			
			</div>
<!-- next one -->
			<div class="row">
			<div class="panel panel-default">
				<div class="panel-heading">
				<div class="row">
				<div class="col-md-3">
				Report on average time
				</div>
				<div class="col-md-9">
				
				<form method="post" action="">
				        <input type="text" id="date" name="date1" placeholder="YYYY/MM/DD" >
						<input type="text" id="date" name="date2" placeholder="YYYY/MM/DD" >

		
						<button type="submit" name="average_submit" class="btn btn-default">
						<i class="glyphicon glyphicon-search"></i>
						</button>
				</form>
				</div>
				</div>
				</div>
				<div class="panel-body">
				<table id="employee_data6" class="table">
				<thead>
				
				<th>Sno</th>
				<th>Patron</th>
				
				<th>No. of days</th>
				</thead>
				<tbody>
				<?php 
				if(isset($_POST['average_submit'])){
					$date1=$_POST['date1'];
					$date2=$_POST['date2'];
				$i=0;          
				$sql=mysqli_query($conn,"SELECT Req_by,DATEDIFF(`Sent_date`, `entry_date`) as diff from entry where Req_date between '$date1' and '$date2' ");
				while($row=mysqli_fetch_array($sql)){
						$req_by=$row['Req_by'];
								$req=mysqli_query($conn,"select Display_name from patrons where Sr_no='$req_by'");
								$row1=mysqli_fetch_array($req);
								$req_by1=$row1['Display_name'];
								$a=$req_by1;
							$elapsed=$row['diff'];
							$i++;	
						?>
				<tr>
				<td><?php echo"$i";?></td>
				<td><?php echo"$a";?></td>
				<td><?php echo"$elapsed";?></td>
				
				</tr>
	
				<?php
				}
				}	

				
				else{
		         $i=0;          
				$sql=mysqli_query($conn,"SELECT Req_by,DATEDIFF(`Sent_date`, `entry_date`) as diff from entry where  MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) ");
				while($row=mysqli_fetch_array($sql)){
						$req_by=$row['Req_by'];
								$req=mysqli_query($conn,"select Display_name from patrons where Sr_no='$req_by'");
								$row1=mysqli_fetch_array($req);
								$req_by1=$row1['Display_name'];
								$a=$req_by1;
							$elapsed=$row['diff'];
							$i++;	
						?>
				<tr>
				<td><?php echo"$i";?></td>
				<td><?php echo"$a";?></td>
				<td><?php echo"$elapsed";?></td>
				
				</tr>
	
				<?php
				}
				}	

				?>
		</tbody>
	</table>		
					</div>
			</div>
			
			
			</div>
<!-- next col -->
			  <div class="row">
			 <div class="panel panel-default">
				<div class="panel-heading">
				<div class="row">
				<div class="col-md-3">
				Request by Discipline
				</div>
				<div class="col-md-9">
				
				<form method="post" action="">
				        <input type="text" id="date" name="date1" placeholder="YYYY/MM/DD" >
						<input type="text" id="date" name="date2" placeholder="YYYY/MM/DD" >

		
						<button type="submit" name="discipline_submit" class="btn btn-default">
						<i class="glyphicon glyphicon-search"></i>
						</button>
				</form>
				</div>
				</div>

				</div>
				<div class="panel-body">
				<table id="employee_data7" class="table">
				<thead>
				<th>Sno</th>
				<th>Discipline Name</th>
				<th>Count</th>
				<th>Pending</th>
				<th>Approached</th>
				<th>Complete</th>
				<th>Closed</th>
				</thead>
				<?php 
				if(isset($_POST['discipline_submit'])){
					$date1=$_POST['date1'];
					$date2=$_POST['date2'];
					
					$j1=mysqli_query($conn,"select Discipline,count(*) as count,patrons.Sr_no as pat from entry,patrons where Req_date between '$date1' and '$date2'and entry.Req_by=patrons.Sr_no  group by Discipline order by Discipline");
					$i=0;
					while($row=mysqli_fetch_array($j1)){
						$i++;
						$discipline=$row['Discipline'];
						if($discipline!=''){
						$count=$row['count'];
						$sr_no=$row['pat'];
						
						$sql2=mysqli_query($conn,"select * from entry where Req_date between '$date1' and '$date2' and entry.Req_by=$sr_no and Status='Pending'");
						$sql22=mysqli_num_rows($sql2);
						$sql3=mysqli_query($conn,"select * from entry where Req_date between '$date1' and '$date2' and entry.Req_by=$sr_no and Status='Approached'");
						$sql23=mysqli_num_rows($sql3);
						$sql4=mysqli_query($conn,"select * from entry where Req_date between '$date1' and '$date2' and entry.Req_by=$sr_no and Status='Complete'");
						$sql24=mysqli_num_rows($sql4);
						$sql5=mysqli_query($conn,"select * from entry where Req_date between '$date1' and '$date2' and entry.Req_by=$sr_no and Status='Closed'");
						$sql25=mysqli_num_rows($sql5);
						
					?>										
					<tr>
					<td><?php echo"$i";?></td>
					<td><?php echo"$row[Discipline]";?></td>
					<td><?php echo"$count";?></td>
					<td><?php echo"$sql22";?></td>
					<td><?php echo"$sql23";?></td>
					<td><?php echo"$sql24";?></td>
					<td><?php echo"$sql25";?></td>
					</tr>
					
					<?php 
					}
				}}
				
				else
				{
					
					
					$j1=mysqli_query($conn,"select Discipline,count(*) as count,patrons.Sr_no as pat from entry,patrons where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and entry.Req_by=patrons.Sr_no  group by Discipline order by Discipline");
					$i=0;
					while($row=mysqli_fetch_array($j1)){
						$i++;
						$discipline=$row['Discipline'];
						if($discipline!=''){
						$count=$row['count'];
						$sr_no=$row['pat'];
						$count=$row['count'];
						$sql2=mysqli_query($conn,"select * from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and entry.Req_by=$sr_no and Status='Pending'");
						$sql22=mysqli_num_rows($sql2);
						$sql3=mysqli_query($conn,"select * from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and entry.Req_by=$sr_no and Status='Approached'");
						$sql23=mysqli_num_rows($sql3);
						$sql4=mysqli_query($conn,"select * from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and entry.Req_by=$sr_no and Status='Complete'");
						$sql24=mysqli_num_rows($sql4);
						$sql5=mysqli_query($conn,"select * from entry where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and entry.Req_by=$sr_no and Status='Closed'");
						$sql25=mysqli_num_rows($sql5);
						

						
						
					?>										
					<tr>
					<td><?php echo"$i";?></td>
					<td><?php echo"$discipline";?></td>
					<td><?php echo"$count";?></td>
					<td><?php echo"$sql22";?></td>
					<td><?php echo"$sql23";?></td>
					<td><?php echo"$sql24";?></td>
					<td><?php echo"$sql25";?></td>
					</tr>
									
					<?php 
					}
				}
				}
				?>
				</table>
				</div>
				</div>
				</div>
					
<!-- next col -->
			  <div class="row">
			 <div class="panel panel-default">
				<div class="panel-heading">
				<div class="row">
				<div class="col-md-3">
				Request by Program Name
				</div>
				<div class="col-md-9">
				
				<form method="post" action="">
				        <input type="text" id="date" name="date1" placeholder="YYYY/MM/DD" >
						<input type="text" id="date" name="date2" placeholder="YYYY/MM/DD" >

		
						<button type="submit" name="program_submit" class="btn btn-default">
						<i class="glyphicon glyphicon-search"></i>
						</button>
				</form>
				</div>
				</div>

				</div>
				<div class="panel-body">
				<table id="employee_data8" class="table">
				<thead>
				<th>Sno</th>
				<th>Program Name</th>
				<th>Count</th>
				
				</thead>
				<?php 
				if(isset($_POST['program_submit'])){
					$date1=$_POST['date1'];
					$date2=$_POST['date2'];
					
					$j1=mysqli_query($conn,"select Program_name,count(*) as count,patrons.Sr_no as pat from entry,patrons where Req_date between '$date1' and '$date2'and entry.Req_by=patrons.Sr_no  group by Program_name order by Program_name");
					$i=0;
					while($row=mysqli_fetch_array($j1)){
						$i++;
						$program=$row['Program_name'];
						if($program!=''){
						$count=$row['count'];
						$sr_no=$row['pat'];
					?>										
					<tr>
					<td><?php echo"$i";?></td>
					<td><?php echo"$row[Program_name]";?></td>
					<td><?php echo"$count";?></td>
					
					</tr>
					
					<?php 
					}
				}}
				
				else
				{
					
					
					$j1=mysqli_query($conn,"select Program_name,count(*) as count,patrons.Sr_no as pat from entry,patrons where MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE) and entry.Req_by=patrons.Sr_no  group by Program_name	order by Program_name");
					$i=0;
					while($row=mysqli_fetch_array($j1)){
						$i++;
						$program=$row['Program_name'];
						if($program!=''){
						$count=$row['count'];
						$sr_no=$row['pat'];
						$count=$row['count'];
					
					?>										
					<tr>
					<td><?php echo"$i";?></td>
					<td><?php echo"$program";?></td>
					<td><?php echo"$count";?></td>
					
					<!--<td><?php echo"$sql22";?></td>
					<td><?php echo"$sql23";?></td>
					<td><?php echo"$sql24";?></td>
					<td><?php echo"$sql25";?></td>-->
					</tr>
									
					<?php 
					}
				}
				}
				?>
				</table>
				</div>
				</div>
				</div>
		
 
    <!-- Custom Theme Scripts -->
    <script src="build/js/custom.min.js"></script>
	
  </body>
</html>
<script>
	$(document).ready(function(){  
      
 }); 

</script>