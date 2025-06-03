<?php
include('../dbcon.php'); // Update path if needed

$columns = ['Journal_name', 'Print_ISSN', 'E_ISSN', 'Pub_name', 'Main_pub', 'Pub_country'];

$draw = intval($_POST['draw']);
$row = intval($_POST['start']);
$rowperpage = intval($_POST['length']);
$columnIndex = intval($_POST['order'][0]['column']);
$columnName = $columns[$columnIndex];
$columnSortOrder = $_POST['order'][0]['dir'];
$searchValue = mysqli_real_escape_string($conn, $_POST['search']['value']);

// Total records without filtering
$totalRecordsQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM journal_list");
$totalRecords = mysqli_fetch_assoc($totalRecordsQuery)['total'];

// Total records with filtering
$searchQuery = "";
if ($searchValue != '') {
    $searchQuery = " AND (
        Journal_name LIKE '%$searchValue%' OR 
        Print_ISSN LIKE '%$searchValue%' OR 
        E_ISSN LIKE '%$searchValue%' OR 
        Pub_name LIKE '%$searchValue%' OR 
        Main_pub LIKE '%$searchValue%' OR 
        Pub_country LIKE '%$searchValue%'
    )";
}

$filteredRecordsQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM journal_list WHERE 1 $searchQuery");
$totalRecordwithFilter = mysqli_fetch_assoc($filteredRecordsQuery)['total'];

// Fetch records
$journalQuery = "
    SELECT * FROM journal_list 
    WHERE 1 $searchQuery 
    ORDER BY $columnName $columnSortOrder 
    LIMIT $row, $rowperpage
";
$journals = mysqli_query($conn, $journalQuery);

$data = [];

while ($row = mysqli_fetch_assoc($journals)) {
    $data[] = [
        "Journal_name" => $row['Journal_name'],
        "Print_ISSN" => $row['Print_ISSN'],
        "E_ISSN" => $row['E_ISSN'],
        "Pub_name" => $row['Pub_name'],
        "Main_pub" => $row['Main_pub'],
        "Pub_country" => $row['Pub_country'],
        "Actions" => '<a href="edit11.php?edit_record=' . $row["S_no"] . '" class="btn btn-info btn-xs">Edit</a>
                    <a href="delete_record.php?delete_record=' . $row["S_no"] . '" class="btn btn-danger btn-xs" onclick="return confirm(\'Are you sure you want to delete this item?\');">Delete</a>'
    ];

}

// Prepare response
$response = [
    "draw" => $draw,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecordwithFilter,
    "data" => $data
];

echo json_encode($response);
?>
