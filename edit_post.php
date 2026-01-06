<?php
require_once '../config.php';
requireLogin();

$conn = getDBConnection();
$success = '';
$error = '';
$post = ['title' => '', 'slug' => '', 'content' => '', 'excerpt' => '', 'is_published' => 1];

// Edit existing post
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();
    
    if (!$post) {
        header('Location: posts.php');
        exit();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCsrfToken()) {
        $error = 'Invalid security token. Please try again.';
    } else {
    $title = sanitize($_POST['title']);
    $slug = sanitize($_POST['slug']);
    $content = $_POST['content']; // Don't sanitize HTML content - allow HTML
    $excerpt = sanitize($_POST['excerpt']);
    $featured_image = sanitize($_POST['featured_image']);
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    $author_id = $_SESSION['admin_id'];
    
    // Handle PDF upload
    if (isset($_FILES['pdf_upload']) && $_FILES['pdf_upload']['error'] === UPLOAD_ERR_OK) {
        try {
            require_once 'pdf_processor.php';
            $processor = new PDFProcessor();
            
            // Upload and extract content
            $pdfPath = $processor->uploadPDF($_FILES['pdf_upload']);
            $extractedContent = $processor->extractToHTML($pdfPath);
            
            // Override content with PDF content
            $content = $extractedContent;
            
            // If title is empty, try to extract from first heading
            if (empty($title) && preg_match('/<h[1-3]>(.+?)<\/h[1-3]>/i', $content, $matches)) {
                $title = strip_tags($matches[1]);
                if (empty($slug)) {
                    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
                }
            }
            
            // Auto-generate excerpt from first paragraph if empty
            if (empty($excerpt) && preg_match('/<p>(.+?)<\/p>/i', $content, $matches)) {
                $excerpt = substr(strip_tags($matches[1]), 0, 200) . '...';
            }
            
            $success = 'PDF uploaded and content extracted successfully!';
        } catch (Exception $e) {
            $error = 'PDF processing error: ' . $e->getMessage();
        }
    }
    
    if (isset($_POST['id']) && $_POST['id']) {
        // Update
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("UPDATE posts SET title=?, slug=?, content=?, excerpt=?, featured_image=?, is_published=? WHERE id=?");
        $stmt->bind_param("sssssii", $title, $slug, $content, $excerpt, $featured_image, $is_published, $id);
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO posts (title, slug, content, excerpt, featured_image, author_id, is_published) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiii", $title, $slug, $content, $excerpt, $featured_image, $author_id, $is_published);
    }
    
    if ($stmt->execute()) {
        $success = 'Post saved successfully!';
        if (!isset($_POST['id'])) {
            header('Location: posts.php');
            exit();
        }
    } else {
        $error = 'Error saving post: ' . $stmt->error;
    }
    $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($_GET['id']) ? 'Edit' : 'Add'; ?> Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="media-picker.css">
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
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
                    <h1 class="mb-4"><?php echo isset($_GET['id']) ? 'Edit' : 'Add New'; ?> Post</h1>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-body">
                            <form method="POST">
                                <?php echo csrfField(); ?>
                                <?php if (isset($_GET['id'])): ?>
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug (URL)</label>
                                    <input type="text" class="form-control" id="slug" name="slug" value="<?php echo htmlspecialchars($post['slug']); ?>" required>
                                    <small class="text-muted">Use lowercase letters, numbers, and hyphens only</small>
                                </div>

                                <div class="mb-3">
                                    <label for="excerpt" class="form-label">Excerpt</label>
                                    <textarea class="form-control" id="excerpt" name="excerpt" rows="3"><?php echo htmlspecialchars($post['excerpt']); ?></textarea>
                                    <small class="text-muted">Short summary of the post</small>
                                </div>

                                <div class="mb-3">
                                    <label for="featured_image" class="form-label">Featured Image</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="featured_image" name="featured_image" value="<?php echo htmlspecialchars($post['featured_image'] ?? ''); ?>" placeholder="/uploads/image.jpg" readonly>
                                        <button type="button" class="btn btn-primary" onclick="openMediaPicker((path) => { document.getElementById('featured_image').value = path; updateImagePreview('featured_image', 'imagePreview'); })">
                                            <i class="bi bi-image"></i> Browse
                                        </button>
                                    </div>
                                    <small class="text-muted">Click Browse to select from media library</small>
                                    <div id="imagePreview" class="mt-2">
                                        <?php if (!empty($post['featured_image'])): ?>
                                            <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="Featured Image Preview" style="max-width: 200px; border-radius: 8px;">
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="content" class="form-label">Content</label>
                                    <textarea class="form-control" id="content" name="content" rows="15"><?php echo htmlspecialchars($post['content']); ?></textarea>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" <?php echo $post['is_published'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_published">Published</label>
                                </div>

                                <button type="submit" class="btn btn-primary">Save Post</button>
                                <a href="posts.php" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="media-picker.js"></script>
    <script>
        function updateImagePreview(inputId, previewId) {
            const path = document.getElementById(inputId).value;
            const preview = document.getElementById(previewId);
            if (path) {
                preview.innerHTML = `<img src="${path}" alt="Preview" style="max-width: 200px; border-radius: 8px;">`;
            } else {
                preview.innerHTML = '';
            }
        }
        
        tinymce.init({
            selector: '#content',
            height: 500,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic forecolor backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | image media link | code | help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; font-size: 14px }',
            image_advtab: true,
            file_picker_types: 'image media',
            file_picker_callback: function(callback, value, meta) {
                if (meta.filetype === 'image') {
                    openMediaPicker((path) => {
                        callback(path, { alt: '' });
                    });
                }
            },
            images_upload_url: 'upload_handler.php',
            automatic_uploads: true,
            images_reuse_filename: true,
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            media_live_embeds: true,
            promotion: false
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>
