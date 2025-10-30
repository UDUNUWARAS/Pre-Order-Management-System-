<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

require_once 'connection/connection.php';

session_start();
if (isset($_SESSION["a"])) {
  $aid = $_SESSION["a"]['id'];
}

$query = $_GET["query"] ?? '';

try {
  $sql = "SELECT * FROM `order`";
  $params = [];
  $types = "";

  if (!empty($query)) {
    $sql .= " WHERE admin_id=? AND status='pending' AND (customer_name LIKE ? OR nic LIKE ? OR city LIKE ?)";
    $wildcard = "%" . $query . "%";
    $params = [$aid, $wildcard, $wildcard, $wildcard];
    $types = "isss";
  } else {
    $sql .= " WHERE admin_id=? AND status='pending'";
    $params = [$aid];
    $types = "i";
  }

  $result = Database::search($sql, $types, ...$params);

  $data = [];
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }

  echo json_encode($data);
} catch (Exception $e) {
  echo json_encode(["status" => "error", "message" => "DB Error: " . $e->getMessage()]);
}
