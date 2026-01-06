<?php
require_once '../config.php';
requireLogin();

$conn = getDBConnection();

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM posts WHERE id = $id");
    header('Location: posts.php');
    exit();
}

// Get all posts
$posts = $conn->query("SELECT p.*, a.username as author FROM posts p LEFT JOIN admins a ON p.author_id = a.id ORDER BY p.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
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
                    <a href="pages.php"><i class="bi bi-file-earmark-text"></i> Pages</a>
                    <a href="posts.php" class="active"><i class="bi bi-newspaper"></i> Posts</a>
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
                        <h1>Posts</h1>
                        <a href="edit_post.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add New Post
                        </a>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($post = $posts->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($post['title']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($post['author']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $post['is_published'] ? 'success' : 'secondary'; ?>">
                                                <?php echo $post['is_published'] ? 'Published' : 'Draft'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                                        <td>
                                            <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="posts.php?delete=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
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
