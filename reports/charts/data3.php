<?php
include('dbcon.php');
header('Content-Type: application/json');

$date1 = $_POST['doc_date1'] ?? '';
$date2 = $_POST['doc_date2'] ?? '';

$where = '';
if (!empty($date1) && !empty($date2)) {
    $d1 = mysqli_real_escape_string($conn, $date1);
    $d2 = mysqli_real_escape_string($conn, $date2);
    $where = "WHERE DATE(Req_date) BETWEEN '$d1' AND '$d2'";
}

$query = "
    SELECT Document_type, COUNT(*) AS count
    FROM entry
    $where
    GROUP BY Document_type
    ORDER BY count DESC
";

$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
