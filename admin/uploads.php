<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}
include_once('../includes/config.php');
include_once('../includes/helpers.php');

// Generate a CSRF token if one doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include_once('includes/admin_header.php');

$message = '';
$error = '';

// --- Security Enhancements ---
// 1. Whitelist of allowed file types (extensions and MIME types)
$allowed_extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif'];
$allowed_mime_types = [
    'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'image/jpeg', 'image/png', 'image/gif'
];

// Handle file uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_file'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = 'CSRF token validation failed.';
    } else {
        $class_id = !empty($_POST['class_id']) ? $_POST['class_id'] : null;
        $file = $_FILES['file'];

        if ($file['error'] === UPLOAD_ERR_OK) {
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $file_mime_type = mime_content_type($file['tmp_name']);

            // 2. Validate file type against the whitelist
            if (!in_array($file_extension, $allowed_extensions) || !in_array($file_mime_type, $allowed_mime_types)) {
                $error = "Invalid file type. Allowed types: " . implode(', ', $allowed_extensions);
            } else {
                // 3. Determine the display name and generate a unique filename
                $display_name = !empty(trim($_POST['display_name'])) ? trim($_POST['display_name']) : basename($file['name']);
                $unique_filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $file_extension;
                $db_filepath = 'uploads/' . $unique_filename;
                $disk_filepath = '../' . $db_filepath;

                if (!is_dir('../uploads')) {
                    mkdir('../uploads', 0755, true);
                }

                if (move_uploaded_file($file['tmp_name'], $disk_filepath)) {
                    try {
                        // Store the display name and the new unique path
                        $stmt = $pdo->prepare("INSERT INTO uploads (filename, filepath, class_id) VALUES (?, ?, ?)");
                        $stmt->execute([$display_name, $db_filepath, $class_id]);
                        $message = "File uploaded successfully!";
                    } catch (PDOException $e) {
                        $error = "Database error: " . $e->getMessage();
                        unlink($disk_filepath); // Clean up on DB error
                    }
                } else {
                    $error = "Failed to move uploaded file.";
                }
            }
        } else {
            $error = "File upload error code: " . $file['error'];
        }
    }
}

// Handle file deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_upload'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = 'CSRF token validation failed.';
    } else {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("SELECT filepath FROM uploads WHERE id = ?");
        $stmt->execute([$id]);
        $upload = $stmt->fetch();

        if ($upload) {
            $disk_filepath = '../' . $upload['filepath'];
            if (file_exists($disk_filepath)) {
                unlink($disk_filepath);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM uploads WHERE id = ?");
        $stmt->execute([$id]);
        $message = "File deleted successfully.";
    }
}

// Fetch data for display
$uploads = $pdo->query("SELECT u.id, u.filename, u.filepath, u.upload_date, c.name as class_name FROM uploads u LEFT JOIN classes c ON u.class_id = c.id ORDER BY u.upload_date DESC")->fetchAll();
$classes = $pdo->query("SELECT * FROM classes ORDER BY name")->fetchAll();
?>

<div class="container-fluid py-4">
    <h1 class="mb-4">Manage Uploads</h1>

    <?php if (!empty($message)): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

    <div class="row">
        <!-- Upload Form -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Upload New File</h5></div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="mb-3">
                            <label for="file" class="form-label">Select File</label>
                            <input type="file" class="form-control" name="file" id="file" required>
                            <div class="form-text">Allowed: PDF, DOCX, PPTX, XLSX, JPG, PNG</div>
                        </div>
                        <div class="mb-3">
                            <label for="display_name" class="form-label">Display Name (Optional)</label>
                            <input type="text" class="form-control" name="display_name" id="display_name" placeholder="e.g., 'Chapter 1 Notes'">
                            <div class="form-text">If left blank, the original filename will be used.</div>
                        </div>
                        <div class="mb-3">
                            <label for="class_id" class="form-label">Assign to Class (Optional)</label>
                            <select name="class_id" id="class_id" class="form-select">
                                <option value="">-- Select a Class --</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" name="upload_file" class="btn btn-primary w-100"><i class="fas fa-upload me-2"></i>Upload File</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Existing Uploads -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Existing Uploads</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr><th>File</th><th>Class</th><th>Uploaded On</th><th class="text-end">Actions</th></tr>
                            </thead>
                            <tbody>
                                <?php if (empty($uploads)): ?>
                                    <tr><td colspan="4" class="text-center text-muted">No files uploaded yet.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($uploads as $upload): ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo BASE_URL . htmlspecialchars($upload['filepath']); ?>" target="_blank" class="text-decoration-none d-flex align-items-center">
                                                <img src="<?php echo get_file_icon($upload['filepath']); ?>" alt="icon" class="me-2" style="width: 24px; height: 24px;">
                                                <span class="fw-bold" title="<?php echo htmlspecialchars($upload['filename']); ?>">
                                                    <?php echo htmlspecialchars(mb_strimwidth($upload['filename'], 0, 30, "...")); ?>
                                                </span>
                                            </a>
                                        </td>
                                        <td><?php echo htmlspecialchars($upload['class_name'] ?? 'N/A'); ?></td>
                                        <td><?php echo date("M d, Y", strtotime($upload['upload_date'])); ?></td>
                                        <td class="text-end">
                                            <form method="POST" onsubmit="return confirm('Are you sure?');" class="d-inline">
                                                <input type="hidden" name="id" value="<?php echo $upload['id']; ?>">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <button type="submit" name="delete_upload" class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/admin_footer.php'); ?>