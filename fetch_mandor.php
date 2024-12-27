<?php
include 'config.php';

header('Content-Type: application/json'); // Ensure JSON response
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'fetch') {
        // Fetch all subcontractors data
        $query = "SELECT id, name, created_at FROM subcontractors";
        $result = $conn->query($query);

        if ($result) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Failed to fetch data']);
        }
        exit;
    } elseif ($action === 'add') {
        // Add new subcontractors
        $name = $_POST['name'];
        $created_at = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("INSERT INTO subcontractors (name, created_at) VALUES (?, ?)");
        $stmt->bind_param('ss', $name, $created_at);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add subcontractors']);
        }
        exit;
    } elseif ($action === 'edit') {
        // Edit existing subcontractors
        $id = $_POST['id'];
        $name = $_POST['name'];
        $stmt = $conn->prepare("UPDATE subcontractors SET name = ? WHERE id = ?");
        $stmt->bind_param('si', $name, $id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update subcontractors']);
        }
        exit;
    } elseif ($action === 'delete') {
        // Delete subcontractors
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM subcontractors WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete subcontractors']);
        }
        exit;
    }
}

echo json_encode(['error' => 'Invalid action']);
exit;
?>
