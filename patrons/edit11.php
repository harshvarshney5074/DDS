<?php
include("dbcon.php");
session_start();

if (isset($_GET['edit_record'])) {
  $get_id = $_GET['edit_record'];
  $query = "SELECT * FROM patrons WHERE Sr_no='$get_id'";
  $get_pro = mysqli_query($conn, $query);
  $row_pro = mysqli_fetch_assoc($get_pro);

  $id = $row_pro['Sr_no'];
  $roll_no = $row_pro['Roll_no'];
  $display_name = $row_pro['Display_name'];
  $email_id = $row_pro['Email_id'];
  $discipline = $row_pro['Discipline'];
  $program_name = $row_pro['Program_name'];
  $status = $row_pro['Status'];
}

function trim_input($data) {
  return htmlspecialchars(stripslashes(trim($data)));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Patron | DDS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="../index.php">Entries</a></li>
        <?php if ($_SESSION['type'] === '0' || $_SESSION['type'] === '1'): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">Manage</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="../institutions/index.php">Institutions</a></li>
            <li><a class="dropdown-item" href="../journal/index.php">Document Sources</a></li>
            <li><a class="dropdown-item" href="../patrons/index.php">Patrons</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="../orders.php">Requests</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="../reports/index.php">Reports</a></li>
        <?php if ($_SESSION['type'] === '0'): ?>
        <li class="nav-item"><a class="nav-link" href="../users/index.php">Users</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['uid'])): ?>
        <li class="nav-item"><a class="nav-link">User <?= $_SESSION['uid'] ?></a></li>
        <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5 pt-4" style="max-width: 700px;">
  <h2 class="mt-4 mb-3 text-center">Edit Patron</h2>

  <form method="post" name="inst_form" onsubmit="return validateForm();">
    <div class="mb-3">
      <label for="roll_no" class="form-label">Roll No/ID</label>
      <input type="text" class="form-control" id="roll_no" name="roll_no" value="<?= $roll_no ?>" required>
    </div>
    <div class="mb-3">
      <label for="display_name" class="form-label">Display Name</label>
      <input type="text" class="form-control" id="display_name" name="display_name" value="<?= $display_name ?>" required>
    </div>
    <div class="mb-3">
      <label for="email_id" class="form-label">Email ID</label>
      <input type="email" class="form-control" id="email_id" name="email_id" value="<?= $email_id ?>" required>
    </div>
    <div class="mb-3">
      <label for="discipline" class="form-label">Department</label>
      <input type="text" class="form-control" id="discipline" name="discipline" value="<?= $discipline ?>" required>
    </div>
    <div class="mb-3">
      <label for="program_name" class="form-label">Program/Designation</label>
      <input type="text" class="form-control" id="program_name" name="program_name" value="<?= $program_name ?>" required>
    </div>
    <div class="mb-3">
      <label for="status" class="form-label">Status</label>
      <select class="form-select" name="status" id="status">
        <option selected value="<?= $status ?>"><?= $status ?></option>
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
      </select>
    </div>
    <button type="submit" name="update" class="btn btn-primary">Update</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<script>
function validateForm() {
  const email = document.forms["inst_form"]["email_id"].value;
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!re.test(email)) {
    alert("Please enter a valid email address.");
    return false;
  }
  return true;
}
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
  $roll_no = trim_input($_POST['roll_no']);
  $display_name = trim_input($_POST['display_name']);
  $email_id = trim_input($_POST['email_id']);
  $discipline = trim_input($_POST['discipline']);
  $program_name = trim_input($_POST['program_name']);
  $status = $_POST['status'];

  $update_record = mysqli_query($conn, "UPDATE patrons 
    SET Roll_no='$roll_no', Display_name='$display_name', Email_id='$email_id', 
        Discipline='$discipline', Program_name='$program_name', Status='$status' 
    WHERE Sr_no='$get_id'");

  if ($update_record) {
    echo "<script>alert('Data updated successfully'); window.location.href='index.php';</script>";
  } else {
    echo "<script>alert('Update failed.');</script>";
  }
}
?>
</body>
</html>
