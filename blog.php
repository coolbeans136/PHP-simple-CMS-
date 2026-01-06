<?php
require_once '../config.php';

$conn = getDBConnection();

// Get site settings
$settings = [];
$result = $conn->query("SELECT setting_key, setting_value FROM settings");
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Get all published posts
$posts = $conn->query("SELECT p.*, a.username as author FROM posts p LEFT JOIN admins a ON p.author_id = a.id WHERE p.is_published = 1 ORDER BY p.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - <?php echo htmlspecialchars($settings['site_name'] ?? 'My Site'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; }
        .navbar { background-color: #2c3e50; }
        .page-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 0; margin-bottom: 40px; }
        .card { transition: transform 0.2s; margin-bottom: 20px; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        footer { background-color: #2c3e50; color: white; padding: 30px 0; margin-top: 60px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php"><?php echo htmlspecialchars($settings['site_name'] ?? 'My Site'); ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="pages.php">Pages</a></li>
                    <li class="nav-item"><a class="nav-link active" href="blog.php">Blog</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="page-header">
        <div class="container">
            <h1>Blog</h1>
            <p>Latest articles and updates</p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <?php while ($post = $posts->fetch_assoc()): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <?php if (!empty($post['featured_image'])): ?>
                        <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($post['title']); ?>" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                        <p class="text-muted small">By <?php echo htmlspecialchars($post['author']); ?> on <?php echo date('M j, Y', strtotime($post['created_at'])); ?></p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="post.php?slug=<?php echo urlencode($post['slug']); ?>" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer>
        <div class="container text-center">
            <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($settings['site_name'] ?? 'My Site'); ?>. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
