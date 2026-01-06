#!/bin/bash

echo "Stopping PHP development server..."
pkill -f "php -S localhost:8000"

if [ $? -eq 0 ]; then
    echo "✓ Server stopped successfully"
else
    echo "No server was running on port 8000"
fi
