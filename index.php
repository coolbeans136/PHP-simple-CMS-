<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Launch Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        .launch-container {
            max-width: 900px;
            width: 100%;
            padding: 20px;
        }
        .launch-card {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .launch-header {
            text-align: center;
            margin-bottom: 50px;
        }
        .launch-header h1 {
            color: #2c3e50;
            font-weight: 700;
            font-size: 3rem;
            margin-bottom: 10px;
        }
        .launch-header p {
            color: #7f8c8d;
            font-size: 1.2rem;
        }
        .access-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            display: block;
        }
        .access-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            color: white;
        }
        .access-card.admin {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .access-card.public {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .access-card h3 {
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .access-card p {
            margin: 0;
            opacity: 0.9;
        }
        .access-card i {
            font-size: 2rem;
        }
        .info-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-top: 30px;
        }
        .info-section h5 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .info-item {
            display: flex;
            align-items: start;
            margin-bottom: 10px;
        }
        .info-item i {
            color: #667eea;
            margin-right: 10px;
            margin-top: 3px;
        }
        .badge-custom {
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="launch-container">
        <div class="launch-card">
            <div class="launch-header">
                <h1><i class="bi bi-rocket-takeoff"></i> CMS Launch</h1>
                <p>Choose where you want to go</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <a href="../public/index.php" class="access-card public">
                        <h3>
                            <i class="bi bi-globe"></i>
                            Public Website
                        </h3>
                        <p>View the public-facing website with all published content</p>
                        <div class="mt-3">
                            <span class="badge-custom">Pages</span>
                            <span class="badge-custom">Blog</span>
                            <span class="badge-custom">Posts</span>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="../admin/login.php" class="access-card admin">
                        <h3>
                            <i class="bi bi-shield-lock"></i>
                            Admin Panel
                        </h3>
                        <p>Manage content, pages, posts, and site settings</p>
                        <div class="mt-3">
                            <span class="badge-custom">Dashboard</span>
                            <span class="badge-custom">CMS</span>
                            <span class="badge-custom">Settings</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="info-section">
                <h5><i class="bi bi-info-circle"></i> Quick Info</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item">
                            <i class="bi bi-person-circle"></i>
                            <div>
                                <strong>Default Admin Login</strong><br>
                                <small>Username: <code>admin</code><br>
                                Password: <code>admin123</code></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <i class="bi bi-database"></i>
                            <div>
                                <strong>Database</strong><br>
                                <small>Make sure MySQL is running<br>
                                Database: <code>cms_db</code></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="info-item">
                            <i class="bi bi-book"></i>
                            <div>
                                <strong>Documentation</strong><br>
                                <small>See <a href="../README_CMS.md" target="_blank">README_CMS.md</a> for setup</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <i class="bi bi-server"></i>
                            <div>
                                <strong>Server</strong><br>
                                <small>Running on port 8000</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted mb-0">
                    <i class="bi bi-code-slash"></i> Simple CMS with PHP & MySQL
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
