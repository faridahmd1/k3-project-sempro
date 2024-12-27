<?php
// Include common layout files
include 'layouts/header.php';
include 'layouts/navbar.php';
include 'layouts/sidebar.php';

// Determine which page to load
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$pageFile = "pages/$page.php";

// Check if the requested page file exists
if (file_exists($pageFile)) {
    include $pageFile;
} else {
    echo "<div class='content-wrapper'><section class='content'>
        <div class='container-fluid'><h1>404 - Page Not Found</h1></div></section></div>";
}

include 'layouts/footer.php';
?>
