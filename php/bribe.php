<?php

header('Content-Type: application/json');
//session_start();
require_once 'session.php';
require_once 'config.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$filters = $_SESSION['filters'] ?? [];

// Build your SQL query based on filters
$query = "SELECT region, 
       COUNT(*) AS total_clients, 
       SUM(CASE WHEN bribe = 'Yes' THEN 1 ELSE 0 END) AS bribe_count
FROM satisfaction
WHERE 1=1";

// Apply filters if set
if (!empty($filters['region'])) {
    $region = $mysqli->real_escape_string($filters['region']);
    $query .= " AND region = '$region'";
}
if (!empty($filters['district'])) {
    $district = $mysqli->real_escape_string($filters['district']);
    $query .= " AND district = '$district'";
}
if (!empty($filters['facility'])) {
    $facility = $mysqli->real_escape_string($filters['facility']);
    $query .= " AND facility = '$facility'";
}
if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
    $date_from = $mysqli->real_escape_string($filters['date_from']);
    $date_to = $mysqli->real_escape_string($filters['date_to']);
    $query .= " AND date BETWEEN '$date_from' AND '$date_to'";
}

$query .= " GROUP BY region";

$result = $mysqli->query($query);

$data = [
    'labels' => [],
    'datasets' => [
        [
            'label' => 'Clients Surveyed',
            'backgroundColor' => 'rgba(78, 115, 223, 0.5)',
            'borderColor' => 'rgba(78, 115, 223, 1)',
            'data' => []
        ],
        [
            'label' => 'Reported Paying Bribes',
            'backgroundColor' => 'rgba(28, 200, 138, 0.5)',
            'borderColor' => 'rgba(28, 200, 138, 1)',
            'data' => []
        ],
        [
            'label' => '% Clients Reported Paying Bribes',
            'backgroundColor' => 'rgba(54, 185, 204, 0.5)',
            'borderColor' => 'rgba(54, 185, 204, 1)',
            'data' => []
        ]
    ]
];

while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['region'];
    $data['datasets'][0]['data'][] = (int)$row['total_clients'];
    $data['datasets'][1]['data'][] = (int)$row['bribe_count'];
    $percentage = $row['total_clients'] > 0 ? round(($row['bribe_count'] / $row['total_clients']) * 100, 2) : 0;
    $data['datasets'][2]['data'][] = $percentage;
}

//header('Content-Type: application/json');
echo json_encode($data);
?>
