<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DDS | Login</title>

  <!-- Bootstrap 5.3.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900" rel="stylesheet">
  <link rel="stylesheet" href="logincss/css/style.css">
</head>
<body>

<div class="form-module">
  <div class="form">
    <div class="text-center mb-3">
      <img src="image/iitgn_lib_logo.png" alt="IITGN Logo" style="width:400px; height:80px;">
      <h4 class="mt-4 mb-0">DOCUMENT DELIVERY SERVICES (DDS)</h4>
    </div>
    <h2 class="text-center">Login</h2>
    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit" name="login">Login</button>
    </form>
  </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="logincss/js/index.js"></script>

</body>
</html>

<?php
include('dbcon.php');

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $input_password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'") or die(mysqli_error($conn));

    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $stored_hash = $row['password'];

        $md5_input = md5($input_password);
        $login_success = false;

        // Modern password hash check
        if (password_verify($input_password, $stored_hash)) {
            $login_success = true;

        // Fallback for legacy MD5 passwords
        } elseif ($md5_input === $stored_hash) {
            $login_success = true;

            // Upgrade to secure password hash
            $new_hash = password_hash($input_password, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE user SET password='$new_hash' WHERE username='$username'");
        }

        if ($login_success) {
            session_start();
            $_SESSION["uid"] = $row["username"];
            $_SESSION["type"] = $row["user_type"];
            mysqli_query($conn, "UPDATE user SET last_login=NOW() WHERE username='$username'");
            header('location:home.php');
            exit;
        } else {
            echo "<div class='text-center alert alert-danger mt-4'>Invalid password.</div>";
        }
    } else {
        echo "<div class='text-center alert alert-danger mt-4'>User not found.</div>";
    }
}
?>
