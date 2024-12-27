<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Fetch the existing images before deletion
    $sql = "SELECT tool_box_image, activity_image FROM daily_reports WHERE id = $id";
    $result = $conn->query($sql);
    $images = $result->fetch_assoc();

    // Delete the record from the database
    $delete_sql = "DELETE FROM daily_reports WHERE id = $id";
    if ($conn->query($delete_sql) === TRUE) {
        // Delete images from the server
        if ($images['tool_box_image']) {
            unlink("uploads/" . $images['tool_box_image']);
        }
        if ($images['activity_image']) {
            unlink("uploads/" . $images['activity_image']);
        }
        // echo "Record deleted successfully";
        echo "<script>alert('Deleted successfully'); window.location.href='index.php?page=dailyreport';</script>";
    } else {
        // echo "Error deleting record: " . $conn->error;
        echo "<script>alert('Failed to deleting record: " . $conn->error . "'); window.location.href='index.php?page=dailyreport';</script>";
    }
    
    if ($conn->query($delete_sql) === TRUE) {
    } else {
    }
}
?>