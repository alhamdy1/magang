#!/bin/bash

echo "Testing Laravel Server..."
echo ""

# Start server in background
php artisan serve --host=0.0.0.0 --port=8000 > /tmp/server.log 2>&1 &
SERVER_PID=$!

echo "Server started with PID: $SERVER_PID"
echo "Waiting 3 seconds for server to be ready..."
sleep 3

echo ""
echo "Testing HTTP connection..."
HTTP_CODE=$(curl -s -o /tmp/response.html -w "%{http_code}" http://localhost:8000)

echo "HTTP Response Code: $HTTP_CODE"
echo ""

if [ "$HTTP_CODE" = "200" ]; then
    echo "✓ Server is working correctly!"
    echo ""
    echo "First 30 lines of response:"
    head -30 /tmp/response.html
else
    echo "✗ Server returned error code: $HTTP_CODE"
    echo ""
    echo "Server logs:"
    cat /tmp/server.log
fi

echo ""
echo "Stopping server..."
kill $SERVER_PID
wait $SERVER_PID 2>/dev/null

echo "Done."
