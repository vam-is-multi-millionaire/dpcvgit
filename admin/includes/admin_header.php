<?php
include_once('../includes/config.php');
// Simple check if the user is logged in.
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?php echo SITE_NAME; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-dark border-right" id="sidebar-wrapper">
        <div class="sidebar-heading text-white"><?php echo SITE_NAME; ?> Admin</div>
        <div class="list-group list-group-flush">
            <a href="dashboard.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a href="classes.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="fas fa-chalkboard-teacher me-2"></i>Classes
            </a>
            <a href="uploads.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="fas fa-cloud-upload-alt me-2"></i>Uploads
            </a>
            <a href="menu.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="fas fa-bars me-2"></i>Menu
            </a>
            <a href="../index.php" class="list-group-item list-group-item-action bg-dark text-white" target="_blank">
                <i class="fas fa-eye me-2"></i>View Site
            </a>
        </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <button class="btn btn-primary" id="menu-toggle"><i class="fas fa-bars"></i></button>

                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid px-4">

