<?php
header('Content-Type: application/json');
require_once 'php/config.php'; // Ensure this file sets up your $mysqli connection

// Define satisfaction categories to match chart labels
$data = [
    'Satisfied' => 0,
    'Dissatisfied' => 0
];

// Query to count responses
$query = "
    SELECT 
        SUM(CASE WHEN satifisaction = 'Yes' THEN 1 ELSE 0 END) AS satisfied,
        SUM(CASE WHEN satifisaction = 'No' THEN 1 ELSE 0 END) AS dissatisfied
    FROM satisfaction
";

$result = $mysqli->query($query);
if ($result && $row = $result->fetch_assoc()) {
    $data['Satisfied'] = (int)$row['satisfied'];
    $data['Dissatisfied'] = (int)$row['dissatisfied'];
}

// Format data for Chart.js
$response = [
    'labels' => array_keys($data),
    'datasets' => [
        [
            'label' => 'Overall Satisfaction',
            'data' => array_values($data),
            'backgroundColor' => [
                'rgba(75, 192, 192, 0.6)',
                'rgba(255, 99, 132, 0.6)'
            ],
            'borderColor' => [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            'borderWidth' => 1
        ]
    ]
];

echo json_encode($response);
?>
