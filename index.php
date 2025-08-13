<?php 
include_once('includes/header.php'); 
include_once('includes/helpers.php'); 
?>

<div class="container-fluid p-0">
    <!-- Hero Section -->
    <section id="hero" class="text-white text-center bg-dark d-flex align-items-center justify-content-center">
        <div class="container">
            <h1 class="display-3">Welcome to DPCV</h1>
            <p class="lead">Your one-stop platform for educational resources.</p>
            <form action="search.php" method="GET" class="d-flex justify-content-center mt-4">
                <input class="form-control me-2 w-50" type="search" name="query" placeholder="Search for files, classes, and more..." aria-label="Search" required>
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
        </div>
    </section>

    <!-- Recent Uploads Section -->
    <section id="recent-uploads" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Recent Uploads</h2>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                <?php
                $stmt = $pdo->query("
                    SELECT u.filename, u.filepath, c.name as class_name 
                    FROM uploads u 
                    LEFT JOIN classes c ON u.class_id = c.id 
                    ORDER BY u.upload_date DESC 
                    LIMIT 8
                ");
                $recent_uploads = $stmt->fetchAll();

                if (empty($recent_uploads)) {
                    echo '<div class="col-12 text-center"><p>No recent uploads to display.</p></div>';
                } else {
                    foreach ($recent_uploads as $upload):
                ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm upload-card">
                                <a href="<?php echo htmlspecialchars($upload['filepath']); ?>" target="_blank" class="text-decoration-none text-dark">
                                    <div class="card-img-top-container">
                                        <?php echo generate_file_preview($upload['filename'], $upload['filepath']); ?>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($upload['filename']); ?></h5>
                                        <p class="card-text text-muted"><?php echo htmlspecialchars($upload['class_name'] ?? 'General'); ?></p>
                                    </div>
                                </a>
                            </div>
                        </div>
                <?php 
                    endforeach;
                }
                ?>
            </div>
        </div>
    </section>
</div>

<?php include_once('includes/footer.php'); ?>


