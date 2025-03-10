<?php
// Database connection
include 'db_connection.php';

// Fetch accounts from the database, sorted alphabetically by name
$result = $conn->query("SELECT id, name, type, balance FROM accounts ORDER BY name ASC");

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['type']) . "</td>";
    echo "<td>\${$row['balance']}</td>";
    echo "<td><button class='btn btn-danger btn-sm' onclick='deleteAccount({$row['id']})'>Delete</button></td>";
    echo "</tr>";
}

$conn->close();
?>