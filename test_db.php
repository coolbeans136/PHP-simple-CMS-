<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing Database Connection</h2>";

// Test basic connection
$host = 'localhost';
$user = 'root';
$pass = 'root123';
$db = 'cms_db';

echo "<p>Attempting to connect to MySQL...</p>";
echo "<p>Host: $host</p>";
echo "<p>User: $user</p>";
echo "<p>Database: $db</p>";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("<p style='color: red;'>Connection failed: " . $conn->connect_error . "</p>");
}

echo "<p style='color: green;'>✓ Connected successfully!</p>";

// Test querying
$result = $conn->query("SELECT * FROM admins");
if ($result) {
    echo "<p style='color: green;'>✓ Admins table found with " . $result->num_rows . " users</p>";
    while ($row = $result->fetch_assoc()) {
        echo "<p>User: " . htmlspecialchars($row['username']) . "</p>";
    }
} else {
    echo "<p style='color: red;'>Error querying admins: " . $conn->error . "</p>";
}

$conn->close();
?>
