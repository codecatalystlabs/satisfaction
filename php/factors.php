<?php
require_once 'session.php';
require_once 'config.php';

$filters = isset($_SESSION['filters']) ? $_SESSION['filters'] : [];

// Build your SQL query based on filters
$query = "SELECT * FROM your_table WHERE 1=1";

if (!empty($filters['region'])) {
    $region = $mysqli->real_escape_string($filters['region']);
    $query .= " AND region = '$region'";
}

// Add more conditions based on other filters

$result = $mysqli->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
