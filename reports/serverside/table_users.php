<?php
include('../dbcon.php');

$columns = [
  0 => '',         // Sno (not sortable)
  1 => 'patron_name',
  2 => 'count',
  3 => 'Pending',
  4 => 'Approached',
  5 => 'Received',
  6 => 'Complete',
  7 => 'Closed'
];

// DataTables parameters
$search       = $_POST['search']['value'] ?? '';
$orderIdx     = intval($_POST['order'][0]['column'] ?? 1);
$orderDir     = ($_POST['order'][0]['dir'] ?? 'asc') === 'asc' ? 'ASC' : 'DESC';
$start        = intval($_POST['start'] ?? 0);
$length       = intval($_POST['length'] ?? 10);

$date1 = $_POST['user_date1'] ?? '';
$date2 = $_POST['user_date2'] ?? '';
$useDate = ($date1 && $date2);

$dateFilter = $useDate
  ? "WHERE DATE(Req_date) BETWEEN '".mysqli_real_escape_string($conn,$date1)
    ."' AND '".mysqli_real_escape_string($conn,$date2)."'"
  : "";

$whereSearch = '';
if ($search) {
  $esc = mysqli_real_escape_string($conn, $search);
  $whereSearch = ($dateFilter ? ' AND ' : 'WHERE ') . "(p.Display_name LIKE '%$esc%')";
}
$whereClause = $dateFilter . $whereSearch;

// Build ORDER BY
$orderCol = $columns[$orderIdx] ?? 'patron_name';
$orderSQL = $orderCol ? "ORDER BY $orderCol $orderDir" : "";

// Main query
$sql = "
  SELECT
    p.Display_name AS patron_name,
    COUNT(*) AS count,
    SUM(e.Status='Pending') AS Pending,
    SUM(e.Status='Approached') AS Approached,
    SUM(e.Status='Received') AS Received,
    SUM(e.Status='Complete') AS Complete,
    SUM(e.Status='Closed') AS Closed
  FROM entry e
  JOIN patrons p ON e.Req_by = p.Sr_no
  $whereClause
  GROUP BY p.Display_name
  $orderSQL
  " . ($length != -1 ? "LIMIT $start, $length" : "") . "
";
$res = mysqli_query($conn, $sql);

// Count totals
$totalQ = "
  SELECT COUNT(DISTINCT p.Display_name) AS total
  FROM entry e
  JOIN patrons p ON e.Req_by = p.Sr_no
  $dateFilter";
$totalRes = mysqli_fetch_assoc(mysqli_query($conn, $totalQ));
$totalCount = intval($totalRes['total']);

$filteredQ = "
  SELECT COUNT(*) AS total FROM (
    SELECT p.Display_name
    FROM entry e
    JOIN patrons p ON e.Req_by = p.Sr_no
    $whereClause
    GROUP BY p.Display_name
  ) t
";
$filteredRes = mysqli_fetch_assoc(mysqli_query($conn, $filteredQ));
$filteredCount = intval($filteredRes['total']);

// Prepare response data
$data = [];
$idx = $start + 1;
while ($row = mysqli_fetch_assoc($res)) {
  $data[] = [
    $idx++,
    htmlspecialchars($row['patron_name']),
    intval($row['count']),
    intval($row['Pending']),
    intval($row['Approached']),
    intval($row['Received']),
    intval($row['Complete']),
    intval($row['Closed']),
  ];
}

// Output JSON
echo json_encode([
  'draw'            => intval($_POST['draw']),
  'recordsTotal'    => $totalCount,
  'recordsFiltered' => $filteredCount,
  'data'            => $data
]);
