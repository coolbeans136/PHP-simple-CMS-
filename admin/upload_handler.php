<?php
require_once '../config.php';
requireLogin();

$conn = getDBConnection();

// Handle TinyMCE image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $upload_dir = '../uploads/';
    
    // Create uploads directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file = $_FILES['file'];
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Upload error occurred']);
        exit;
    }
    
    // Check file size (max 5MB for inline images)
    $max_size = 5 * 1024 * 1024;
    if ($file['size'] > $max_size) {
        header('HTTP/1.1 413 Payload Too Large');
        echo json_encode(['error' => 'File too large. Maximum 5MB']);
        exit;
    }
    
    $original_filename = basename($file['name']);
    $file_extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
    
    // Allowed image types for inline uploads
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    
    if (in_array($file_extension, $allowed_types)) {
        // Generate unique filename
        $filename = uniqid() . '_' . time() . '.' . $file_extension;
        $file_path = $upload_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Save to database
            $stmt = $conn->prepare("INSERT INTO media (filename, original_filename, file_path, file_type, file_size, mime_type, uploaded_by) VALUES (?, ?, ?, 'image', ?, ?, ?)");
            $uploaded_by = $_SESSION['admin_id'];
            $stmt->bind_param("sssisi", $filename, $original_filename, $file_path, $file['size'], $file['type'], $uploaded_by);
            $stmt->execute();
            $stmt->close();
            
            // Return JSON response for TinyMCE
            header('Content-Type: application/json');
            
            // Convert to absolute URL
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $location = str_replace('../', '', $file_path);
            
            echo json_encode(['location' => "$protocol://$host/$location"]);
            exit;
        }
    }
    
    // Error response
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Upload failed']);
    exit;
}

$conn->close();
?>
