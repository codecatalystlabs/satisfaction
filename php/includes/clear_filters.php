<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

session_start();
session_unset();
echo json_encode(['status' => 'cleared']);
?>
