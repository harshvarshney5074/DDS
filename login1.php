<?php 
include('header.php');
include('dbcon.php');
?>
<body>
<br><br>

 
	<div class="container">

	<div class="alert alert-info" role="alert">
		<a href="index.php" style="text-decoration:none;">
			<button class="btn btn-primary active">
				Log In
			</button>
		</a>
		<a href="register_user.php" style="text-decoration:none;">
			<button class="btn btn-default">
				Register User
			</button>
		</a>
		<a href="user_list.php" style="text-decoration:none;">
			<button class="btn btn-default">
				User List
			</button>
		</a>
	</div>
	
	<div class="row-fluid">
		<div class="span6" style="margin-left: 220px;">
		<div class="alert alert-success">Log In</div>
		<form class="form-horizontal" method="POST">
		<div class="control-group">
		<label class="control-label" for="inputEmail">Username</label>
		<div class="controls">
		<input type="text" id="inputEmail" name="username" placeholder="Username" required>
		</div>
		</div>
		<div class="control-group">
		<label class="control-label" for="inputPassword">Password</label>
		<div class="controls">
		<input type="password" name="password" id="inputPassword" placeholder="Password" required>
		</div>
		</div>
		<div class="control-group">
		<div class="controls">
		<button type="submit" name="login" class="btn btn-primary">Login</button>
		</div>
		<br>
		<?php
		if (isset($_POST['login'])){
		$username=$_POST['username'];
		$password=md5($_POST['password']);
		
		$login=mysqli_query($conn,"select * from user where username='$username' and password='$password'")or die(mysql_error());
		$count=mysqli_num_rows($login);
		
		if ($count > 0){
		$row = mysqli_fetch_array($login);
			
			session_start();
			$_SESSION["uid"]= $row["username"];
			$_SESSION["type"]=$row["user_type"];
			
		header('location:index.php');
		}else{ ?>
		<div class="alert alert-error">Error login! Please check your username or password</div>
		<?php
		}}
		?>
		
		</div>
		</form>
		</div>
	</div>
		
	</div>

</body>
</html>