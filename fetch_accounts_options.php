<?php
include 'db_connection.php';

// Retrieve accounts
$sql = "SELECT id, name FROM accounts";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $account = $row['name'];
    $id = $row['id'];
    
    // Need to include ID to know which account is selected

    echo "<option value='$id'>$account</option>";
}

?>