<?php
include("../dbcon.php");

$columns = ['Sr_no', 'Roll_no', 'Display_name', 'Email_id', 'Discipline', 'Program_name', 'Status'];
$search = $_GET['search']['value'];
$orderColIndex = $_GET['order'][0]['column'];
$orderCol = $columns[$orderColIndex];
$orderDir = $_GET['order'][0]['dir'];
$start = $_GET['start'];
$length = $_GET['length'];

$where = '';
if (!empty($search)) {
  $where = "WHERE Roll_no LIKE '%$search%' 
            OR Display_name LIKE '%$search%' 
            OR Email_id LIKE '%$search%' 
            OR Discipline LIKE '%$search%' 
            OR Program_name LIKE '%$search%' 
            OR Status LIKE '%$search%'";
}

// Total records without filtering
$totalQuery = mysqli_query($conn, "SELECT COUNT(*) FROM patrons");
$totalData = mysqli_fetch_row($totalQuery)[0];

// Total records with filtering
$filteredQuery = mysqli_query($conn, "SELECT COUNT(*) FROM patrons $where");
$totalFiltered = mysqli_fetch_row($filteredQuery)[0];

// Fetch data
$dataQuery = mysqli_query($conn,
  "SELECT * FROM patrons 
   $where 
   ORDER BY $orderCol $orderDir 
   LIMIT $start, $length");

$data = [];
while ($row = mysqli_fetch_assoc($dataQuery)) {
  $data[] = $row;
}

$response = [
  "draw" => intval($_GET['draw']),
  "recordsTotal" => $totalData,
  "recordsFiltered" => $totalFiltered,
  "data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);
?>
