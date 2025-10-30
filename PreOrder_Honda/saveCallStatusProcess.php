<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

require_once 'connection/connection.php';

date_default_timezone_set('Asia/Colombo');

$action   = $_POST["action"] ?? '';
$order_id = $_POST["order_id"] ?? 0;
$ans1     = $_POST["ans1"] ?? 0;
$ans2     = $_POST["ans2"] ?? 0;
$ans3     = $_POST["ans3"] ?? 0;
$not1     = $_POST["not_ans1"] ?? 0;
$not2     = $_POST["not_ans2"] ?? 0;
$not3     = $_POST["not_ans3"] ?? 0;

if ($action !== "save_call" || !$order_id) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

try {
    // Check if a record already exists
    $check = Database::search("SELECT id FROM call_records WHERE order_id = ?", "i", $order_id);

    if ($check->num_rows > 0) {
        // Update existing record
        Database::iud(
            "UPDATE call_records 
       SET answared_1=?, answared_2=?, answared_3=?, not_answared_1=?, not_answared_2=?, not_answared_3=? 
       WHERE order_id=?",
            "iiiiiii",
            $ans1,
            $ans2,
            $ans3,
            $not1,
            $not2,
            $not3,
            $order_id
        );
        echo json_encode(["success" => true, "message" => "Call record updated."]);
    } else {
        // Insert new record
        Database::iud(
            "INSERT INTO call_records (order_id, answared_1, answared_2, answared_3, not_answared_1, not_answared_2, not_answared_3) 
       VALUES (?, ?, ?, ?, ?, ?, ?)",
            "iiiiiii",
            $order_id,
            $ans1,
            $ans2,
            $ans3,
            $not1,
            $not2,
            $not3
        );
        echo json_encode(["success" => true, "message" => "Call record created."]);
    }


    // Update order status
    $check = Database::search("SELECT * FROM call_records WHERE order_id = ?", "i", $order_id);
    $checkData = $check->fetch_assoc();

    if ($checkData) {
        if ($checkData["answared_1"] == 1 && $checkData["answared_2"] == 1 && $checkData["answared_3"] == 1) {
            Database::iud("UPDATE `order` SET status = 'passed' WHERE id = ?", "i", $order_id);
        } else if ($checkData["not_answared_1"] == 1 && $checkData["not_answared_2"] == 1 && $checkData["not_answared_3"] == 1) {
            Database::iud("UPDATE `order` SET status = 'failed' WHERE id = ?", "i", $order_id);
        }
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "DB Error: " . $e->getMessage()]);
}
