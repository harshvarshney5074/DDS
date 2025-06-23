<?php
include("../dbcon.php");

$draw = intval($_POST['draw'] ?? 0);
$del_date1 = $_POST['del_date1'] ?? '';
$del_date2 = $_POST['del_date2'] ?? '';

$where = "WHERE e.Sent_date IS NOT NULL AND e.entry_date IS NOT NULL";
if (!empty($del_date1) && !empty($del_date2)) {
    $where .= " AND e.Req_date BETWEEN '$del_date1' AND '$del_date2'";
}

$query = "
    SELECT 
        CASE 
            WHEN DATEDIFF(e.Sent_date, e.entry_date) < 1 THEN '<1'
            WHEN DATEDIFF(e.Sent_date, e.entry_date) BETWEEN 1 AND 3 THEN '1-3'
            WHEN DATEDIFF(e.Sent_date, e.entry_date) BETWEEN 4 AND 5 THEN '3-5'
            WHEN DATEDIFF(e.Sent_date, e.entry_date) BETWEEN 6 AND 7 THEN '5-7'
            WHEN DATEDIFF(e.Sent_date, e.entry_date) BETWEEN 8 AND 15 THEN '7-15'
            WHEN DATEDIFF(e.Sent_date, e.entry_date) BETWEEN 16 AND 30 THEN '15-30'
            ELSE '>30'
        END AS range_label,
        COUNT(*) AS total
    FROM entry e
    $where
    GROUP BY range_label
    ORDER BY 
        MIN(CASE 
            WHEN DATEDIFF(e.Sent_date, e.entry_date) < 1 THEN 0
            WHEN DATEDIFF(e.Sent_date, e.entry_date) BETWEEN 1 AND 3 THEN 1
            WHEN DATEDIFF(e.Sent_date, e.entry_date) BETWEEN 4 AND 5 THEN 2
            WHEN DATEDIFF(e.Sent_date, e.entry_date) BETWEEN 6 AND 7 THEN 3
            WHEN DATEDIFF(e.Sent_date, e.entry_date) BETWEEN 8 AND 15 THEN 4
            WHEN DATEDIFF(e.Sent_date, e.entry_date) BETWEEN 16 AND 30 THEN 5
            ELSE 6
        END)
";

$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'range' => $row['range_label'],
        'count' => $row['total']
    ];
}

// We don't paginate, but still return total counts for DataTables
echo json_encode([
    'draw' => $draw,
    'recordsTotal' => count($data),
    'recordsFiltered' => count($data),
    'data' => $data
]);
?>
