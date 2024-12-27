<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $id_subcon = $_POST['id_subcon'];
    $amount = $_POST['amount'];

    $sql = "UPDATE new_worker_recaps SET id_subcon = ?, amount = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $id_subcon, $amount, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Worker updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update worker: ' . $conn->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>