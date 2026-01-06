<?php
require_once '../config.php';
requireLogin();

$conn = getDBConnection();
$success = '';
$error = '';

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media_file'])) {
    $upload_dir = '../uploads/';
    
    // Create uploads directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file = $_FILES['media_file'];
    $original_filename = basename($file['name']);
    $file_extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
    
    // Check file size (max 10MB)
    $max_size = 10 * 1024 * 1024; // 10MB in bytes
    if ($file['size'] > $max_size) {
        $error = 'File too large. Maximum size is 10MB.';
    } else if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Upload error occurred.';
    } else {
    
    // Allowed file types
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'mp4', 'webm', 'ogg', 'pdf'];
    
    if (in_array($file_extension, $allowed_types)) {
        // Generate unique filename
        $filename = uniqid() . '_' . time() . '.' . $file_extension;
        $file_path = $upload_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            $stmt = $conn->prepare("INSERT INTO media (filename, original_filename, file_path, file_type, file_size, mime_type, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $file_type = strpos($file['type'], 'image') !== false ? 'image' : (strpos($file['type'], 'video') !== false ? 'video' : 'document');
            $uploaded_by = $_SESSION['admin_id'];
            $stmt->bind_param("sssssii", $filename, $original_filename, $file_path, $file_type, $file['size'], $file['type'], $uploaded_by);
            
            if ($stmt->execute()) {
                $success = 'File uploaded successfully!';
            } else {
                $error = 'Error saving file info to database.';
            }
            $stmt->close();
        } else {
            $error = 'Error uploading file.';
        }
    } else {
        $error = 'Invalid file type. Allowed: ' . implode(', ', $allowed_types);
    }
    }
}

// Handle file deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("SELECT * FROM media WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $media = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($media && file_exists($media['file_path'])) {
        unlink($media['file_path']);
        $stmt = $conn->prepare("DELETE FROM media WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $success = 'File deleted successfully!';
    }
}

// Get all media files
$media_files = $conn->query("SELECT m.*, a.username FROM media m LEFT JOIN admins a ON m.uploaded_by = a.id ORDER BY m.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background-color: #2c3e50; min-height: 100vh; color: white; }
        .sidebar a { color: #ecf0f1; text-decoration: none; padding: 10px 20px; display: block; transition: background 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: #34495e; }
        .media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
        .media-item { border: 1px solid #ddd; border-radius: 8px; padding: 10px; background: white; text-align: center; position: relative; }
        .media-item img { max-width: 100%; height: 150px; object-fit: cover; border-radius: 4px; }
        .media-item video { max-width: 100%; height: 150px; border-radius: 4px; }
        .media-item .icon { font-size: 100px; color: #6c757d; }
        .copy-btn { cursor: pointer; }
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
                    <a href="media.php" class="active"><i class="bi bi-images"></i> Media</a>
                    <a href="settings.php"><i class="bi bi-gear"></i> Settings</a>
                    <a href="../public/index.php" target="_blank"><i class="bi bi-globe"></i> View Site</a>
                    <hr>
                    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <div class="p-4">
                    <h1 class="mb-4">Media Library</h1>

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show"><?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show"><?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Upload Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Upload Media</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <input type="file" class="form-control" name="media_file" required accept="image/*,video/*,.pdf">
                                    <small class="text-muted">Supported: Images (JPG, PNG, GIF, WebP, SVG), Videos (MP4, WebM, OGG), PDF</small>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Upload</button>
                            </form>
                        </div>
                    </div>

                    <!-- Media Grid -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">All Media Files</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($media_files->num_rows > 0): ?>
                                <div class="media-grid">
                                    <?php while ($media = $media_files->fetch_assoc()): ?>
                                        <div class="media-item">
                                            <?php if ($media['file_type'] === 'image'): ?>
                                                <img src="<?php echo htmlspecialchars($media['file_path']); ?>" alt="<?php echo htmlspecialchars($media['original_filename']); ?>">
                                            <?php elseif ($media['file_type'] === 'video'): ?>
                                                <video controls>
                                                    <source src="<?php echo htmlspecialchars($media['file_path']); ?>" type="<?php echo htmlspecialchars($media['mime_type']); ?>">
                                                </video>
                                            <?php else: ?>
                                                <i class="bi bi-file-earmark icon"></i>
                                            <?php endif; ?>
                                            
                                            <div class="mt-2">
                                                <small class="d-block text-truncate" title="<?php echo htmlspecialchars($media['original_filename']); ?>">
                                                    <?php echo htmlspecialchars($media['original_filename']); ?>
                                                </small>
                                                <small class="text-muted d-block">
                                                    <?php echo round($media['file_size'] / 1024, 2); ?> KB
                                                </small>
                                            </div>
                                            
                                            <div class="btn-group btn-group-sm mt-2" role="group">
                                                <button class="btn btn-outline-primary copy-btn" onclick="copyUrl('<?php echo htmlspecialchars($media['file_path']); ?>')" title="Copy URL">
                                                    <i class="bi bi-clipboard"></i>
                                                </button>
                                                <a href="?delete=<?php echo $media['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Delete this file?')" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No media files uploaded yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyUrl(url) {
            // Convert relative path to absolute URL
            const fullUrl = window.location.origin + '/' + url.replace('../', '');
            navigator.clipboard.writeText(fullUrl).then(() => {
                alert('URL copied to clipboard: ' + fullUrl);
            });
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
