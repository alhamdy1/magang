#!/bin/bash

# 🚀 Quick Start Script - Sistem Perizinan Reklame
# Script ini memudahkan Anda menjalankan berbagai command

echo "=================================="
echo "  Sistem Perizinan Reklame"
echo "=================================="
echo ""

show_menu() {
    echo "Pilih opsi:"
    echo "1) Start Development Server"
    echo "2) Start with Hot Reload (Server + Vite)"
    echo "3) Reset Database & Seed Data"
    echo "4) Clear All Cache"
    echo "5) View Logs (realtime)"
    echo "6) Run Tests"
    echo "7) Show User Accounts"
    echo "0) Exit"
    echo ""
}

while true; do
    show_menu
    read -p "Masukkan pilihan [0-7]: " choice
    echo ""
    
    case $choice in
        1)
            echo "🚀 Starting Laravel Server..."
            php artisan serve
            ;;
        2)
            echo "🚀 Starting Laravel Server + Vite..."
            echo "Buka terminal baru dan jalankan: npm run dev"
            php artisan serve
            ;;
        3)
            echo "⚠️  WARNING: Ini akan menghapus semua data!"
            read -p "Lanjutkan? (y/n): " confirm
            if [ "$confirm" = "y" ]; then
                php artisan migrate:fresh --seed
                echo "✓ Database reset dan seeded!"
            fi
            ;;
        4)
            echo "🧹 Clearing cache..."
            php artisan cache:clear
            php artisan config:clear
            php artisan view:clear
            php artisan route:clear
            echo "✓ Cache cleared!"
            ;;
        5)
            echo "📋 Viewing logs (Ctrl+C to stop)..."
            tail -f storage/logs/laravel.log
            ;;
        6)
            echo "🧪 Running tests..."
            php artisan test
            ;;
        7)
            echo "👥 User Accounts untuk Testing:"
            echo "================================"
            echo ""
            echo "Password untuk semua: password"
            echo ""
            echo "Role       | Email"
            echo "-----------|-------------------------"
            echo "Admin      | admin@perizinan.com"
            echo "Kabid      | kabid@perizinan.com"
            echo "Kasi       | kasi@perizinan.com"
            echo "Operator 1 | operator1@perizinan.com"
            echo "Operator 2 | operator2@perizinan.com"
            echo "User       | user@perizinan.com"
            echo ""
            read -p "Press Enter to continue..."
            ;;
        0)
            echo "Goodbye! 👋"
            exit 0
            ;;
        *)
            echo "❌ Pilihan tidak valid!"
            ;;
    esac
    
    echo ""
    echo "=================================="
    echo ""
done
