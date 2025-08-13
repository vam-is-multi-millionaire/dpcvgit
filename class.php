<?php
include_once('includes/header.php');
include_once('includes/helpers.php');

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$class_id = $_GET['id'];

// Fetch class details
$stmt_class = $pdo->prepare("SELECT * FROM classes WHERE id = ?");
$stmt_class->execute([$class_id]);
$class = $stmt_class->fetch();

if (!$class) {
    // You might want to redirect to a 404 page here
    header('Location: index.php');
    exit();
}

// Fetch uploads for this class
$stmt_uploads = $pdo->prepare("
    SELECT u.filename, u.filepath, u.upload_date 
    FROM uploads u 
    WHERE u.class_id = ? 
    ORDER BY u.upload_date DESC
");
$stmt_uploads->execute([$class_id]);
$uploads = $stmt_uploads->fetchAll();
?>

<div class="container py-5">
    <div class="class-header p-4 p-md-5 mb-4 theme-aware-bg rounded-3 text-center text-md-start">
        <h1 class="display-5 fw-bold"><?php echo htmlspecialchars($class['name']); ?></h1>
        <p class="fs-4"><?php echo htmlspecialchars($class['description']); ?></p>
    </div>

    <h2 class="mb-4 text-center text-md-start">Uploaded Files</h2>
    <div class="row">
        <?php if ($uploads): ?>
            <?php foreach ($uploads as $upload): 
            ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100 shadow-sm upload-card">
                        <a href="<?php echo htmlspecialchars($upload['filepath']); ?>" target="_blank" class="text-decoration-none text-dark">
                            <div class="card-img-top-container">
                                <?php echo generate_file_preview($upload['filename'], $upload['filepath']); ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($upload['filename']); ?></h5>
                                <p class="card-text text-muted">Uploaded on: <?php echo date('F j, Y', strtotime($upload['upload_date'])); ?></p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info" role="alert">
                    No files have been uploaded for this class yet.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>

