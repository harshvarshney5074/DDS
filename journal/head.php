<!DOCTYPE html>
<?php 


?>
<html lang="en">
<head>
  <title>IITGN</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
    <form method="post" action="index1.php" class="navbar-form navbar-right">
      
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
  


</body>
</html>
