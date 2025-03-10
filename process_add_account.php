<?php
// Database connection
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $account_name = $_POST['account_name'];
    $account_type = $_POST['account_type'];
    $balance = 0; // Starting balance

    // Insert new account into the database
    $sql = "INSERT INTO accounts (name, type, balance) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $account_name, $account_type, $balance);

    if ($stmt->execute()) {
        header("Location: chart_of_accounts.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>