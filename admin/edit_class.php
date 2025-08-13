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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token validation failed.');
    }

    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $stmt = $pdo->prepare("UPDATE classes SET name = ?, description = ? WHERE id = ?");
    $stmt->execute([$name, $description, $id]);
    header('Location: classes.php?status=updated');
    exit();
}

include_once('includes/admin_header.php');

if (!isset($_GET['id'])) {
    header('Location: classes.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM classes WHERE id = ?");
$stmt->execute([$id]);
$class = $stmt->fetch();

if (!$class) {
    header('Location: classes.php');
    exit();
}
?>

<div class="management-page">
    <h1>Edit Class</h1>

    <div class="edit-form">
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $class['id']; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="input-group">
                <label for="name">Class Name</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($class['name']); ?>" required>
            </div>
            <div class="input-group">
                <label for="description">Class Description</label>
                <textarea name="description" id="description"><?php echo htmlspecialchars($class['description']); ?></textarea>
            </div>
            <button type="submit" name="update_class">Update Class</button>
        </form>
    </div>
</div>

<?php include_once('includes/admin_footer.php'); ?>