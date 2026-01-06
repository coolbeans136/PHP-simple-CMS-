<?php
require_once '../config.php';

$conn = getDBConnection();

// Get site settings
$settings = [];
$result = $conn->query("SELECT setting_key, setting_value FROM settings");
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Get post by slug
$slug = isset($_GET['slug']) ? $conn->real_escape_string($_GET['slug']) : '';
$post = $conn->query("SELECT p.*, a.username as author FROM posts p LEFT JOIN admins a ON p.author_id = a.id WHERE p.slug = '$slug' AND p.is_published = 1")->fetch_assoc();

if (!$post) {
    header('Location: blog.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - <?php echo htmlspecialchars($settings['site_name'] ?? 'My Site'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; }
        .navbar { background-color: #2c3e50; }
        .article-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 60px 0; margin-bottom: 40px; }
        .article-content { font-size: 1.1rem; line-height: 1.8; }
        footer { background-color: #2c3e50; color: white; padding: 30px 0; margin-top: 60px; }
        
        /* Rich media styling */
        .article-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 20px 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .article-content video { max-width: 100%; height: auto; border-radius: 8px; margin: 20px 0; }
        .article-content iframe { max-width: 100%; border-radius: 8px; margin: 20px 0; }
        .article-content table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .article-content table th, .article-content table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        .article-content table th { background-color: #f8f9fa; font-weight: 600; }
        .article-content blockquote { border-left: 4px solid #667eea; padding-left: 20px; margin: 20px 0; font-style: italic; color: #555; }
        .article-content pre { background-color: #f5f5f5; padding: 15px; border-radius: 4px; overflow-x: auto; }
        .article-content code { background-color: #f5f5f5; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
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
                    <li class="nav-item"><a class="nav-link" href="blog.php">Blog</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="article-header">
        <div class="container">
            <h1><?php echo htmlspecialchars($post['title']); ?></h1>
            <p class="lead">By <?php echo htmlspecialchars($post['author']); ?> on <?php echo date('F j, Y', strtotime($post['created_at'])); ?></p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <?php if (!empty($post['featured_image'])): ?>
                    <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="img-fluid mb-4" style="width: 100%; max-height: 500px; object-fit: cover; border-radius: 12px;">
                <?php endif; ?>
                <div class="article-content">
                    <?php echo $post['content']; ?>
                </div>
                <hr class="my-5">
                <a href="blog.php" class="btn btn-secondary">&larr; Back to Blog</a>
            </div>
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
