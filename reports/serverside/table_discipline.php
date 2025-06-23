<?php
include("../../dbcon.php");

// Columns for ordering
$columns = ['','Discipline', 'total_requests'];
$start = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$lengthClause = $length === -1 ? "" : "LIMIT $start, $length";

// Ordering
$orderClause = "";
if (isset($_POST['order'][0]['column'])) {
    $idx = intval($_POST['order'][0]['column']);
    $dir = $_POST['order'][0]['dir'] === 'desc' ? 'DESC' : 'ASC';
    if (isset($columns[$idx])) {
        $col = $columns[$idx];
        if ($col !== '') {
            $orderClause = "ORDER BY $col $dir";
        }
    }
}

// Date filter: uses input dates or defaults to ALL if none specified
$date1 = $_POST['date1'] ?? '';
$date2 = $_POST['date2'] ?? '';
$dateFilter = "";
if ($date1 && $date2) {
    $d1 = mysqli_real_escape_string($conn, $date1);
    $d2 = mysqli_real_escape_string($conn, $date2);
    $dateFilter = "AND e.Req_date BETWEEN '$d1' AND '$d2'";
}

// Search filter (discipline name)
$searchValue = $_POST['search']['value'] ?? '';
$searchFilter = "";
if ($searchValue !== '') {
    $sv = mysqli_real_escape_string($conn, $searchValue);
    $searchFilter = "AND p.Discipline LIKE '%$sv%'";
}

// Total distinct disciplines (for pagination)
$totalRes = mysqli_query($conn, "SELECT COUNT(DISTINCT Discipline) AS cnt FROM patrons WHERE Discipline IS NOT NULL AND Discipline <> ''");
$totalRecords = intval(mysqli_fetch_assoc($totalRes)['cnt']);

// Total filtered count
$filteredRes = mysqli_query($conn, "
    SELECT COUNT(*) AS cnt FROM (
        SELECT p.Discipline
        FROM entry e
        JOIN patrons p ON e.Req_by = p.Sr_no
        WHERE p.Discipline IS NOT NULL AND p.Discipline <> ''
        $dateFilter $searchFilter
        GROUP BY p.Discipline
    ) t
");
$recordsFiltered = intval(mysqli_fetch_assoc($filteredRes)['cnt']);

// Main query: count by discipline with status breakdown
$query = "
    SELECT
        p.Discipline,
        COUNT(*) AS total_requests,
        SUM(e.Status = 'Pending') AS Pending,
        SUM(e.Status = 'Approached') AS Approached,
        SUM(e.Status = 'Received') AS Received,
        SUM(e.Status = 'Complete') AS Complete,
        SUM(e.Status = 'Closed') AS Closed
    FROM entry e
    JOIN patrons p ON e.Req_by = p.Sr_no
    WHERE p.Discipline IS NOT NULL AND p.Discipline <> ''
    $dateFilter $searchFilter
    GROUP BY p.Discipline
    $orderClause
    $lengthClause
";
$res = mysqli_query($conn, $query) or die(mysqli_error($conn));

$data = [];
$seq = $start + 1;
while ($row = mysqli_fetch_assoc($res)) {
    $data[] = [
        $seq++,
        htmlspecialchars($row['Discipline']),
        intval($row['total_requests']),
        intval($row['Pending']),
        intval($row['Approached']),
        intval($row['Received']),
        intval($row['Complete']),
        intval($row['Closed']),
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
]);
