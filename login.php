<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>DDS|Login</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>

      <link rel="stylesheet" href="logincss/css/style.css">

  
</head>

<body>
  
<!-- Form Mixin-->
<!-- Input Mixin-->
<!-- Button Mixin-->
<!-- Pen Title-->
<div class="pen-title">
  <h2><img src="image/iitgn.png" style='width:75px; height:50px'>DOCUMENT DELIVERY SERVICES</h2>
</div>
<!-- Form Module-->
<div class="module form-module">
  <div>
    
  </div>
  <div class="form">
    <h2><center>Login </h2>
    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username"/>
      <input type="password" name="password" placeholder="Password"/>
      
	  <button type="submit" name="login">Login</button>
    </form>
  </div>
  
  <div class="cta"><a href="#">Forgot your password?</a></div>
</div>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script src="logincss/js/index.js"></script>

</body>
</html>
<?php
		include('dbcon.php');
		if (isset($_POST['login'])){
		$username=$_POST['username'];
		$password=md5($_POST['password']);
		
		$login=mysqli_query($conn,"select * from user where username='$username' and password='$password'")or die(mysql_error());
		$count=mysqli_num_rows($login);
		
		if ($count > 0){
		$row = mysqli_fetch_array($login);
			$login=mysqli_query($conn,"update user set last_login=now() where username='$username'");
			session_start();
			$_SESSION["uid"]= $row["username"];
			$_SESSION["type"]=$row["user_type"];
			
		header('location:index.php');
		}
		else{
		?>
		<center>
		<div class="alert alert-error">Error login! Please check your username or password</div>
		</center>
		<?php
		}}
		?>
		