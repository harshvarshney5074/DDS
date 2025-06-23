<?php
include("dbcon.php");
session_start();

function trim_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_GET['edit_record'])) {
    $get_id = $_GET['edit_record'];
    $query = "SELECT * FROM journal_list WHERE S_no='$get_id'";
    $get_pro = mysqli_query($conn, $query);
    $row_pro = mysqli_fetch_array($get_pro);
    
    $id = $row_pro['S_no'];
    $jour_name = $row_pro['Journal_name'];
    $print_issn = $row_pro['Print_ISSN'];
    $e_issn = $row_pro['E_ISSN'];
    $pub_name = $row_pro['Pub_name'];
    $pub_country = $row_pro['Pub_country'];
}

if (isset($_POST['update'])) {
    $jour_name1 = trim_input($_POST['jour_name']);
    $print_issn = trim_input($_POST['print_issn']);
    $e_issn = trim_input($_POST['e_issn']);
    $pub_name = trim_input($_POST['pub_name']);
    $pub_country = trim_input($_POST['pub_country']);

    $update_record = mysqli_query($conn, "UPDATE journal_list SET Journal_name='$jour_name1', Print_ISSN='$print_issn', E_ISSN='$e_issn', Pub_name='$pub_name', Pub_country='$pub_country' WHERE S_no='$get_id'");
    $update_inst = mysqli_query($conn, "UPDATE entry SET Journal_name='$jour_name1' WHERE Journal_name='$jour_name'");

    if ($update_record) {
        echo "<script>alert('Data updated successfully')</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Journal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            padding-top: 70px;
            background-color: lightblue;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="../home.php">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="../index.php">Entries</a></li>
                <?php if ($_SESSION['type'] == '0' || $_SESSION['type'] == '1'): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Manage
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../institutions/index.php">Institutions</a></li>
                        <li><a class="dropdown-item" href="../journal/index.php">Document Sources</a></li>
                        <li><a class="dropdown-item" href="../patrons/index.php">Patrons</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="../orders.php">Requests</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="../reports/index.php">Reports</a></li>
                <?php if ($_SESSION['type'] == '0'): ?>
                <li class="nav-item"><a class="nav-link" href="../users/index.php">Users</a></li>
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
    <h2>Edit Journal Record</h2>
    <form method="post" name="inst_form">
        <div class="mb-3">
            <label class="form-label">Name of Source</label>
            <input type="text" name="jour_name" value="<?php echo $jour_name; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">ISSN/ISBN</label>
            <input type="text" name="print_issn" value="<?php echo $print_issn; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">E-ISSN</label>
            <input type="text" name="e_issn" value="<?php echo $e_issn; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Name of Publisher</label>
            <input type="text" name="pub_name" value="<?php echo $pub_name; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Publication Country</label>
            <input type="text" name="pub_country" value="<?php echo $pub_country; ?>" class="form-control">
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" name="update" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
