<?php
include('../dbcon.php');

// Define columns as seen in DataTables (frontend)
$columns = ['Category', 'Total', 'Pending', 'Approached', 'Received', 'Complete', 'Closed'];

// Get ordering parameters
$search = $_POST['search']['value'] ?? '';
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderDir = $_POST['order'][0]['dir'] ?? 'asc';
$orderColumn = $columns[$orderColumnIndex] ?? 'Category';

// Map DataTable column to SQL expression
$orderColumnSqlMap = [
    'Category' => 'Category',
    'Total' => 'COUNT(*)',
    'Pending' => 'SUM(CASE WHEN Status = "Pending" THEN 1 ELSE 0 END)',
    'Approached' => 'SUM(CASE WHEN Status = "Approached" THEN 1 ELSE 0 END)',
    'Received' => 'SUM(CASE WHEN Status = "Received" THEN 1 ELSE 0 END)',
    'Complete' => 'SUM(CASE WHEN Status = "Complete" THEN 1 ELSE 0 END)',
    'Closed' => 'SUM(CASE WHEN Status = "Closed" THEN 1 ELSE 0 END)'
];
$orderBy = $orderColumnSqlMap[$orderColumn] ?? 'Category';

$start = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;

// Optional date filters
$date1 = $_POST['date1'] ?? '';
$date2 = $_POST['date2'] ?? '';
$dateFilter = '';

if (!empty($date1) && !empty($date2)) {
    $d1 = mysqli_real_escape_string($conn, $date1);
    $d2 = mysqli_real_escape_string($conn, $date2);
    $dateFilter = " AND (req_date BETWEEN '$d1' AND '$d2')";
}

// Build WHERE clause
$whereParts = [];

if (!empty($search)) {
    $escapedSearch = mysqli_real_escape_string($conn, $search);
    $whereParts[] = "Category LIKE '%$escapedSearch%'";
}

$where = '';
if (!empty($whereParts)) {
    $where = 'WHERE ' . implode(' AND ', $whereParts);
    $where .= $dateFilter;
} elseif (!empty($dateFilter)) {
    $where = 'WHERE 1 ' . $dateFilter;
}

// Main query
$query = "
    SELECT 
        Category,
        SUM(CASE WHEN Status = 'Pending' THEN 1 ELSE 0 END) AS Pending,
        SUM(CASE WHEN Status = 'Approached' THEN 1 ELSE 0 END) AS Approached,
        SUM(CASE WHEN Status = 'Received' THEN 1 ELSE 0 END) AS Received,
        SUM(CASE WHEN Status = 'Complete' THEN 1 ELSE 0 END) AS Complete,
        SUM(CASE WHEN Status = 'Closed' THEN 1 ELSE 0 END) AS Closed,
        COUNT(*) AS Total
    FROM entry
    $where
    GROUP BY Category
    ORDER BY $orderBy $orderDir
    " . ($length != -1 ? "LIMIT $start, $length" : "") . "
";
$dataResult = mysqli_query($conn, $query);

// Total records
$totalRecordsQuery = "SELECT COUNT(DISTINCT Category) AS total FROM entry";
$totalRecords = mysqli_fetch_assoc(mysqli_query($conn, $totalRecordsQuery))['total'];

// Filtered records
$filteredRecordsQuery = "SELECT COUNT(DISTINCT Category) AS filtered FROM entry $where";
$totalFiltered = mysqli_fetch_assoc(mysqli_query($conn, $filteredRecordsQuery))['filtered'];

$data = [];
while ($row = mysqli_fetch_assoc($dataResult)) {
    $data[] = [
        htmlspecialchars($row['Category']),
        $row['Total'],
        $row['Pending'],
        $row['Approached'],
        $row['Received'],
        $row['Complete'],
        $row['Closed']
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);
