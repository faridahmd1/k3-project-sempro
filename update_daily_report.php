<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $id_subcon = $_POST['id_subcon'];
    $work_area = $_POST['work_area'];
    $work_unit = $_POST['work_unit'];
    $working_date = $_POST['working_date'];
    $working_hours = $_POST['working_hours'];
    $work_aids = $_POST['work_aids'];

    // Fetch existing images from the database
    $sql = "SELECT tool_box_image, activity_image FROM daily_reports WHERE id = $id";
    $result = $conn->query($sql);
    $existing_images = $result->fetch_assoc();

    // Initialize variables for images
    $tool_box_image = $existing_images['tool_box_image'];
    $activity_image = $existing_images['activity_image'];

    // Check if new tool box image is uploaded
    if (!empty($_FILES['tool_box_image']['name'])) {
        // Delete the old tool box image if it exists
        if ($tool_box_image) {
            unlink("uploads/" . $tool_box_image);
        }
        // Upload the new tool box image
        $tool_box_image = $_FILES['tool_box_image']['name'];
        move_uploaded_file($_FILES['tool_box_image']['tmp_name'], "uploads/" . $tool_box_image);
    } else {
        // If no new image is uploaded, keep the existing one
        $tool_box_image = $existing_images['tool_box_image'];
    }

    // Check if new activity image is uploaded
    if (!empty($_FILES['activity_image']['name'])) {
        // Delete the old activity image if it exists
        if ($activity_image) {
            unlink("uploads/" . $activity_image);
        }
        // Upload the new activity image
        $activity_image = $_FILES['activity_image']['name'];
        move_uploaded_file($_FILES['activity_image']['tmp_name'], "uploads/" . $activity_image);
    } else {
        // If no new image is uploaded, keep the existing one
        $activity_image = $existing_images['activity_image'];
    }

    // Update the report
    $update_sql = "UPDATE daily_reports SET 
        id_subcon = '$id_subcon',
        work_area = '$work_area',
        work_unit = '$work_unit',
        working_date = '$working_date',
        working_hours = '$working_hours',
        work_aids = '$work_aids',
        tool_box_image = '$tool_box_image',
        activity_image = '$activity_image'
        WHERE id = $id";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Record updated successfully'); window.location.href='index.php?page=daily_reports';</script>";
    } else {
        echo "<script>alert('Error updating record: " . $conn->error . "'); window.location.href='index.php?page=daily_reports';</script>";
    }
}
?>