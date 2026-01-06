#!/bin/bash

# Check if server is running
if pgrep -f "php -S localhost:8000" > /dev/null; then
    PID=$(pgrep -f "php -S localhost:8000")
    echo "✓ Server is running (PID: $PID)"
    echo ""
    echo "Access at: http://localhost:8000"
    echo ""
    echo "Recent log entries:"
    echo "-------------------"
    tail -n 10 server.log 2>/dev/null || echo "No log file found"
else
    echo "✗ Server is NOT running"
    echo ""
    echo "Start it with: ./start_server.sh"
fi

# Check MariaDB
if pgrep -x "mariadbd" > /dev/null; then
    echo ""
    echo "✓ MariaDB is running"
else
    echo ""
    echo "✗ MariaDB is NOT running"
    echo "Start it with: sudo service mariadb start"
fi
