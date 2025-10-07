#!/bin/bash

echo "🚀 Quick Order - 快速安裝腳本"
echo "================================"
echo ""

# 檢查是否有 .env 文件
if [ ! -f .env ]; then
    echo "📝 複製 .env.example 到 .env..."
    cp .env.example .env
    echo "✅ .env 文件已創建"
else
    echo "✅ .env 文件已存在"
fi

echo ""
echo "🔑 生成應用金鑰..."
php artisan key:generate

echo ""
echo "🗄️ 創建資料庫..."
mysql -u root -e "CREATE DATABASE IF NOT EXISTS quick_order CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✅ 資料庫 'quick_order' 已創建"
else
    echo "⚠️  請手動創建資料庫 'quick_order'"
fi

echo ""
echo "📦 安裝 Composer 依賴..."
composer install --no-interaction

echo ""
echo "🔄 重新生成 autoload..."
composer dump-autoload

echo ""
echo "🗂️ 執行資料庫遷移..."
php artisan migrate --force

echo ""
echo "🌱 填充測試資料..."
php artisan db:seed --force

echo ""
echo "🔗 創建儲存連結..."
php artisan storage:link

echo ""
echo "🧹 清除所有快取..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo ""
echo "================================"
echo "✅ 安裝完成！"
echo ""
echo "🌐 前台: http://localhost:8000"
echo "🔐 後台: http://localhost:8000/admin"
echo ""
echo "📧 管理員帳號:"
echo "   Email: admin@quickorder.com"
echo "   密碼: password"
echo ""
echo "🎟️ 測試優惠券:"
echo "   WELCOME10, SAVE5, SUMMER20, FREESHIP"
echo ""
echo "🚀 啟動伺服器: php artisan serve"
echo "================================"

