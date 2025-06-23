<?php  
session_start();
include('dbcon.php');
$query = "SELECT * FROM user";
$result = mysqli_query($conn, $query);
?>  
<!DOCTYPE html>
<html lang="en">  
<head>  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DDS - User Management</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- jQuery 3.7.1 -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <style>
    body {
      background-color: lightblue;
    }
  </style>
</head>  

<body>  
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="../home.php">Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" title="navbar-toggler">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="../index.php">Entries</a></li>
        <?php if ($_SESSION['type'] == '0' || $_SESSION['type'] == '1') { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="manageDropdown" role="button" data-bs-toggle="dropdown">
              Manage
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="../institutions/index.php">Institutions</a></li>
              <li><a class="dropdown-item" href="../journal/index.php">Document Sources</a></li>
              <li><a class="dropdown-item" href="../patrons/index.php">Patrons</a></li>
            </ul>
          </li>
        <li class="nav-item"><a class="nav-link" href="../orders.php">Requests</a></li>
        <?php } ?>
        <li class="nav-item"><a class="nav-link" href="../reports/index.php">Reports</a></li>
        <?php if ($_SESSION['type'] == '0') { ?>
        <li class="nav-item"><a class="nav-link" href="../users/index.php">Users</a></li>
        <?php } ?>
      </ul>
      <ul class="navbar-nav">
        <?php if (isset($_SESSION['uid'])): ?>
          <li class="nav-item"><a class="nav-link">User <?php echo $_SESSION['uid']; ?></a></li>
          <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<main class="container mt-5 pt-4">
  <h1 class="text-center mt-4">User Details</h1>
  <div class="mb-3 text-end">
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#add_data_Modal">Add User</button>
  </div>

  <div id="employee_table">
    <table id="employee_data" class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>Username</th>
          <th>Usertype</th>
          <th>Last Login</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_array($result)) {
          $utype = match ($row["user_type"]) {
            "0" => "Admin",
            "1" => "User",
            default => "Normal User"
          };
        ?>
          <tr>
            <td><?= $row["username"]; ?></td>
            <td><?= $utype; ?></td>
            <td><?= $row["last_login"]; ?></td>
            <td>
              <a href="edit11.php?edit_record=<?= $row["user_id"]; ?>" class="btn btn-info btn-sm">Change</a>
              <button class="btn btn-danger btn-sm delete_data" id="<?= $row["user_id"]; ?>">Delete</button>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</main>

<!-- Add Modal -->
<div class="modal fade" id="add_data_Modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" id="insert_form" onsubmit="return validateForm();">
        <div class="modal-header">
          <h5 class="modal-title">User Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" title="close-btn"></button>
        </div>
        <div class="modal-body">
          <label for="user_name">UserName</label>
          <input type="text" name="user_name" id="user_name" class="form-control" required>
          <br />
          <label>UserType</label>
          <select name="user_type" class="form-control" title="user_type">
            <option value="0">Admin</option>
            <option value="1">User</option>
            <option value="2">Normal User</option>
          </select>
          <br />
          <label for="password">Password</label>
          <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="modal-footer">
          <input type="submit" name="insert" value="Insert" class="btn btn-success">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="dataModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">User Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" title="close-btn"></button>
      </div>
      <div class="modal-body" id="employee_detail"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $('#employee_data').DataTable();

  $(document).on('click', '.delete_data', function() {
    var user_id = $(this).attr("id");
    if (user_id != '') {
      $.ajax({
        url: "delete.php",
        method: "POST",
        data: { user_id: user_id },
        success: function(data) {
          $('#employee_detail').html(data);
          $('#dataModal').modal('show');
        }
      });
    }
  });
});
</script>

<?php
if (!empty($_POST)) {
  include('dbcon.php');
  $uname = $_POST['user_name'];
  $utype = $_POST['user_type'];
  $plainPassword = $_POST['password'];

  // Secure password hash
  $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

  $check = mysqli_query($conn, "SELECT * FROM user WHERE username='$uname'");
  if (mysqli_num_rows($check) > 0) {
    echo "<script>alert('This username already exists.')</script>";
  } else {
    $sql = mysqli_query($conn, "INSERT INTO user (username, user_type, password) VALUES ('$uname', '$utype', '$hashedPassword')");
    if ($sql) {
      echo "<script>alert('Successfully added')</script>";
      echo "<script>window.open('index.php','_self')</script>";
    } else {
      echo "<script>alert('Error occurred during insertion')</script>";
    }
  }
}
?>
</body>
</html>
