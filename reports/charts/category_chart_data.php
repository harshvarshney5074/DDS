<?php
include('../dbcon.php');
// file_put_contents('cat_debug.json', json_encode($data, JSON_PRETTY_PRINT));

$date1 = $_POST['category_date1'] ?? '';
$date2 = $_POST['category_date2'] ?? '';

$where = '';
if (!empty($date1) && !empty($date2)) {
    $d1 = mysqli_real_escape_string($conn, $date1);
    $d2 = mysqli_real_escape_string($conn, $date2);
    $where = "WHERE DATE(entry_date) BETWEEN '$d1' AND '$d2'";
}

$query = "
    SELECT Category, COUNT(*) AS count
    FROM entry
    $where
    GROUP BY Category
    ORDER BY count DESC
";

$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [$row['Category'], (int)$row['count']];
}

echo json_encode($data);
