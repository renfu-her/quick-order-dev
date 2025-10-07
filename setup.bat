@echo off
echo 🚀 Quick Order - 快速安裝腳本
echo ================================
echo.

REM 檢查是否有 .env 文件
if not exist .env (
    echo 📝 複製 .env.example 到 .env...
    copy .env.example .env
    echo ✅ .env 文件已創建
) else (
    echo ✅ .env 文件已存在
)

echo.
echo 🔑 生成應用金鑰...
php artisan key:generate

echo.
echo 🗄️ 創建資料庫...
mysql -u root -e "CREATE DATABASE IF NOT EXISTS quick_order CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul
if %errorlevel% equ 0 (
    echo ✅ 資料庫 'quick_order' 已創建
) else (
    echo ⚠️  請手動創建資料庫 'quick_order'
    echo    方法: 使用 Laragon Database Manager 或 phpMyAdmin
)

echo.
echo 📦 安裝 Composer 依賴...
call composer install --no-interaction

echo.
echo 🔄 重新生成 autoload...
call composer dump-autoload

echo.
echo 🗂️ 執行資料庫遷移...
php artisan migrate --force

echo.
echo 🌱 填充測試資料...
php artisan db:seed --force

echo.
echo 🔗 創建儲存連結...
php artisan storage:link

echo.
echo 🧹 清除所有快取...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo ================================
echo ✅ 安裝完成！
echo.
echo 🌐 前台: http://localhost:8000
echo 🔐 後台: http://localhost:8000/admin
echo.
echo 📧 管理員帳號:
echo    Email: admin@quickorder.com
echo    密碼: password
echo.
echo 🎟️ 測試優惠券:
echo    WELCOME10, SAVE5, SUMMER20, FREESHIP
echo.
echo 🚀 啟動伺服器: php artisan serve
echo ================================
echo.
pause

