<?php
session_start();
include('dbcon.php');

$length = isset($_POST['length']) ? intval($_POST['length']) : 25;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

// Column index to database column name
$columns = [
    0 => null, // mail icon
    1 => 'e.Sr_no',
    2 => 'e.Req_date',
    3 => 'p.Display_name',
    4 => 'e.Category',
    5 => 'e.Bibliographic_details',
    6 => 'e.Journal_name',
    7 => 'e.Document_type',
    8 => 'e.Status',
    9 => null, // actions
    10 => null // download
];

$orderColumnIndex = $_POST['order'][0]['column'];
$orderDir = $_POST['order'][0]['dir'];
$orderColumn = $columns[$orderColumnIndex];

// Base query with join and status filter
$baseQuery = "FROM entry e LEFT JOIN patrons p ON e.Req_by = p.Sr_no";

// Search filtering
$searchQuery = " WHERE 1=1";
if (!empty($searchValue)) {
    $searchValue = $conn->real_escape_string($searchValue);
    $searchQuery .= " AND (
        e.Sr_no LIKE '%$searchValue%' OR 
        e.Req_date LIKE '%$searchValue%' OR 
        p.Display_name LIKE '%$searchValue%' OR 
        e.Category LIKE '%$searchValue%' OR 
        e.Bibliographic_details LIKE '%$searchValue%' OR 
        e.Journal_name LIKE '%$searchValue%' OR 
        e.Document_type LIKE '%$searchValue%' OR 
        e.Status LIKE '%$searchValue%'
    )";
}

// Date filter support (if needed later)
if (!empty($_POST['date1']) && !empty($_POST['date2'])) {
    $date1 = date('Y-m-d', strtotime($_POST['date1']));
    $date2 = date('Y-m-d', strtotime($_POST['date2']));
    $searchQuery .= " AND e.Req_date BETWEEN '$date1' AND '$date2'";
}

if (!empty($_POST['statuses']) && is_array($_POST['statuses'])) {
    $statuses = array_map([$conn, 'real_escape_string'], $_POST['statuses']);
    $statusList = "'" . implode("','", $statuses) . "'";
    $searchQuery .= " AND e.Status IN ($statusList)";
}

if (!empty($_POST['docTypes']) && is_array($_POST['docTypes'])) {
    $escaped = array_map([$conn, 'real_escape_string'], $_POST['docTypes']);
    $in = "'" . implode("','", $escaped) . "'";
    $searchQuery .= " AND e.Document_type IN ($in)";
}

// Filter: Patron Categories
if (!empty($_POST['categories']) && is_array($_POST['categories'])) {
    $escaped = array_map([$conn, 'real_escape_string'], $_POST['categories']);
    $in = "'" . implode("','", $escaped) . "'";
    $searchQuery .= " AND e.Category IN ($in)";
}

// Total records without filtering
$totalQuery = "SELECT COUNT(*) as total $baseQuery";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalData = $totalRow['total'];

// Total records with filtering
$filteredQuery = "SELECT COUNT(*) as total $baseQuery $searchQuery";
$filteredResult = mysqli_query($conn, $filteredQuery);
$filteredRow = mysqli_fetch_assoc($filteredResult);
$filteredCount = $filteredRow['total'];

// Final data query
$orderClause = $orderColumn ? " ORDER BY $orderColumn $orderDir" : " ORDER BY e.Sr_no DESC";
$limitClause = " LIMIT $start, $length";
$dataQuery = "SELECT e.*, p.Display_name $baseQuery $searchQuery $orderClause $limitClause";
$dataResult = mysqli_query($conn, $dataQuery);

$data = [];
while ($row = mysqli_fetch_assoc($dataResult)) {
    $subArray = [];
    $subArray[] = '<input type="checkbox" class="entry-checkbox" name="send[]" value="' . $row['Sr_no'] . '">';
    $subArray[] = $row['Sr_no'];
    $subArray[] = $row['Req_date'];
    $subArray[] = $row['Display_name'] ?? '';
    $subArray[] = $row['Category'];
    $subArray[] = $row['Bibliographic_details'];
    $subArray[] = $row['Journal_name'];
    $subArray[] = $row['Document_type'];
    $subArray[] = $row['Status'];

    $actions = '
        <a href="edit_final.php?edit_record=' . $row['Sr_no'] . '" class="btn btn-info btn-sm mb-1">Edit</a><br />
        <button type="button" name="view" value="View" id="' . $row['Sr_no'] . '" class="btn btn-success btn-sm mb-1 view_data">View</button><br />
        <button type="button" name="delete" value="Delete" id="' . $row['Sr_no'] . '" class="btn btn-danger btn-sm delete_data">Delete</button>';
    $subArray[] = $actions;

    if (!empty($row['File_path']) && ($row['Status'] == 'Complete' || $row['Status'] == 'Received')) {
        $subArray[] = '<a href="' . htmlspecialchars($row['File_path']) . '" download title="Download file" class="btn btn-sm btn-outline-success"><i class="fa fa-download"></i></a>';
    } else {
        $subArray[] = '';
    }

    $data[] = $subArray;
}

// Output JSON
echo json_encode([
    "draw" => intval($_POST["draw"]),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $filteredCount,
    "data" => $data
]);
?>
