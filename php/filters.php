<?php
require_once 'session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set filters
    $_SESSION['filters'] = $_POST['filters'];
    echo json_encode(['status' => 'success']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get filters
    $filters = isset($_SESSION['filters']) ? $_SESSION['filters'] : [];
    echo json_encode($filters);
}
?>
