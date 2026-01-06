# CMS Launch Guide

This folder contains scripts and files to easily launch and access your CMS.

## Quick Start

### Linux/Mac:
```bash
cd launch
chmod +x start.sh
./start.sh
```

### Windows:
```bash
cd launch
start.bat
```

### Manual Start:
```bash
# From project root
php -S localhost:8000
```

## Access Points

Once the server is running, visit:

- **Launch Page**: http://localhost:8000/launch/
- **Public Site**: http://localhost:8000/public/
- **Admin Panel**: http://localhost:8000/admin/login.php

## Default Credentials

- Username: `admin`
- Password: `admin123`

## Files in this Folder

- `start.sh` - Launch script for Linux/Mac
- `start.bat` - Launch script for Windows
- `index.php` - Beautiful launch page with links to both admin and public
- `README.md` - This file

## Troubleshooting

If the server doesn't start:
1. Make sure PHP is installed: `php --version`
2. Check if port 8000 is available
3. Ensure you're in the correct directory

If the database doesn't connect:
1. Make sure MySQL is running
2. Import database.sql if you haven't already
3. Check credentials in config.php
