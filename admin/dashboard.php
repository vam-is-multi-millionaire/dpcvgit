<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}
include_once('includes/admin_header.php');
?>

<h1 class="mt-4">Dashboard</h1>
<p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! Here's an overview of your site.</p>

<div class="row mt-4">
    <div class="col-md-4 mb-4">
        <a href="classes.php" class="dashboard-card d-block">
            <div class="card-icon"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="card-body">
                <h5 class="card-title">Manage Classes</h5>
                <p class="card-text">Add, edit, or delete class information.</p>
            </div>
        </a>
    </div>
    <div class="col-md-4 mb-4">
        <a href="uploads.php" class="dashboard-card d-block">
            <div class="card-icon"><i class="fas fa-cloud-upload-alt"></i></div>
            <div class="card-body">
                <h5 class="card-title">Manage Uploads</h5>
                <p class="card-text">Upload new files and manage existing ones.</p>
            </div>
        </a>
    </div>
    <div class="col-md-4 mb-4">
        <a href="menu.php" class="dashboard-card d-block">
            <div class="card-icon"><i class="fas fa-bars"></i></div>
            <div class="card-body">
                <h5 class="card-title">Manage Menu</h5>
                <p class="card-text">Add, edit, or delete menu items.</p>
            </div>
        </a>
    </div>
</div>

<?php include_once('includes/admin_footer.php'); ?>

