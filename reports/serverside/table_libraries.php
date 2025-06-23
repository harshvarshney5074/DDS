<?php
include('../dbcon.php');

$columns = ['institute_name', 'send_count', 'receive_count'];

$search = $_POST['search']['value'] ?? '';
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderDir = $_POST['order'][0]['dir'] ?? 'asc';
$orderColumn = $columns[$orderColumnIndex] ?? 'institute_name';

$start = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;

// Get date filters
$date1 = $_POST['lib_date1'] ?? '';
$date2 = $_POST['lib_date2'] ?? '';

$data = [];
$totalRecords = 0;
$totalFiltered = 0;
$grandTotalSent = 0;
$grandTotalReceived = 0;

// Build date condition only if both dates are provided
if (!empty($date1) && !empty($date2)) {
    $d1 = mysqli_real_escape_string($conn, $date1);
    $d2 = mysqli_real_escape_string($conn, $date2);
    $dateCondition = "req_date BETWEEN '$d1' AND '$d2'";
} else {
    $dateCondition = "1"; // Always true condition — gets all records
}

// Fetch all institutes
$institutes = mysqli_query($conn, "SELECT * FROM institutions");
while ($row = mysqli_fetch_assoc($institutes)) {
    $id = $row['Sr_no'];
    $name = $row['institute_name'];

    // Count from both lists with filters
    $sendQuery = "SELECT COUNT(*) AS cnt FROM institute_list WHERE institute_name = '$id' AND $dateCondition";
    $recvQuery = "SELECT COUNT(*) AS cnt FROM receive_list WHERE institute_name = '$id' AND $dateCondition";

    $sendCount = mysqli_fetch_assoc(mysqli_query($conn, $sendQuery))['cnt'] ?? 0;
    $recvCount = mysqli_fetch_assoc(mysqli_query($conn, $recvQuery))['cnt'] ?? 0;
    $grandTotalSent += $sendCount;
    $grandTotalReceived += $recvCount;


    // Search filter
    if ($search && stripos($name, $search) === false) {
        continue;
    }

    $data[] = [
        htmlspecialchars($name),
        $sendCount,
        $recvCount
    ];
}

$totalRecords = count($data);
$totalFiltered = $totalRecords;

// Sort
usort($data, function ($a, $b) use ($orderColumnIndex, $orderDir) {
    return ($orderDir === 'asc')
        ? strnatcasecmp($a[$orderColumnIndex], $b[$orderColumnIndex])
        : strnatcasecmp($b[$orderColumnIndex], $a[$orderColumnIndex]);
});

// Pagination
$data = array_slice($data, $start, $length);

echo json_encode([
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $totalRecords,
    'recordsFiltered' => $totalFiltered,
    'data' => $data,
    'total_sent' => $grandTotalSent,
    'total_received' => $grandTotalReceived
]);
