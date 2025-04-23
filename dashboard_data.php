<?php
header('Content-Type: application/json');
require_once 'php/config.php'; // Ensure this file sets up your $mysqli connection

$data = [];

// Total number of clients
$result = $mysqli->query("SELECT COUNT(*) AS total_clients FROM satisfaction");
$row = $result->fetch_assoc();
$data['total_clients'] = (int)$row['total_clients'];

// Number of satisfied clients
$result = $mysqli->query("SELECT COUNT(*) AS satisfied_clients FROM satisfaction WHERE satifisaction = 'Yes'");
$row = $result->fetch_assoc();
$data['satisfied_clients'] = (int)$row['satisfied_clients'];

// Overall satisfaction percentage
$data['overall_satisfaction'] = $data['total_clients'] > 0
    ? round(($data['satisfied_clients'] / $data['total_clients']) * 100, 2)
    : 0;

// Entries from males
$result = $mysqli->query("SELECT COUNT(*) AS male_entries FROM satisfaction WHERE demo_gender = 'Male'");
$row = $result->fetch_assoc();
$data['male_entries'] = (int)$row['male_entries'];

// Entries from females
$result = $mysqli->query("SELECT COUNT(*) AS female_entries FROM satisfaction WHERE demo_gender = 'Female'");
$row = $result->fetch_assoc();
$data['female_entries'] = (int)$row['female_entries'];

// Facilities asking for bribes
$result = $mysqli->query("SELECT COUNT(DISTINCT facility) AS facilities_asking_bribes FROM satisfaction WHERE bribe = 'Yes'");
$row = $result->fetch_assoc();
$data['facilities_asking_bribes'] = (int)$row['facilities_asking_bribes'];

// Average cost of services
$result = $mysqli->query("SELECT AVG(cost_of_services) AS avg_cost FROM satisfaction");
$row = $result->fetch_assoc();
$data['average_cost'] = round((float)$row['avg_cost'], 2);

// Availability of medicines percentage
$result = $mysqli->query("SELECT AVG(availability_of_medicines) AS avg_medicine_availability FROM satisfaction");
$row = $result->fetch_assoc();
$data['medicine_availability'] = round(((float)$row['avg_medicine_availability'] / 3) * 100, 2); // Assuming scale is 1-3

// Encounters responded to in time
$result = $mysqli->query("SELECT COUNT(*) AS timely_encounters FROM satisfaction WHERE timeliness_of_services >= 2");
$row = $result->fetch_assoc();
$data['timely_encounters'] = (int)$row['timely_encounters'];

// Total entries today
$today = date('Y-m-d');
$result = $mysqli->query("SELECT COUNT(*) AS entries_today FROM satisfaction WHERE DATE(system_submission_date) = '$today'");
$row = $result->fetch_assoc();
$data['entries_today'] = (int)$row['entries_today'];

echo json_encode($data);
?>
