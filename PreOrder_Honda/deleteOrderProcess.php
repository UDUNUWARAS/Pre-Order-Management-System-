<?php
require 'connection/connection.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'Missing order ID']);
    exit;
}

try {
    Database::iud("DELETE FROM `order` WHERE id = ?", "i", $id);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
