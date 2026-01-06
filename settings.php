<?php
require_once '../config.php';
requireLogin();

$conn = getDBConnection();
$success = '';
$error = '';

// Get current settings
$settings = [];
$result = $conn->query("SELECT setting_key, setting_value FROM settings");
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = sanitize($_POST['site_name']);
    $site_description = sanitize($_POST['site_description']);
    $site_email = sanitize($_POST['site_email']);
    
    $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
    
    $key = 'site_name';
    $stmt->bind_param("ss", $site_name, $key);
    $stmt->execute();
    
    $key = 'site_description';
    $stmt->bind_param("ss", $site_description, $key);
    $stmt->execute();
    
    $key = 'site_email';
    $stmt->bind_param("ss", $site_email, $key);
    $stmt->execute();
    
    $stmt->close();
    $success = 'Settings saved successfully!';
    
    // Reload settings
    $settings = [
        'site_name' => $site_name,
        'site_description' => $site_description,
        'site_email' => $site_email
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
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
                    <a href="posts.php"><i class="bi bi-newspaper"></i> Posts</a>
                    <a href="media.php"><i class="bi bi-images"></i> Media</a>
                    <a href="settings.php" class="active"><i class="bi bi-gear"></i> Settings</a>
                    <a href="../public/index.php" target="_blank"><i class="bi bi-globe"></i> View Site</a>
                    <hr>
                    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <div class="p-4">
                    <h1 class="mb-4">Site Settings</h1>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="site_name" class="form-label">Site Name</label>
                                    <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="site_description" class="form-label">Site Description</label>
                                    <textarea class="form-control" id="site_description" name="site_description" rows="3"><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="site_email" class="form-label">Site Email</label>
                                    <input type="email" class="form-control" id="site_email" name="site_email" value="<?php echo htmlspecialchars($settings['site_email'] ?? ''); ?>" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Save Settings</button>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Database Information</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Default Admin Login:</strong></p>
                            <ul>
                                <li>Username: <code>admin</code></li>
                                <li>Password: <code>admin123</code></li>
                            </ul>
                            <p class="text-danger"><small><i class="bi bi-exclamation-triangle"></i> Please change the default password in production!</small></p>
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
