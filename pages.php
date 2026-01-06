<?php
require_once '../config.php';

$conn = getDBConnection();

// Get site settings
$settings = [];
$result = $conn->query("SELECT setting_key, setting_value FROM settings");
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Get page by slug or show all pages
if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];
    $stmt = $conn->prepare("SELECT * FROM pages WHERE slug = ? AND is_published = 1");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $page = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$page) {
        header('Location: pages.php');
        exit();
    }
    $viewMode = 'single';
} else {
    $pages = $conn->query("SELECT * FROM pages WHERE is_published = 1 ORDER BY title");
    $viewMode = 'list';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $viewMode === 'single' ? htmlspecialchars($page['title']) : 'Pages'; ?> - <?php echo htmlspecialchars($settings['site_name'] ?? 'My Site'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; }
        .navbar { background-color: #2c3e50; }
        .page-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 0; margin-bottom: 40px; }
        .card { transition: transform 0.2s; margin-bottom: 20px; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        footer { background-color: #2c3e50; color: white; padding: 30px 0; margin-top: 60px; }
        
        /* Rich media styling */
        .page-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 20px 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .page-content video { max-width: 100%; height: auto; border-radius: 8px; margin: 20px 0; }
        .page-content iframe { max-width: 100%; border-radius: 8px; margin: 20px 0; }
        .page-content table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .page-content table th, .page-content table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        .page-content table th { background-color: #f8f9fa; font-weight: 600; }
        .page-content blockquote { border-left: 4px solid #667eea; padding-left: 20px; margin: 20px 0; font-style: italic; color: #555; }
        .page-content pre { background-color: #f5f5f5; padding: 15px; border-radius: 4px; overflow-x: auto; }
        .page-content code { background-color: #f5f5f5; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
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
                    <li class="nav-item"><a class="nav-link active" href="pages.php">Pages</a></li>
                    <li class="nav-item"><a class="nav-link" href="blog.php">Blog</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="page-header">
        <div class="container">
            <h1><?php echo $viewMode === 'single' ? htmlspecialchars($page['title']) : 'All Pages'; ?></h1>
        </div>
    </div>

    <div class="container">
        <?php if ($viewMode === 'single'): ?>
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <?php if (!empty($page['featured_image'])): ?>
                        <img src="<?php echo htmlspecialchars($page['featured_image']); ?>" alt="<?php echo htmlspecialchars($page['title']); ?>" class="img-fluid mb-4" style="width: 100%; max-height: 500px; object-fit: cover; border-radius: 12px;">
                    <?php endif; ?>
                    <div class="page-content">
                        <?php echo $page['content']; ?>
                    </div>
                    <hr class="my-5">
                    <a href="pages.php" class="btn btn-secondary">&larr; Back to Pages</a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <?php while ($p = $pages->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if (!empty($p['featured_image'])): ?>
                            <img src="<?php echo htmlspecialchars($p['featured_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($p['title']); ?>" style="height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($p['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($p['meta_description']); ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="pages.php?slug=<?php echo urlencode($p['slug']); ?>" class="btn btn-primary">View Page</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
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
