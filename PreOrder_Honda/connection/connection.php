<?php

class Database
{
    public static $connection;

    public static function setUpConnection()
    {
        if (!isset(Database::$connection)) {
            Database::$connection = new mysqli("localhost", "root", "", "honda_preorder", 3306);
            if (Database::$connection->connect_error) {
                die("Connection failed: " . Database::$connection->connect_error);
            }
        }
    }

    public static function iud($q, $types = null, ...$params)
    {
        Database::setUpConnection();
        if ($types && $params) {
            $stmt = Database::$connection->prepare($q);
            if ($stmt === false) {
                die("Error preparing query: " . Database::$connection->error);
            }
            $stmt->bind_param($types, ...$params);
            if (!$stmt->execute()) {
                die("Error executing query: " . $stmt->error);
            }
            $insertId = Database::$connection->insert_id; // <-- get last inserted id
            return $insertId;
            $stmt->close();
        } else {
            if (!Database::$connection->query($q)) {
                die("Error in query: " . Database::$connection->error);
            }
        }
    }

    public static function search($q, $types = null, ...$params)
    {
        Database::setUpConnection();
        if ($types && $params) {
            $stmt = Database::$connection->prepare($q);
            if ($stmt === false) {
                die("Error preparing query: " . Database::$connection->error);
            }
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $resultset = $stmt->get_result();
            $stmt->close();
        } else {
            $resultset = Database::$connection->query($q);
            if ($resultset === false) {
                die("Error in query: " . Database::$connection->error);
            }
        }
        return $resultset;
    }
}
