<?php
include("dbcon.php");
session_start();

if (isset($_GET['edit_record'])) {
    $get_id = $_GET['edit_record'];
    $query = "SELECT * FROM institutions WHERE Sr_no='$get_id'";
    $get_pro = mysqli_query($conn, $query);

    while ($row_pro = mysqli_fetch_array($get_pro)) {
        $id = $row_pro['Sr_no'];
        $inst = $row_pro['institute_name'];
        $email = $row_pro['email'];
        $add = $row_pro['address'];
        $status = $row_pro['Status'];
        $remarks = $row_pro['Remarks'];
    }
}

function trim_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Institution - DDS</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <style>
        body {
            padding-top: 70px;
        }
    </style>

    <script>
        function validateForm() {
            const email = document.forms["inst_form"]["email"].value;
            const atpos = email.indexOf("@");
            const dotpos = email.lastIndexOf(".");
            if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= email.length) {
                alert("Not a valid e-mail address");
                return false;
            }
        }
    </script>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">IITGN</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($_SESSION['type'] === '0' || $_SESSION['type'] === '1'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Manage</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../institutions/index.php">Institutions</a></li>
                            <li><a class="dropdown-item" href="../journal/index.php">Journals</a></li>
                            <li><a class="dropdown-item" href="../patrons/index.php">Patrons</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="../biblo_search1.php">Search</a></li>
                    <li class="nav-item"><a class="nav-link" href="../orders.php">Orders</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="../reports/index.php">Reports</a></li>
                <?php if ($_SESSION['type'] === '0'): ?>
                    <li class="nav-item"><a class="nav-link" href="../users/index.php">Settings</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['uid'])): ?>
                    <li class="nav-item"><a class="nav-link" href="#">User <?= $_SESSION['uid'] ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Form Section -->
<div class="container">
    <h2 class="mb-4">Edit Institution Record</h2>
    <form method="post" name="inst_form" action="" onsubmit="return validateForm();">
        <div class="mb-3">
            <label for="institute_name" class="form-label">Institute Name</label>
            <input type="text" class="form-control" id="institute_name" name="institute_name" value="<?= $inst ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="<?= $add ?>" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option selected><?= $status ?></option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <input type="text" class="form-control" id="remarks" name="remarks" value="<?= $remarks ?>">
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary" name="update">Update</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>

<?php
if (isset($_POST['update'])) {
    $inst1 = trim_input($_POST['institute_name']);
    $email = trim_input($_POST['email']);
    $add = trim_input($_POST['address']);
    $status = $_POST['status'];
    $remarks = trim_input($_POST['remarks']);

    $update_record = mysqli_query($conn, "UPDATE institutions SET institute_name='$inst1', email='$email', address='$add', Status='$status', Remarks='$remarks' WHERE Sr_no='$get_id'");

    if ($update_record) {
        echo "<script>alert('Data updated successfully'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Update failed.');</script>";
    }
}
?>
