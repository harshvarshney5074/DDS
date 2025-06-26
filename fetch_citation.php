<?php
function stripTagsDeep($text) {
    return is_string($text) ? strip_tags($text) : '';
}

function formatAPA($data) {
    // Authors
    $authors = [];
    if (!empty($data['author']) && is_array($data['author'])) {
        foreach ($data['author'] as $a) {
            if (isset($a['family'])) {
                $initials = '';
                if (!empty($a['given'])) {
                    $parts = explode(' ', $a['given']);
                    foreach ($parts as $p) {
                        $initials .= strtoupper(substr($p, 0, 1)) . '.';
                    }
                }
                $authors[] = $a['family'] . ', ' . $initials;
            } elseif (isset($a['literal'])) {
                $authors[] = $a['literal'];
            }
        }
    }

    $citation = count($authors) ? implode(', ', $authors) : '[No author]';

    // Year
    $year = $data['issued']['date-parts'][0][0] ?? 'n.d.';
    $citation .= " ($year). ";

    // Title
    $title = stripTagsDeep($data['title'][0] ?? '');
    $citation .= $title;

    // Source/container
    $type = $data['type'] ?? '';
    $container = stripTagsDeep($data['container-title'][0] ?? '');

    if ($type === 'book-chapter') {
        // Book chapter formatting
        if ($container) {
            $citation .= ". In " . $container;
        }
        if (!empty($data['volume'])) {
            $citation .= " (Vol. " . stripTagsDeep($data['volume']) . ")";
        }
        if (!empty($data['page'])) {
            $citation .= ", pp. " . stripTagsDeep($data['page']);
        }
        if (!empty($data['publisher'])) {
            $citation .= ". " . stripTagsDeep($data['publisher']);
        }
    } else {
        // Journal article, conference, etc.
        if ($container) {
            $citation .= ". " . $container;
        }
        if (!empty($data['volume'])) {
            $citation .= ", " . stripTagsDeep($data['volume']);
            if (!empty($data['issue'])) {
                $citation .= "(" . stripTagsDeep($data['issue']) . ")";
            }
        }
        if (!empty($data['page'])) {
            $citation .= ", " . stripTagsDeep($data['page']);
        }
    }

    // DOI
    if (!empty($data['DOI'])) {
        $citation .= ". https://doi.org/" . stripTagsDeep($data['DOI']);
    }

    return trim($citation);
}

// Main handler
$doi = $_POST['doi'] ?? '';
if (!$doi) {
    http_response_code(400);
    echo 'Missing DOI';
    exit;
}

// Normalize DOI
$doi_stripped = preg_replace('#^https?://(dx\.)?doi\.org/#', '', trim($doi));

// CrossRef API request
$crossref_url = "https://api.crossref.org/works/" . urlencode($doi_stripped);
$ch = curl_init($crossref_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
$response = curl_exec($ch);
curl_close($ch);

// Parse and format
$data = json_decode($response, true);
if (!empty($data['message'])) {
    $citation = formatAPA($data['message']);
    echo $citation ?: 'Could not format citation.';
} else {
    echo 'Could not retrieve citation.';
}
?>
