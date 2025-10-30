<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

require_once 'connection/connection.php';

date_default_timezone_set('Asia/Colombo');

$order_id = $_GET["order_id"] ?? 0;

if (!$order_id) {
    echo json_encode(["success" => false, "message" => "Invalid order_id"]);
    exit;
}

try {
    $res = Database::search(
        "SELECT answared_1, answared_2, answared_3, not_answared_1, not_answared_2, not_answared_3 
     FROM call_records WHERE order_id = ? LIMIT 1",
        "i",
        $order_id
    );

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        echo json_encode(["success" => true, "data" => $row]);
    } else {
        echo json_encode(["success" => true, "data" => null]); // no record yet
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "DB Error: " . $e->getMessage()]);
}
