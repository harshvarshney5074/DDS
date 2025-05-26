<?php
session_start();
include('checkUser.php');
include('dbcon.php');

if (isset($_POST['date_submit'])) {
    $ab = $_POST['date1'];
    $cd = $_POST['date2'];
    $query = "SELECT * FROM entry WHERE Req_date BETWEEN '$ab' AND '$cd'";
} elseif (isset($_GET['get_date1']) && isset($_GET['get_date2'])) {
    $ab = $_GET['get_date1'];
    $cd = $_GET['get_date2'];
    $query = "SELECT * FROM entry WHERE Req_date BETWEEN '$ab' AND '$cd'";
} else {
    $query = "SELECT * FROM entry ORDER BY Req_date DESC";
}
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>DDS</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <h2>Document Delivery Requests</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label>From:</label>
            <input type="date" name="date1" required>
            <label>To:</label>
            <input type="date" name="date2" required>
            <input type="submit" name="date_submit" value="Search" class="btn btn-primary">
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Req ID</th>
                <th>Req Date</th>
                <th>Name</th>
                <th>Title</th>
                <th>Status</th>
                <!-- Add more columns as needed -->
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['Req_id'] ?></td>
                <td><?= $row['Req_date'] ?></td>
                <td><?= $row['Name'] ?></td>
                <td><?= $row['Title'] ?></td>
                <td><?= $row['Status'] ?></td>
                <!-- Add more fields as needed -->
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
