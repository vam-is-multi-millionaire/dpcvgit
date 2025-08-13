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

include_once('includes/admin_header.php');

$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token validation failed.');
    }

    try {
        if (isset($_POST['add_menu_item'])) {
            $name = $_POST['name'];
            $url = $_POST['url'];
            $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
            $sort_order = $_POST['sort_order'];

            $stmt = $pdo->prepare("INSERT INTO menu_items (name, url, parent_id, sort_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $url, $parent_id, $sort_order]);
            $message = "Menu item added successfully!";
        } elseif (isset($_POST['delete_menu_item'])) {
            $id = $_POST['id'];
            // Optional: First, check for and handle child items if necessary
            $stmt_check_children = $pdo->prepare("SELECT id FROM menu_items WHERE parent_id = ?");
            $stmt_check_children->execute([$id]);
            if ($stmt_check_children->rowCount() > 0) {
                $error = "Cannot delete a menu item that has children. Please remove or reassign child items first.";
            } else {
                $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
                $stmt->execute([$id]);
                $message = "Menu item deleted successfully!";
            }
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// Fetch all menu items to build the hierarchy
$stmt = $pdo->query("SELECT * FROM menu_items ORDER BY sort_order, name");
$all_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create a hierarchical structure
$menu_tree = [];
$items_by_id = [];
foreach ($all_items as $item) {
    $items_by_id[$item['id']] = $item;
    $items_by_id[$item['id']]['children'] = [];
}

foreach ($items_by_id as $id => &$item) {
    if ($item['parent_id'] && isset($items_by_id[$item['parent_id']])) {
        $items_by_id[$item['parent_id']]['children'][] = &$item;
    }
}
unset($item); // Unset reference

foreach ($items_by_id as $id => $item) {
    if (!$item['parent_id']) {
        $menu_tree[] = $item;
    }
}

// Function to render the menu tree, now accepting the CSRF token
function render_menu_tree($items, $csrf_token, $level = 0) {
    if (empty($items)) {
        return;
    }

    echo '<ul class="list-group' . ($level > 0 ? ' mt-2' : '') . '">';
    foreach ($items as $item) {
        echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
        echo '<div>';
        echo '<span class="fw-bold">' . htmlspecialchars($item['name']) . '</span>';
        echo '<small class="text-muted ms-2">(' . htmlspecialchars($item['url']) . ')</small>';
        echo '<span class="badge bg-secondary ms-2">Order: ' . $item['sort_order'] . '</span>';
        echo '</div>';
        echo '<div class="actions">';
        echo '<a href="edit_menu.php?id=' . $item['id'] . '" class="btn btn-primary btn-sm me-2" title="Edit"><i class="fas fa-edit"></i></a>';
        echo '<form method="POST" onsubmit="return confirm(\'Are you sure you want to delete this item?\');" class="d-inline">';
        echo '<input type="hidden" name="id" value="' . $item['id'] . '">';
        echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrf_token) . '">';
        echo '<button type="submit" name="delete_menu_item" class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>';
        echo '</form>';
        echo '</div>';
        echo '</li>';

        if (!empty($item['children'])) {
            echo '<li class="list-group-item" style="padding-left: ' . ($level + 2) . 'rem;">';
            render_menu_tree($item['children'], $csrf_token, $level + 1);
            echo '</li>';
        }
    }
    echo '</ul>';
}
?>

<div class="container-fluid py-4">
    <h1 class="mb-4">Manage Menu</h1>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success">' . $message . '</div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">' . $error . '</div>
    <?php endif; ?>

    <div class="row">
        <!-- Add Menu Item Form -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Add New Menu Item</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="e.g., Home" required>
                        </div>
                        <div class="mb-3">
                            <label for="url" class="form-label">URL</label>
                            <input type="text" class="form-control" id="url" name="url" placeholder="e.g., index.php" required>
                        </div>
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Parent Item</label>
                            <select name="parent_id" id="parent_id" class="form-select">
                                <option value="">-- No Parent --</option>
                                <?php foreach ($all_items as $item): ?>
                                    <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" value="0">
                        </div>
                        <button type="submit" name="add_menu_item" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-2"></i>Add Menu Item
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Existing Menu Items -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Menu Structure</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($menu_tree)): ?>
                        <div class="text-center text-muted">No menu items yet. Add one using the form.</div>
                    <?php else: ?>
                        <?php render_menu_tree($menu_tree, $_SESSION['csrf_token']); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/admin_footer.php'); ?>