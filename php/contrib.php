<?php
header('Content-Type: application/json');
require_once 'config.php';

$factors = [
    'cleanliness',
    'timeliness_of_services',
    'privacy',
    'respect',
    'availability_of_medicines',
    'availability_of_services',
    'g_access_to_services',
    'needed_time_given',
    'cost_of_services'
];

$counts = [];
$total_dissatisfactions = 0;

foreach ($factors as $factor) {
    $query = "
        SELECT COUNT(*) as count
        FROM satisfaction
        WHERE satifisaction = 'No' AND $factor = 'N'
    ";
    $result = $mysqli->query($query);
    if ($result && $row = $result->fetch_assoc()) {
        $count = (int)$row['count'];
        $counts[$factor] = $count;
        $total_dissatisfactions += $count;
    }
}

// Sort descending
arsort($counts);

// Get top contributors to 80%
$cumulative = 0;
$threshold = 0.8 * $total_dissatisfactions;
$top_factors = [];

foreach ($counts as $factor => $count) {
    if ($cumulative >= $threshold) break;
    $top_factors[$factor] = $count;
    $cumulative += $count;
}

// Prepare for Chart.js
$response = [
    'labels' => array_map('ucwords', str_replace('_', ' ', array_keys($top_factors))),
    'datasets' => [
        [
            'label' => 'Factors Contributing to 80% of Dissatisfaction',
            'data' => array_values($top_factors),
            'backgroundColor' => array_map(function () {
                return 'rgba(' . rand(0,255) . ',' . rand(0,255) . ',' . rand(0,255) . ', 0.6)';
            }, range(1, count($top_factors))),
            'borderColor' => array_map(function () {
                return 'rgba(0,0,0,0.8)';
            }, range(1, count($top_factors))),
            'borderWidth' => 1
        ]
    ]
];

echo json_encode($response);
?>
