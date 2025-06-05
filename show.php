<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DDS - Order Details</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

  <!-- Datepicker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css"/>

  <style>
    .table {
      width: 80%;
      margin: auto;
    }
    body {
      padding-top: 70px; /* account for fixed navbar */
    }
  </style>
</head>
<body>

<?php
session_start(); // Make sure session is started before accessing $_SESSION
?>

<!-- Navbar from show3.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">IITGN</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if ($_SESSION['type'] === '0' || $_SESSION['type'] === '1'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Manage</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="institutions/index.php">Institutions</a></li>
              <li><a class="dropdown-item" href="journal/index.php">Journals</a></li>
              <li><a class="dropdown-item" href="patrons/index.php">Patrons</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link" href="biblo_search1.php">Search</a></li>
          <li class="nav-item"><a class="nav-link" href="orders.php">Orders</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="reports/index.php">Reports</a></li>
        <?php if ($_SESSION['type'] === '0'): ?>
          <li class="nav-item"><a class="nav-link" href="users/index.php">Settings</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['uid'])): ?>
          <li class="nav-item"><a class="nav-link">User <?= $_SESSION['uid'] ?></a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<?php
include('dbcon.php');

if (isset($_GET['order_no'])) {
  $order_no = $_GET['order_no'];
  $user = mysqli_query($conn, "SELECT * FROM orders WHERE order_id='$order_no'");
  $row = mysqli_fetch_array($user);
  $user_email = $row['username'];
  $username = $row['Display_name'];

  $sql = mysqli_query($conn, "SELECT * FROM entry WHERE order_id='$order_no'");
  $num = mysqli_num_rows($sql);

  $sql1 = mysqli_query($conn, "SELECT * FROM entry WHERE order_id='$order_no' AND Status!='Complete'");
  $num1 = mysqli_num_rows($sql1);
?>

<div class="container py-5">
  <h3 class="text-center mb-4">Basket Number - <?php echo $order_no; ?></h3>

  <form action="show3.php" method="post">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th scope="col">Send</th>
          <th scope="col">Sr No</th>
          <th scope="col">Bibliographic Details</th>
          <th scope="col">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_array($sql)) { ?>
          <tr>
            <td><input type="checkbox" name="send[]" value="<?php echo $row['Sr_no']; ?>"></td>
            <td><?php echo $row['Sr_no']; ?></td>
            <td><?php echo $row['Bibliographic_details']; ?></td>
            <td><?php echo $row['Status']; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>

    <input type="hidden" name="order_no" value="<?php echo $order_no; ?>">
    <input type="hidden" name="useremail" value="<?php echo $user_email; ?>">
    <input type="hidden" name="username" value="<?php echo $username; ?>">
    
    <div class="text-center mt-4">
      <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>
</div>

<?php } ?>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<script>
  $(document).ready(function () {
    $('.table').DataTable();
  });

  document.querySelector("form").addEventListener("submit", function (e) {
    const checked = document.querySelectorAll('input[name="send[]"]:checked');
    if (checked.length === 0) {
      e.preventDefault();
      alert("Please select at least one document before submitting.");
    }
  });
</script>

</body>
</html>
