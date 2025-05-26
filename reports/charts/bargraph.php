<?php 
include('dbcon.php');
include('head.php');


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

	
	
$query1=mysqli_query($conn,"select Status from entry where Status='Pending' and Req_date between '$date1' and '$date2'");
$count1=mysqli_num_rows($query1);
$query2=mysqli_query($conn,"select Sr_no from entry where Status='Approached' and Req_date between '$date1' and '$date2'");
$count2=mysqli_num_rows($query2);
$query3=mysqli_query($conn,"select Sr_no from entry where Status='Complete'  and Req_date between '$date1' and '$date2'");
$count3=mysqli_num_rows($query3);
$query4=mysqli_query($conn,"select Sr_no from entry where Status='Closed' and Req_date between '$date1' and '$date2'");
$count4=mysqli_num_rows($query4);	
	
	
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



$query1=mysqli_query($conn,"select Status from entry where Status='Pending' and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$count1=mysqli_num_rows($query1);
$query2=mysqli_query($conn,"select Sr_no from entry where Status='Approached' and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$count2=mysqli_num_rows($query2);
$query3=mysqli_query($conn,"select Sr_no from entry where Status='Complete' and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$count3=mysqli_num_rows($query3);
$query4=mysqli_query($conn,"select Sr_no from entry where Status='Closed' and MONTH(Req_date) = MONTH(CURRENT_DATE) AND YEAR(Req_date) = YEAR(CURRENT_DATE)");
$count4=mysqli_num_rows($query4);
}
?>	

<head>
<title>Report</title>
<style>
#chart-container{
	width:100%;
	height:auto;
	
}



</style>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <!-- Include Date Range Picker -->
			<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
 
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
</script>
 
	 <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Status', 'Count'],
          ['Pending',    <?php echo "$count1";?>],
          ['Approached',       <?php echo "$count2";?>],
          ['Complete',   <?php echo "$count3";?>],
          ['Closed', <?php echo "$count4";?>]
          
        ]);

        var options = {
          title: 'Status-wise Statistics',
		  is3D:true
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
   <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Category', 'Queries'],
          ['Student',    <?php echo"$R1";?>],
          ['Faculty',       <?php echo"$S1";?>],
          ['Other Institutions',   <?php echo"$T1";?>],
          ['Corporate Members', <?php echo"$U1";?>],
          ['Institute Members',     <?php echo"$V1";?>]
        ]);

        var options = {
          title: 'Category-wise Statistics',
		  is3D:true
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart1'));

        chart.draw(data, options);
      }
    </script>

</head>

 

<body >
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
	  <li><a href="../index.php">Reports</a></li>
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

<br/><br/><br/>
<center>
<div class="row">
<div class="col-md-6">
<div class="panel panel-default">
  <div class="panel-heading">Report based on Institutions</div>
  <div class="panel-body">
	<div id="chart-container">
	<canvas id="mycanvas1"></canvas>
	</div>
  </div>
</div>
</div>
<div class="col-md-6">
<div class="panel panel-default">
  <div class="panel-heading">Report based on Journals</div>
  <div class="panel-body">
	<div id="chart-container">
	<canvas id="mycanvas2"></canvas>
	</div>
  </div>
</div>
</div>
</div>

<!--
				<form method="post" action="">
				        <input type="text" id="date" name="date1" placeholder="YYYY/MM/DD" >
						<input type="text" id="date" name="date2" placeholder="YYYY/MM/DD" >

		
						<button type="submit" name="user_submit" class="btn btn-default">
						<i class="glyphicon glyphicon-search"></i>
						</button>
				</form>
		-->
 <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-12">
			  <div class="panel-group">
			  <div class="panel panel-default">
			  <div class="panel-heading">Report based on Status</div>
			  <div class="panel-body">
                <div id="piechart" style="width: 600px; height: 270px;"></div>
                </div>
				</div>
				
              </div>
			  </div>
			  <div class="col-md-6 col-sm-6 col-xs-12">
			  <div class="panel-group">
			  <div class="panel panel-default">
			  <div class="panel-heading">Report based on Category</div>
			  <div class="panel-body">
                <div id="piechart1" style="width: 600px; height: 270px;"></div>
                </div>
				</div>
				</div>
              </div>
			  </div>
</div>
<div class="row">
<div class="col-md-6">
<div class="panel panel-default">
  <div class="panel-heading">Report based on Document-type</div>
  <div class="panel-body">
	<div id="chart-container">
	<canvas id="mycanvas3"></canvas>
	</div>
  </div>
</div>
</div>
<div class="col-md-6">
<div class="panel panel-default">
  <div class="panel-heading">Report based on Discipline</div>
  <div class="panel-body">
	<div id="chart-container">
	<canvas id="mycanvas5"></canvas>
	</div>
  </div>
</div>
</div>
</div>
<div class="row">
<div class="col-md-6">
<div class="panel panel-default">
  <div class="panel-heading">Report based on Program Name</div>
  <div class="panel-body">
	<div id="chart-container">
	<canvas id="mycanvas4"></canvas>
	</div>
  </div>
</div>
</div>
</div>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/Chart.min.js"></script>

<script type="text/javascript" src="js/app1.js"></script>
<script type="text/javascript" src="js/app2.js"></script>
<script type="text/javascript" src="js/app3.js"></script>
<script type="text/javascript" src="js/app4.js"></script>
<script type="text/javascript" src="js/app5.js"></script>
</body>
