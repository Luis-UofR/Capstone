<?php
include 'db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$account_id = $data['id'];

if ($account_id) {
    $sql = "DELETE FROM accounts WHERE id = '$account_id'";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid account ID']);
}

$conn->close();
?>