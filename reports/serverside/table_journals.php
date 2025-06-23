<?php
include('../dbcon.php');

// For column sorting
$columns = ['Journal_name', 'count', 'Pending', 'Approached', 'Received', 'Complete', 'Closed'];
$orderColumnIndex = $_POST['order'][0]['column'] ?? 1; // Index in DataTables
$orderDir = $_POST['order'][0]['dir'] ?? 'asc';
$orderColumn = $columns[$orderColumnIndex - 1] ?? 'Journal_name';

$start = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;
$search = $_POST['search']['value'] ?? '';

$date1 = $_POST['date1'] ?? '';
$date2 = $_POST['date2'] ?? '';

// Helper to extract journal name from APA-style biblio field
function extract_journal_name($biblio) {
    if (preg_match('/\)\.\s*(.+?),\s*\d/', $biblio, $matches)) {
        return trim($matches[1]);
    }
    return 'Unknown';
}

// Build WHERE clause
$whereParts = [];
if (!empty($date1) && !empty($date2)) {
    $d1 = mysqli_real_escape_string($conn, $date1);
    $d2 = mysqli_real_escape_string($conn, $date2);
    $whereParts[] = "DATE(Req_date) BETWEEN '$d1' AND '$d2'";
}
$whereClause = count($whereParts) ? "WHERE " . implode(" AND ", $whereParts) : "";

// Get all matching entries
$query = "
    SELECT Journal_name, Bibliographic_details, Status
    FROM entry
    $whereClause
";
$result = mysqli_query($conn, $query);

// Count per journal (resolved name)
$journalStats = [];

while ($row = mysqli_fetch_assoc($result)) {
    $name = trim($row['Journal_name']);
    if (empty($name)) {
        $name = extract_journal_name($row['Bibliographic_details']);
    }

    if (!isset($journalStats[$name])) {
        $journalStats[$name] = [
            'count' => 0,
            'Pending' => 0,
            'Approached' => 0,
            'Received' => 0,
            'Complete' => 0,
            'Closed' => 0,
        ];
    }

    $journalStats[$name]['count'] += 1;
    $status = $row['Status'];
    if (isset($journalStats[$name][$status])) {
        $journalStats[$name][$status] += 1;
    }
}

// Apply search filter if needed
if (!empty($search)) {
    $journalStats = array_filter($journalStats, function($key) use ($search) {
        return stripos($key, $search) !== false;
    }, ARRAY_FILTER_USE_KEY);
}

// Sort
uasort($journalStats, function($a, $b) use ($orderColumn, $orderDir) {
    if ($a[$orderColumn] == $b[$orderColumn]) return 0;
    return ($orderDir === 'asc' ? 1 : -1) * ($a[$orderColumn] <=> $b[$orderColumn]);
});

// Pagination
$recordsTotal = count($journalStats);
$recordsFiltered = $recordsTotal;

$journalStatsPage = array_slice($journalStats, $start, $length, true);

// Build final data
$data = [];
$index = $start + 1;
foreach ($journalStatsPage as $name => $stats) {
    $data[] = [
        $index++,
        htmlspecialchars($name),
        $stats['count'],
        $stats['Pending'],
        $stats['Approached'],
        $stats['Received'],
        $stats['Complete'],
        $stats['Closed']
    ];
}

echo json_encode([
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data' => $data
]);
?>
