<?php
header('Content-Type: application/json');
require_once 'php/config.php'; // Ensure this file sets up your $mysqli connection
// require_once 'php/filters.php';
// require_once 'php/session.php';
// Optional: Apply filters from session if needed


$filters = $_SESSION['filters'] ?? [];

// Build the base query
$query = "SELECT reporting_period, COUNT(*) AS total_entries, 
          SUM(CASE WHEN satifisaction = 'Yes' THEN 1 ELSE 0 END) AS satisfied_clients
          FROM satisfaction WHERE reporting_period >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 12 MONTH), '%Y-%m-01')
  AND reporting_period < DATE_FORMAT(CURDATE(), '%Y-%m-01')";

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
    $query .= " AND reporting_period BETWEEN '$date_from' AND '$date_to'";
}

$query .= " GROUP BY reporting_period ORDER BY reporting_period ASC";

$result = $mysqli->query($query);

$data = [
    'labels' => [],
    'datasets' => [
        [
            'label' => 'Satisfaction Rate (%)',
            'data' => [],
            'fill' => false,
            'borderColor' => 'rgba(75, 192, 192, 1)',
            'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
            'tension' => 0.1
        ]
    ]
];

while ($row = $result->fetch_assoc()) {
    $date = $row['reporting_period'];
    $total = (int)$row['total_entries'];
    $satisfied = (int)$row['satisfied_clients'];
    $percentage = $total > 0 ? round(($satisfied / $total) * 100, 2) : 0;

    $data['labels'][] = $date;
    $data['datasets'][0]['data'][] = $percentage;
}

echo json_encode($data);
?>
