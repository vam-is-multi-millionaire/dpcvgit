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
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100 dashboard-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="card-icon me-3">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Manage Classes</h5>
                        <p class="card-text text-muted">Add, edit, or delete class information.</p>
                    </div>
                </div>
            </div>
            <a href="classes.php" class="stretched-link"></a>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100 dashboard-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="card-icon me-3">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Manage Uploads</h5>
                        <p class="card-text text-muted">Upload new files and manage existing ones.</p>
                    </div>
                </div>
            </div>
            <a href="uploads.php" class="stretched-link"></a>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100 dashboard-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="card-icon me-3">
                        <i class="fas fa-bars"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Manage Menu</h5>
                        <p class="card-text text-muted">Add, edit, or delete menu items.</p>
                    </div>
                </div>
            </div>
            <a href="menu.php" class="stretched-link"></a>
        </div>
    </div>
</div>

<?php include_once('includes/admin_footer.php'); ?>