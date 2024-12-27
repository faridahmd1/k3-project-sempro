<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nik = $_POST['nik'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $jenis_pekerjaan = $_POST['jenis_pekerjaan'];
    $address = $_POST['address'];
    $date_of_birth = $_POST['date_of_birth'];
    $place_of_birth = $_POST['place_of_birth'];
    $mother_name = $_POST['mother_name'];
    $age = $_POST['age'];
    $phone_num = $_POST['phone_num'];
    $blood_pressure = $_POST['blood_pressure'];
    $disease_history = $_POST['disease_history'];

    $sql = "UPDATE workers SET nik = ?, name = ?, gender = ?, jenis_pekerjaan = ?, address = ?, date_of_birth = ?, place_of_birth = ?, mother_name = ?, age = ?, phone_num = ?, blood_pressure = ?, disease_history = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssssisssi', $nik, $name, $gender, $jenis_pekerjaan, $address, $date_of_birth, $place_of_birth, $mother_name, $age, $phone_num, $blood_pressure, $disease_history, $id);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Worker updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to update worker']);
    }
}
