<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_subcon = $_POST['id_subcon'];
    $work_area = $_POST['work_area'];
    $work_unit = $_POST['work_unit'];
    $working_date = $_POST['working_date'];
    $working_hours = $_POST['working_hours'];
    $work_aids = $_POST['work_aids'];

    $tool_box_image = '';
    $activity_image = '';

    if (!empty($_FILES['tool_box_image']['name'])) {
        $tool_box_image = time() . '_' . $_FILES['tool_box_image']['name'];
        move_uploaded_file($_FILES['tool_box_image']['tmp_name'], 'uploads/' . $tool_box_image);
    }

    if (!empty($_FILES['activity_image']['name'])) {
        $activity_image = time() . '_' . $_FILES['activity_image']['name'];
        move_uploaded_file($_FILES['activity_image']['tmp_name'], 'uploads/' . $activity_image);
    }

    $sql = "
        INSERT INTO daily_reports (id_subcon, work_area, work_unit, working_date, working_hours, work_aids, tool_box_image, activity_image, created_at)
        VALUES ('$id_subcon', '$work_area', '$work_unit', '$working_date', '$working_hours', '$work_aids', '$tool_box_image', '$activity_image', NOW())
    ";

    if ($conn->query($sql)) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
