<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}
include_once('../includes/config.php');

// Generate a CSRF token if one doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle POST requests for both adding and deleting
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for a valid CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token validation failed.');
    }

    // Handle Add Class action
    if (isset($_POST['add_class'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $stmt = $pdo->prepare("INSERT INTO classes (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $description]);
        header('Location: classes.php?status=added');
        exit();
    }

    // Handle Delete Class action
    if (isset($_POST['delete_class'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM classes WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: classes.php?status=deleted');
        exit();
    }
}

include_once('includes/admin_header.php');

// Fetch all classes
$stmt = $pdo->query("SELECT * FROM classes ORDER BY name");
$classes = $stmt->fetchAll();
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manage Classes</h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Existing Classes</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th style="width: 15%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($classes as $class): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($class['name']); ?></td>
                                    <td><?php echo htmlspecialchars($class['description']); ?></td>
                                    <td>
                                        <a href="edit_class.php?id=<?php echo $class['id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="classes.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?php echo $class['id']; ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <button type="submit" name="delete_class" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this class? This action cannot be undone.');">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Add New Class</h6>
                </div>
                <div class="card-body">
                    <form action="classes.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Class Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <button type="submit" name="add_class" class="btn btn-primary">Add Class</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/admin_footer.php'); ?>
