<?php
require_once '../config.php';
requireLogin();

$conn = getDBConnection();

// Get all media files
$media_files = $conn->query("SELECT id, filename, original_filename, file_path, file_type, file_size FROM media ORDER BY created_at DESC");

$media = [];
while ($row = $media_files->fetch_assoc()) {
    $media[] = $row;
}

header('Content-Type: application/json');
echo json_encode($media);

$conn->close();
?>
