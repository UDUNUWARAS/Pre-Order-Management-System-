<?php

require "connection/connection.php";

$email = $_POST["e"];
$password = $_POST["p"];

if (empty($email)) {
    echo "Please enter email address";
} else if (strlen($email) > 100) {
    echo "Email Address should be less than 100 characters.";
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid Email Address.";
} else if (preg_match('/[!#$%^&*()_+{}\[\]:;<>,.?~\\/\-\'"]/', $password)) {
    echo "Password should not contain special characters";
} else if (empty($password)) {
    echo "Please enter password";
} else {
    $sql = "SELECT * FROM `admin` WHERE `username` = ? AND `password` = ?";

    $resultset = Database::search($sql, "ss", $email, $password);
    $n = $resultset->num_rows;

    if ($n == 1) {
        $data = $resultset->fetch_assoc();
        echo "success";
        session_start();
        $_SESSION["a"] = $data;
    } else {
        echo "Invalid Email or Password";
    }
}
