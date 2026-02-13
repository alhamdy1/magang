#!/bin/bash

# Script untuk update APP_URL di GitHub Codespaces
# Otomatis detect codespace URL dan update .env

echo "🔧 Codespaces URL Updater"
echo "=========================="
echo ""

# Check if running in GitHub Codespaces
if [ -z "$CODESPACE_NAME" ]; then
    echo "⚠️  Not running in GitHub Codespaces"
    echo "   Using default: http://localhost:8000"
    sed -i 's|^APP_URL=.*|APP_URL=http://localhost:8000|' .env
    echo "✓ APP_URL set to: http://localhost:8000"
    exit 0
fi

# Construct Codespaces URL
CODESPACE_URL="https://${CODESPACE_NAME}-8000.${GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN}"

echo "✓ Detected GitHub Codespaces environment"
echo "  Codespace: $CODESPACE_NAME"
echo "  URL: $CODESPACE_URL"
echo ""

# Update .env file
if [ -f .env ]; then
    # Update existing APP_URL
    sed -i "s|^APP_URL=.*|APP_URL=$CODESPACE_URL|" .env
    echo "✓ Updated APP_URL in .env"
    echo ""
    
    # Clear Laravel cache
    php artisan config:clear --quiet
    php artisan cache:clear --quiet
    echo "✓ Cache cleared"
    echo ""
    
    echo "✅ Configuration complete!"
    echo "   Your app URL: $CODESPACE_URL"
    echo ""
    echo "Next steps:"
    echo "1. Make sure server is running: php artisan serve --host=0.0.0.0 --port=8000"
    echo "2. Access your app at: $CODESPACE_URL"
else
    echo "❌ Error: .env file not found"
    echo "   Run: cp .env.example .env"
    exit 1
fi
