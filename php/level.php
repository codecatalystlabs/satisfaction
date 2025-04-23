<?php
// fetch_servicepoint_percentage.php

header('Content-Type: application/json');
require_once 'config.php'; // Ensure this file sets up your $mysqli connection

// Query to get the total count of entries
$totalQuery = "SELECT COUNT(*) AS total FROM satisfaction WHERE servicepoint IS NOT NULL AND servicepoint != ''";
$totalResult = $mysqli->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalEntries = (int)$totalRow['total'];

// Query to get the count of entries per servicepoint
$query = "
    SELECT servicepoint, COUNT(*) AS count
    FROM satisfaction
    WHERE servicepoint IS NOT NULL AND servicepoint != ''
    GROUP BY servicepoint
    ORDER BY count DESC
";

$result = $mysqli->query($query);

$labels = [];
$percentages = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['servicepoint'];
    $percentage = ($totalEntries > 0) ? round(($row['count'] / $totalEntries) * 100, 2) : 0;
    $percentages[] = $percentage;
}

echo json_encode([
    'labels' => $labels,
    'data' => $percentages
]);
?>
