<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "
        SELECT daily_reports.*, subcontractors.name AS subcontractor_name 
        FROM daily_reports 
        LEFT JOIN subcontractors ON daily_reports.id_subcon = subcontractors.id 
        WHERE daily_reports.id = $id
    ";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode([]);
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'fetch_work_area_data') {
    $query = "
        SELECT work_area, COUNT(*) AS report_count
        FROM daily_reports
        WHERE deleted_at IS NULL
        GROUP BY work_area
        ORDER BY report_count DESC
    ";
    $result = $conn->query($query);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'work_area' => $row['work_area'],
                'report_count' => $row['report_count']
            ];
        }
    }

    echo json_encode($data);
    exit;
}
?>
