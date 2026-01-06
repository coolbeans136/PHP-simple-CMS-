#!/bin/bash

echo "==================================="
echo "CMS Rich Media Setup"
echo "==================================="
echo ""

# Get database credentials
echo "This script will update your database to add media support."
echo ""
read -p "Database host [localhost]: " DB_HOST
DB_HOST=${DB_HOST:-localhost}

read -p "Database name [cms_db]: " DB_NAME
DB_NAME=${DB_NAME:-cms_db}

read -p "Database user [root]: " DB_USER
DB_USER=${DB_USER:-root}

read -sp "Database password: " DB_PASS
echo ""
echo ""

# Run database update
echo "Updating database..."
mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < update_db.sql

if [ $? -eq 0 ]; then
    echo "✓ Database updated successfully!"
else
    echo "✗ Database update failed. Please run update_db.sql manually."
    exit 1
fi

# Check if uploads directory exists and has proper permissions
if [ ! -d "uploads" ]; then
    echo "Creating uploads directory..."
    mkdir -p uploads
fi

chmod 755 uploads
echo "✓ Uploads directory configured"

# Check if .htaccess exists in uploads
if [ ! -f "uploads/.htaccess" ]; then
    echo "⚠ Warning: uploads/.htaccess not found"
else
    echo "✓ Upload security configured"
fi

echo ""
echo "==================================="
echo "Setup Complete!"
echo "==================================="
echo ""
echo "You can now:"
echo "1. Access the Media Library at: admin/media.php"
echo "2. Upload images and videos"
echo "3. Use the rich text editor in Pages and Posts"
echo ""
echo "See README_MEDIA.md for detailed usage instructions."
echo ""
