<?php
include('../dbcon.php');

$date1 = $_POST['status_date1'] ?? '';
$date2 = $_POST['status_date2'] ?? '';

$where = '';
if (!empty($date1) && !empty($date2)) {
    $d1 = mysqli_real_escape_string($conn, $date1);
    $d2 = mysqli_real_escape_string($conn, $date2);
    $where = "WHERE DATE(entry_date) BETWEEN '$d1' AND '$d2'";
}

$query = "
    SELECT Status, COUNT(*) AS count
    FROM entry
    $where
    GROUP BY Status
";

$result = mysqli_query($conn, $query);

$status_map = ['Pending' => 0, 'Approached' => 0, 'Received' => 0, 'Complete' => 0, 'Closed' => 0];
while ($row = mysqli_fetch_assoc($result)) {
    $status = $row['Status'];
    $count = (int)$row['count'];
    if (isset($status_map[$status])) {
        $status_map[$status] = $count;
    }
}

echo json_encode($status_map);
