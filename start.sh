#!/bin/bash

echo "======================================"
echo "  Starting CMS Development Server"
echo "======================================"
echo ""

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "Error: PHP is not installed"
    exit 1
fi

# Check if MySQL is running
if ! pgrep -x "mysqld" > /dev/null; then
    echo "Warning: MySQL might not be running"
    echo "Please start MySQL before using the CMS"
fi

# Get the project root directory
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

echo "Project Root: $PROJECT_ROOT"
echo ""
echo "Starting PHP development server on port 8000..."
echo ""
echo "Access your site at:"
echo "  • Main Launch Page:  http://localhost:8000/launch/"
echo "  • Public Site:       http://localhost:8000/public/"
echo "  • Admin Panel:       http://localhost:8000/admin/login.php"
echo ""
echo "Default admin credentials:"
echo "  Username: admin"
echo "  Password: admin123"
echo ""
echo "Press Ctrl+C to stop the server"
echo "======================================"
echo ""

# Start PHP server
cd "$PROJECT_ROOT"
php -S localhost:8000
