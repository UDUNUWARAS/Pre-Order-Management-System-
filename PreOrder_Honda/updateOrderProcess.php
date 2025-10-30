<?php
require 'connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id            = $_POST['id'] ?? null;
    $customer_name = $_POST["customer_name"] ?? '';
    $nic           = $_POST["nic"] ?? '';
    $city          = $_POST["city"] ?? '';
    $contact       = $_POST["contact"] ?? '';
    $noOfBike      = $_POST["NoOfBike"] ?? ''; // added
    $model         = $_POST["model"] ?? '';
    $capacity      = $_POST["capacity"] ?? '';
    $payment       = $_POST["payment"] ?? '';
    $remarks       = $_POST["remarks"] ?? '';
    $location      = $_POST["location"] ?? '';

    if (!$id) {
        echo json_encode(['success' => false, 'error' => 'Missing order ID']);
        exit;
    }

    if (empty($customer_name) || empty($nic) || empty($city) || empty($contact) || 
        empty($noOfBike) || empty($model) || empty($capacity) || empty($payment) || empty($location)) {
        echo json_encode(['success' => false, 'error' => 'Required fields are missing']);
        exit;
    }

    try {
        Database::iud(
            "UPDATE `order` 
             SET customer_name=?, nic=?, city=?, contact=?, no_of_bike=?, model=?, capacity=?, payment=?, remarks=?, location=? 
             WHERE id=?",
            "ssssisisssi",
            $customer_name, $nic, $city, $contact, $noOfBike, $model, $capacity, $payment, $remarks, $location, $id
        );

        echo json_encode(['success' => true, 'message' => 'Order updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
