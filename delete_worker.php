<?php
include 'config.php';

// Cek apakah ID sudah diterima
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Validasi ID sebagai integer
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        echo json_encode(['error' => 'Invalid ID']);
        exit;
    }

    // Menggunakan prepared statement untuk mencegah SQL Injection
    $stmt = $conn->prepare("UPDATE workers SET deleted_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Berhasil dihapus (soft delete)
        echo json_encode(['success' => true, 'message' => 'Worker deleted successfully']);
    } else {
        // Gagal menghapus
        echo json_encode(['success' => false, 'error' => 'Failed to delete worker']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'ID not provided']);
}
?>
