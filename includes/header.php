<?php include_once('config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="light-mode">
    <header class="header">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
                <a class="navbar-brand" href="index.php"><?php echo SITE_NAME; ?></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="main-nav">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <?php
                        $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE parent_id IS NULL ORDER BY sort_order");
                        $stmt->execute();
                        $menu_items = $stmt->fetchAll();

                        foreach ($menu_items as $item) {
                            if (strtolower($item['name']) === 'class') {
                                echo '<li class="nav-item dropdown">';
                                echo '<a class="nav-link dropdown-toggle" href="#" id="class-dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">' . htmlspecialchars($item['name']) . '</a>';
                                
                                $class_stmt = $pdo->query("SELECT * FROM classes");
                                $classes = $class_stmt->fetchAll();
                                
                                if ($classes) {
                                    // Sort classes naturally by name
                                    usort($classes, function($a, $b) {
                                        return strnatcmp($a['name'], $b['name']);
                                    });

                                    echo '<ul class="dropdown-menu" aria-labelledby="class-dropdown">';
                                    foreach ($classes as $class) {
                                        echo '<li><a class="dropdown-item" href="class.php?id=' . $class['id'] . '">' . htmlspecialchars($class['name']) . '</a></li>';
                                    }
                                    echo '</ul>';
                                }
                                echo '</li>';
                            } else {
                                echo '<li class="nav-item"><a class="nav-link" href="' . htmlspecialchars($item['url']) . '">' . htmlspecialchars($item['name']) . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                    <div class="d-flex align-items-center">
                        <form action="search.php" method="GET" class="d-flex me-2">
                            <input class="form-control me-2" type="search" name="query" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit"><i class="fas fa-search"></i></button>
                        </form>
                        <button id="theme-toggle" class="btn btn-link text-decoration-none">
                            <span class="light-icon">☀️</span>
                            <span class="dark-icon">🌙</span>
                        </button>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <main>
