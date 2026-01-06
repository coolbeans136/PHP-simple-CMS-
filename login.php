<?php
require_once '../config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simple rate limiting
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt'] = time();
    }
    
    // Reset after 15 minutes
    if (time() - $_SESSION['last_attempt'] > 900) {
        $_SESSION['login_attempts'] = 0;
    }
    
    // Check if too many attempts
    if ($_SESSION['login_attempts'] >= 5) {
        $time_left = 900 - (time() - $_SESSION['last_attempt']);
        $error = 'Too many login attempts. Please try again in ' . ceil($time_left / 60) . ' minutes.';
    } else {
    
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['login_attempts'] = 0; // Reset on success
            header('Location: index.php');
            exit();
        }
    }
    
    $_SESSION['login_attempts']++;
    $_SESSION['last_attempt'] = time();
    $error = 'Invalid username or password';
    $stmt->close();
    $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: #2c3e50;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h2>CMS Admin</h2>
            <p class="text-muted">Sign in to your account</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        
        <div class="text-center mt-3">
            <small class="text-muted">Default: admin / admin123</small>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
