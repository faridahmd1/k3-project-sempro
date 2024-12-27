<?php
include 'config.php';

// Periksa apakah data dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_subcon = $_POST['id_subcon'] ?? null; // Subcontractor (bisa null)
    $amount = $_POST['amount'] ?? '';

    // Gunakan prepared statement untuk mencegah SQL Injection
    $stmt = $conn->prepare("
        INSERT INTO new_worker_recaps 
        (id_subcon, amount, created_at) 
        VALUES (?, ?, NOW())
    ");

    // Bind parameter   
    $stmt->bind_param(
        "sssssssssiisss",
        $id_subcon,
        $amount
    );

    // Eksekusi query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Recap added successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to add Recap']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
