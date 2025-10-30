<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

require_once 'connection/connection.php';

date_default_timezone_set('Asia/Colombo');

$aid     = $_POST["aid"] ?? '';
$name     = $_POST["customer_name"] ?? '';
$nic      = $_POST["nic"] ?? '';
$city     = $_POST["city"] ?? '';
$noOfBike = $_POST["NoOfBike"] ?? ''; // fixed name (no spaces)
$contact  = $_POST["contact"] ?? '';
$model    = $_POST["model"] ?? '';
$capacity = $_POST["capacity"] ?? '';
$payment  = $_POST["payment"] ?? '';
$remarks  = $_POST["remarks"] ?? '';
$location = $_POST["location"] ?? '';

if (!$aid) {
  echo json_encode(["success" => false, "message" => "Unauthorized access."]);
  exit;
}

if (!$name || !$nic || !$city || !$contact || !$noOfBike || !$model || !$capacity || !$payment || !$location) {
  echo json_encode(["success" => false, "message" => "All fields are required."]);
  exit;
}

try {
  $now = date("Y-m-d H:i:s"); // current Sri Lanka time

  $id = Database::iud(
    "INSERT INTO `order` (admin_id, customer_name, nic, city, contact, no_of_bike, model, capacity, remarks, payment, date, location)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
    "issssissssss",
    $aid,
    $name,
    $nic,
    $city,
    $contact,
    $noOfBike,
    $model,
    $capacity,
    $remarks,
    $payment,
    $now,
    $location
  );

  echo json_encode([
    "success" => true,
    "message" => "Pre-order saved successfully.",
    "inserted_id" => $id
  ]);
} catch (Exception $e) {
  echo json_encode(["success" => false, "message" => "DB Error: " . $e->getMessage()]);
}
