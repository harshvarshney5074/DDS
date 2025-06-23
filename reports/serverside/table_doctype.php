<?php
include('../dbcon.php');

$columns = ['Document_type', 'count', 'Pending', 'Approached', 'Received', 'Complete', 'Closed'];

$search = $_POST['search']['value'] ?? '';
$orderColumnIndex = $_POST['order'][0]['column'] ?? 1;
$orderDir = $_POST['order'][0]['dir'] ?? 'asc';
$orderColumnMap = [
  0 => '', // Serial number column, no need to sort
  1 => 'Document_type',
  2 => 'count',
  3 => 'Pending',
  4 => 'Approached',
  5 => 'Received',
  6 => 'Complete',
  7 => 'Closed'
];

$orderColumn = $orderColumnMap[$orderColumnIndex] ?? 'Document_type';


$start = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;

$date1 = $_POST['date1'] ?? '';
$date2 = $_POST['date2'] ?? '';

// Build WHERE clause
$whereParts = [];
if ($search) {
    $esc = mysqli_real_escape_string($conn, $search);
    $whereParts[] = "Document_type LIKE '%$esc%'";
}
$dateFiltered = false;
if (!empty($date1) && !empty($date2)) {
    $d1 = mysqli_real_escape_string($conn, $date1);
    $d2 = mysqli_real_escape_string($conn, $date2);
    $whereParts[] = "DATE(Req_date) BETWEEN '$d1' AND '$d2'";
    $dateFiltered = true;
}
$whereClause = $whereParts ? 'WHERE ' . implode(' AND ', $whereParts) : '';

$orderClause = $orderColumn ? "ORDER BY $orderColumn $orderDir" : "";

// Main query
$sql = "
  SELECT Document_type,
    COUNT(*) AS count,
    SUM(Status='Pending') AS Pending,
    SUM(Status='Approached') AS Approached,
    SUM(Status='Received') AS Received,
    SUM(Status='Complete') AS Complete,
    SUM(Status='Closed') AS Closed
  FROM entry
  $whereClause
  GROUP BY Document_type
  $orderClause
  " . ($length != -1 ? "LIMIT $start, $length" : "") . "
";
$res = mysqli_query($conn, $sql);

// Filtered count
$filteredQ = "
  SELECT COUNT(*) AS cnt
  FROM (
    SELECT Document_type
    FROM entry
    $whereClause
    GROUP BY Document_type
  ) t
";
$filteredCnt = mysqli_fetch_assoc(mysqli_query($conn, $filteredQ))['cnt'];

// Total count (no filters at all)
$totalQ = "
  SELECT COUNT(*) AS cnt
  FROM (
    SELECT Document_type FROM entry GROUP BY Document_type
  ) t
";
$totalCnt = mysqli_fetch_assoc(mysqli_query($conn, $totalQ))['cnt'];

$data = [];
$counter = $start + 1;
while ($r = mysqli_fetch_assoc($res)) {
    $data[] = [
      $counter++,
      htmlspecialchars($r['Document_type']),
      intval($r['count']),
      intval($r['Pending']),
      intval($r['Approached']),
      intval($r['Received']),
      intval($r['Complete']),
      intval($r['Closed'])
    ];
}

echo json_encode([
  'draw' => intval($_POST['draw']),
  'recordsTotal' => $totalCnt,
  'recordsFiltered' => $filteredCnt,
  'data' => $data
]);
?>
