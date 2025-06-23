<?php 
include('dbcon.php');
header('Content-Type: application/json');

$date1 = $_GET['date1'] ?? '';
$date2 = $_GET['date2'] ?? '';

$data = [];

if (!empty($date1) && !empty($date2)) {
    $d1 = mysqli_real_escape_string($conn, $date1);
    $d2 = mysqli_real_escape_string($conn, $date2);
    $dateCondition = "req_date BETWEEN '$d1' AND '$d2'";
} else {
    $dateCondition = "1"; // No filter
}

$institutes = mysqli_query($conn, "SELECT * FROM institutions");

$all = [];

while ($row = mysqli_fetch_assoc($institutes)) {
    $id = $row['Sr_no'];
    $name = $row['institute_name'];

    $sendQuery = "SELECT COUNT(*) AS cnt FROM institute_list WHERE institute_name = '$id' AND $dateCondition";
    $recvQuery = "SELECT COUNT(*) AS cnt FROM receive_list WHERE institute_name = '$id' AND $dateCondition";

    $sendCount = mysqli_fetch_assoc(mysqli_query($conn, $sendQuery))['cnt'] ?? 0;
    $recvCount = mysqli_fetch_assoc(mysqli_query($conn, $recvQuery))['cnt'] ?? 0;

    $all[] = [
        'institute_name' => $name,
        'send_count' => (int)$sendCount,
        'receive_count' => (int)$recvCount
    ];
}

// Sort by receive_count descending
usort($all, function($a, $b) {
    return $b['receive_count'] <=> $a['receive_count'];
});

// Take top 10
$data = array_slice($all, 0, 10);

echo json_encode($data);
?>
