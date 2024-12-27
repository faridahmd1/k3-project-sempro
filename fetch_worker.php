<?php
include 'config.php';

header('Content-Type: application/json');

// Periksa parameter "action"
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Logika untuk mendapatkan data pekerja berdasarkan ID
    if ($action === 'fetch_worker') {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // Validasi ID sebagai integer
            if (!filter_var($id, FILTER_VALIDATE_INT)) {
                echo json_encode(['error' => 'Invalid ID']);
                exit;
            }

            // Query menggunakan prepared statement untuk fetch data
            $stmt = $conn->prepare("
                SELECT 
                    workers.*, 
                    subcontractors.name AS subcontractor_name 
                FROM 
                    workers 
                LEFT JOIN 
                    subcontractors 
                ON 
                    workers.id_subcon = subcontractors.id 
                WHERE 
                    workers.id = ? 
                    AND workers.deleted_at IS NULL -- Pastikan hanya data yang belum dihapus
            ");
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
        } else {
            echo json_encode(['error' => 'ID not provided']);
        }
    }

    // Logika untuk Pie Chart
    if ($action === 'fetch_pie_data') {
        $query = "
            SELECT 
                subcontractors.name AS subcontractor_name, 
                COUNT(workers.id) AS worker_count 
            FROM 
                workers 
            LEFT JOIN 
                subcontractors 
            ON 
                workers.id_subcon = subcontractors.id 
            WHERE 
                workers.deleted_at IS NULL -- Pastikan hanya data yang belum dihapus
            GROUP BY 
                workers.id_subcon
        ";
        $result = $conn->query($query);

        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = [
                    'subcontractor_name' => $row['subcontractor_name'] ?? 'Unknown',
                    'worker_count' => $row['worker_count']
                ];
            }
        }

        echo json_encode($data);
        exit;
    }

    // Logika untuk Bar Chart
    if ($action === 'fetch_bar_data') {
        $query = "
            SELECT 
                jenis_pekerjaan, 
                COUNT(id) AS job_count 
            FROM 
                workers 
            WHERE 
                deleted_at IS NULL -- Pastikan hanya data yang belum dihapus
            GROUP BY 
                jenis_pekerjaan
        ";
        $result = $conn->query($query);

        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = [
                    'jenis_pekerjaan' => $row['jenis_pekerjaan'] ?? 'Unknown',
                    'job_count' => $row['job_count']
                ];
            }
        }

        echo json_encode($data);
        exit;
    }

    // Jika action tidak valid
    echo json_encode(['error' => 'Invalid action']);
    exit;
}

// Jika action tidak disediakan
echo json_encode(['error' => 'Action parameter is required']);
exit;
?>
