<?php
include 'config.php';

// Periksa apakah data dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nik = $_POST['nik'] ?? '';
    $name = $_POST['name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $id_subcon = $_POST['id_subcon'] ?? null; // Subcontractor (bisa null)
    $jenis_pekerjaan = $_POST['jenis_pekerjaan'] ?? '';
    $address = $_POST['address'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? null;
    $place_of_birth = $_POST['place_of_birth'] ?? '';
    $mother_name = $_POST['mother_name'] ?? '';
    $age = $_POST['age'] ?? null;
    $phone_num = $_POST['phone_num'] ?? '';
    $date_of_entry = $_POST['date_of_entry'] ?? null;
    $blood_pressure = $_POST['blood_pressure'] ?? '';
    $disease_history = $_POST['disease_history'] ?? '';

    // Validasi sederhana
    if (empty($nik) || empty($name) || empty($gender) || empty($jenis_pekerjaan)) {
        echo json_encode(['success' => false, 'error' => 'Fields NIK, Name, Gender, and Job Type are required.']);
        exit;
    }

    // Gunakan prepared statement untuk mencegah SQL Injection
    $stmt = $conn->prepare("
        INSERT INTO workers 
        (nik, name, gender, id_subcon, jenis_pekerjaan, address, date_of_birth, place_of_birth, mother_name, age, phone_num, date_of_entry, blood_pressure, disease_history, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    // Bind parameter
    $stmt->bind_param(
        "sssssssssiisss",
        $nik,
        $name,
        $gender,
        $id_subcon,
        $jenis_pekerjaan,
        $address,
        $date_of_birth,
        $place_of_birth,
        $mother_name,
        $age,
        $phone_num,
        $date_of_entry,
        $blood_pressure,
        $disease_history
    );

    // Eksekusi query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Worker added successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to add worker']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
