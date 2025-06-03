<?php
include("dbcon.php");
session_start();

function trim_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_GET['edit_record'])) {
    $get_id = $_GET['edit_record'];
    $query = "SELECT * FROM user WHERE user_id='$get_id'";
    $get_pro = mysqli_query($conn, $query);
    $row_pro = mysqli_fetch_array($get_pro);

    $id = $row_pro['user_id'];
    $uname = $row_pro['username'];
    $user_type = $row_pro['user_type'];
    $pass = $row_pro['password'];
}

if (isset($_POST['update'])) {
    $utype = trim_input($_POST['utype']);
    $pass = $_POST['pass'];
    $con_pass = $_POST['con_pass'];

    if ($pass == "") {
        $update_record = mysqli_query($conn, "UPDATE user SET user_type='$utype' WHERE user_id='$get_id'");
        if ($update_record) {
            echo "<script>alert('Data updated successfully')</script>";
            echo "<script>window.location.href = 'index.php';</script>";
        }
    } elseif ($pass == $con_pass) {
        $pass1 = md5($pass);
        $update_record = mysqli_query($conn, "UPDATE user SET user_type='$utype', password='$pass1' WHERE user_id='$get_id'");
        if ($update_record) {
            echo "<script>alert('Data updated successfully')</script>";
            echo "<script>window.location.href = 'index.php';</script>";
        }
    } else {
        echo "<script>alert('Password and Confirm password are not the same')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            padding-top: 70px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">IITGN</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if ($_SESSION['type'] == '0' || $_SESSION['type'] == '1'): ?>
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
                <?php if ($_SESSION['type'] == '0'): ?>
                <li class="nav-item"><a class="nav-link" href="../users/index.php">Settings</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['uid'])): ?>
                <li class="nav-item"><a class="nav-link" href="#">User <?php echo $_SESSION['uid']; ?></a></li>
                <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h2>Edit User Details</h2>
    <form method="post" name="inst_form">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" value="<?php echo $uname; ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">User Type</label>
            <select name="utype" class="form-select">
                <option value="<?php echo $user_type; ?>">
                    <?php echo ($user_type == 0 ? "Admin" : ($user_type == 1 ? "User" : "Normal User")); ?>
                </option>
                <option value="0">Admin</option>
                <option value="1">User</option>
                <option value="2">Normal User</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Enter New Password</label>
            <input type="password" name="pass" class="form-control" placeholder="New Password">
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="con_pass" class="form-control" placeholder="Confirm Password">
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" name="update" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
