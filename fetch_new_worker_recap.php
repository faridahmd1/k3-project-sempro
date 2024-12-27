<?php
include 'config.php';

header('Content-Type: application/json');

// Logika untuk mendapatkan data pekerja berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Validasi ID sebagai integer
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        echo json_encode(['error' => 'Invalid ID']);
        exit;
    }

    // Query menggunakan prepared statement untuk fetch data
    $stmt = $conn->prepare("SELECT 
        new_worker_recaps.*, 
        subcontractors.name AS subcontractor_name 
    FROM 
        new_worker_recaps 
    LEFT JOIN 
        subcontractors 
    ON 
        new_worker_recaps.id_subcon = subcontractors.id 
    WHERE 
        new_worker_recaps.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $worker = $result->fetch_assoc();

        // Jika subcontractor_name tidak ditemukan (null), set default 'Unavailable'
        if (empty($worker['subcontractor_name'])) {
            $worker['subcontractor_name'] = 'Unavailable';
        }

        // Kirim data sebagai JSON
        echo json_encode($worker);
    } else {
        echo json_encode([]);
    }
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'fetch_worker_recap') {
    $query = "
        SELECT 
            COALESCE(subcontractors.name, 'Unknown') AS subcontractor_name, 
            SUM(new_worker_recaps.amount) AS worker_count
        FROM 
            new_worker_recaps
        LEFT JOIN 
            subcontractors 
        ON 
            new_worker_recaps.id_subcon = subcontractors.id
        WHERE 
            new_worker_recaps.deleted_at IS NULL
        GROUP BY 
            new_worker_recaps.id_subcon
    ";
    $result = $conn->query($query);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'subcontractor_name' => $row['subcontractor_name'],
                'worker_count' => (int)$row['worker_count']
            ];
        }
    }

    echo json_encode($data);
    exit;
}



?>
