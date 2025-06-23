<?php 
include('dbcon.php');
header('Content-Type: application/json');

$date1 = $_POST['discipline_date1'] ?? '';
$date2 = $_POST['discipline_date2'] ?? '';

$where = '';
if (!empty($date1) && !empty($date2)) {
    $d1 = mysqli_real_escape_string($conn, $date1);
    $d2 = mysqli_real_escape_string($conn, $date2);
    $where = "WHERE DATE(entry.Req_date) BETWEEN '$d1' AND '$d2'";
}

$query = "
    SELECT patrons.Discipline, COUNT(*) AS count
    FROM entry
    JOIN patrons ON entry.Req_by = patrons.Sr_no
    $where
    GROUP BY patrons.Discipline
    ORDER BY count DESC
	LIMIT 10
";

$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
