<?php
include('dbcon.php');
header('Content-Type: application/json');

// Optional: date filtering from POST
$date1 = $_POST['date1'] ?? '';
$date2 = $_POST['date2'] ?? '';

$where = "";
if (!empty($date1) && !empty($date2)) {
    $d1 = mysqli_real_escape_string($conn, $date1);
    $d2 = mysqli_real_escape_string($conn, $date2);
    $where = "WHERE DATE(Req_date) BETWEEN '$d1' AND '$d2'";
}

$query = mysqli_query($conn, "SELECT Journal_name, Bibliographic_details FROM entry $where");

$journal_counts = [];

function extract_journal_name($biblio) {
    // Match pattern: after the last period of the title, the journal name follows
    // Try to match: ). <journal name>, digit...
    if (preg_match('/\)\.\s*(.+?),\s*\d/', $biblio, $matches)) {
        return trim($matches[1]);
    }
    return 'Unknown';
}

while ($row = mysqli_fetch_assoc($query)) {
    $journal = trim($row['Journal_name']);

    if (empty($journal)) {
        $journal = extract_journal_name($row['Bibliographic_details']);
    }

    if (!empty($journal)) {
        if (!isset($journal_counts[$journal])) {
            $journal_counts[$journal] = 0;
        }
        $journal_counts[$journal]++;
    }
}

// Sort by count descending and take top 10
arsort($journal_counts);
$top10 = array_slice($journal_counts, 0, 10, true);

// Format for JSON
$data = [];
foreach ($top10 as $journal => $count) {
    $data[] = [
        'Journal_name' => htmlspecialchars($journal),
        'Count' => $count
    ];
}

echo json_encode($data);
?>
