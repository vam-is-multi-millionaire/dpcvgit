<?php
include_once('includes/header.php');
include_once('includes/helpers.php');

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

$classes = [];
$files = [];

if (!empty($query)) {
    // Search for classes
    $stmt_classes = $pdo->prepare("SELECT * FROM classes WHERE name LIKE ? OR description LIKE ?");
    $stmt_classes->execute(["%$query%", "%$query%"]);
    $classes = $stmt_classes->fetchAll();

    // Search for files
    $stmt_files = $pdo->prepare("
        SELECT u.filename, u.filepath, c.name as class_name 
        FROM uploads u 
        LEFT JOIN classes c ON u.class_id = c.id 
        WHERE u.filename LIKE ?
    ");
    $stmt_files->execute(["%$query%"]);
    $files = $stmt_files->fetchAll();
}
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-4">
            <h1 class="display-4">Search</h1>
            <form action="search.php" method="GET" class="row g-3 justify-content-center">
                <div class="col-auto">
                    <input type="text" class="form-control" name="query" placeholder="Search for classes or files..." value="<?php echo htmlspecialchars($query); ?>" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($query)): ?>
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <h3>Classes</h3>
                <?php if ($classes): ?>
                    <div class="list-group">
                        <?php foreach ($classes as $class): ?>
                            <a href="class.php?id=<?php echo $class['id']; ?>" class="list-group-item list-group-item-action">
                                <?php echo htmlspecialchars($class['name']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">No classes found matching your search.</div>
                <?php endif; ?>
            </div>

            <div class="col-lg-8">
                <h3>Files</h3>
                <?php if ($files): ?>
                    <div class="row">
                        <?php foreach ($files as $file): 
                            $file_path_for_display = htmlspecialchars(substr($file['filepath'], 3));
                        ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow-sm upload-card">
                                    <a href="<?php echo $file_path_for_display; ?>" target="_blank" class="text-decoration-none text-dark">
                                        <div class="card-img-top-container">
                                            <?php echo generate_file_preview($file['filename'], $file['filepath']); ?>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($file['filename']); ?></h5>
                                            <p class="card-text text-muted"><?php echo htmlspecialchars($file['class_name'] ?? 'General'); ?></p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">No files found matching your search.</div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include_once('includes/footer.php'); ?>
