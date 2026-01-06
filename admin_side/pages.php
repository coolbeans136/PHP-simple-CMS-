<?php
require_once '../config.php';
requireLogin();

$conn = getDBConnection();

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM pages WHERE id = $id");
    header('Location: pages.php');
    exit();
}

// Get all pages
$pages = $conn->query("SELECT * FROM pages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Pages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background-color: #2c3e50; min-height: 100vh; color: white; }
        .sidebar a { color: #ecf0f1; text-decoration: none; padding: 10px 20px; display: block; transition: background 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: #34495e; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 px-0 sidebar">
                <div class="p-3">
                    <h4>CMS Admin</h4>
                    <hr>
                </div>
                <nav>
                    <a href="index.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="pages.php" class="active"><i class="bi bi-file-earmark-text"></i> Pages</a>
                    <a href="posts.php"><i class="bi bi-newspaper"></i> Posts</a>
                    <a href="media.php"><i class="bi bi-images"></i> Media</a>
                    <a href="settings.php"><i class="bi bi-gear"></i> Settings</a>
                    <a href="../public/index.php" target="_blank"><i class="bi bi-globe"></i> View Site</a>
                    <hr>
                    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1>Pages</h1>
                        <a href="edit_page.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add New Page
                        </a>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th>Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($page = $pages->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($page['title']); ?></strong></td>
                                        <td><code><?php echo htmlspecialchars($page['slug']); ?></code></td>
                                        <td>
                                            <span class="badge bg-<?php echo $page['is_published'] ? 'success' : 'secondary'; ?>">
                                                <?php echo $page['is_published'] ? 'Published' : 'Draft'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M j, Y', strtotime($page['updated_at'])); ?></td>
                                        <td>
                                            <a href="edit_page.php?id=<?php echo $page['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="pages.php?delete=<?php echo $page['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
