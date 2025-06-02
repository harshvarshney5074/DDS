<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DDS | Login</title>

  <!-- Bootstrap 5.3.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome 6.5 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Google Fonts: Roboto -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="logincss/css/style.css">

</head>
<body>

<div class="pen-title">
  <h2>
    <img src="image/iitgn.png" style="width:75px; height:50px;">
    DOCUMENT DELIVERY SERVICES
  </h2>
</div>

<div class="form-module">
  <div class="form">
    <h2 class="text-center">Login</h2>
    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit" name="login">Login</button>
    </form>
    <div class="cta">
      <a href="#">Forgot your password?</a>
    </div>
  </div>
</div>

<!-- jQuery 3.7.1 -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5.3.3 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="logincss/js/index.js"></script>

</body>
</html>

<?php
include('dbcon.php');
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $login = mysqli_query($conn, "SELECT * FROM user WHERE username='$username' AND password='$password'") or die(mysqli_error($conn));
    $count = mysqli_num_rows($login);

    if ($count > 0) {
        $row = mysqli_fetch_array($login);
        mysqli_query($conn, "UPDATE user SET last_login=NOW() WHERE username='$username'");
        session_start();
        $_SESSION["uid"] = $row["username"];
        $_SESSION["type"] = $row["user_type"];
        header('location:index.php');
    } else {
        echo "<div class='text-center alert alert-danger mt-4'>Error logging in! Please check your username or password.</div>";
    }
}
?>
