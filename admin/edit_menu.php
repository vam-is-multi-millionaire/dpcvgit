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
    $url = $_POST['url'];
    $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
    $sort_order = $_POST['sort_order'];

    $stmt = $pdo->prepare("UPDATE menu_items SET name = ?, url = ?, parent_id = ?, sort_order = ? WHERE id = ?");
    $stmt->execute([$name, $url, $parent_id, $sort_order, $id]);
    header('Location: menu.php?status=updated');
    exit();
}

include_once('includes/admin_header.php');

if (!isset($_GET['id'])) {
    header('Location: menu.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
$stmt->execute([$id]);
$menu_item = $stmt->fetch();

if (!$menu_item) {
    header('Location: menu.php');
    exit();
}

// Fetch all menu items for the parent dropdown, excluding the current item and its children
$stmt_all = $pdo->prepare("SELECT * FROM menu_items WHERE id != ? ORDER BY sort_order");
$stmt_all->execute([$id]);
$all_menu_items = $stmt_all->fetchAll();
?>

<div class="management-page">
    <h1>Edit Menu Item</h1>

    <div class="edit-form">
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $menu_item['id']; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="input-group">
                <label for="name">Item Name</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($menu_item['name']); ?>" required>
            </div>
            <div class="input-group">
                <label for="url">URL</label>
                <input type="text" name="url" id="url" value="<?php echo htmlspecialchars($menu_item['url']); ?>" required>
            </div>
            <div class="input-group">
                <label for="parent_id">Parent Item</label>
                <select name="parent_id" id="parent_id">
                    <option value="">-- No Parent --</option>
                    <?php foreach ($all_menu_items as $item): ?>
                        <option value="<?php echo $item['id']; ?>" <?php echo ($menu_item['parent_id'] == $item['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" value="<?php echo $menu_item['sort_order']; ?>">
            </div>
            <button type="submit" name="update_menu_item">Update Menu Item</button>
        </form>
    </div>
</div>

<?php include_once('includes/admin_footer.php'); ?>