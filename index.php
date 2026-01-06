<?php
require_once '../config.php';
requireLogin();

$conn = getDBConnection();

// Get statistics
$total_pages = $conn->query("SELECT COUNT(*) as count FROM pages")->fetch_assoc()['count'];
$total_posts = $conn->query("SELECT COUNT(*) as count FROM posts")->fetch_assoc()['count'];
$published_pages = $conn->query("SELECT COUNT(*) as count FROM pages WHERE is_published = 1")->fetch_assoc()['count'];
$published_posts = $conn->query("SELECT COUNT(*) as count FROM posts WHERE is_published = 1")->fetch_assoc()['count'];

// Get recent posts
$recent_posts = $conn->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background-color: #2c3e50; min-height: 100vh; color: white; }
        .sidebar a { color: #ecf0f1; text-decoration: none; padding: 10px 20px; display: block; transition: background 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: #34495e; }
        .stat-card { border-left: 4px solid #667eea; }
        .stat-card.success { border-left-color: #2ecc71; }
        .stat-card.info { border-left-color: #3498db; }
        .stat-card.warning { border-left-color: #f39c12; }
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
                    <a href="index.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="pages.php"><i class="bi bi-file-earmark-text"></i> Pages</a>
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
                    <h1 class="mb-4">Dashboard</h1>
                    <p class="text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <h6 class="text-muted">Total Pages</h6>
                                    <h2><?php echo $total_pages; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card success">
                                <div class="card-body">
                                    <h6 class="text-muted">Published Pages</h6>
                                    <h2><?php echo $published_pages; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card info">
                                <div class="card-body">
                                    <h6 class="text-muted">Total Posts</h6>
                                    <h2><?php echo $total_posts; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card warning">
                                <div class="card-body">
                                    <h6 class="text-muted">Published Posts</h6>
                                    <h2><?php echo $published_posts; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Posts -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Recent Posts</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($post = $recent_posts->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $post['is_published'] ? 'success' : 'secondary'; ?>">
                                                <?php echo $post['is_published'] ? 'Published' : 'Draft'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                                        <td>
                                            <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
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
