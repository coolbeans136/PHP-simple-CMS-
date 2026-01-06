#!/bin/bash

# Ensure MariaDB is running
if ! pgrep -x "mariadbd" > /dev/null; then
    echo "Starting MariaDB..."
    sudo service mariadb start
fi

# Kill any existing PHP server on port 8000
pkill -f "php -S localhost:8000" 2>/dev/null

# Wait a moment
sleep 1

# Start PHP server in background
cd "$(dirname "$0")"
nohup php -S localhost:8000 > server.log 2>&1 &
SERVER_PID=$!

echo "======================================"
echo "  CMS Server Started"
echo "======================================"
echo ""
echo "Server PID: $SERVER_PID"
echo "Log file: server.log"
echo ""
echo "Access your site at:"
echo "  • Main:   http://localhost:8000/launch/"
echo "  • Public: http://localhost:8000/public/"
echo "  • Admin:  http://localhost:8000/admin/login.php"
echo "  • Media:  http://localhost:8000/admin/media.php"
echo ""
echo "Login: admin / admin123"
echo ""
echo "To stop the server: ./stop_server.sh"
echo "To view logs: tail -f server.log"
echo "======================================"
