# 資料庫設定指南

## 🗄️ 創建資料庫

您需要先創建 MySQL 資料庫才能執行遷移。

## 方法 1: 使用 MySQL 命令列

```bash
# 連接 MySQL (Windows Laragon)
mysql -u root

# 創建資料庫
CREATE DATABASE quick_order CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 確認創建成功
SHOW DATABASES;

# 退出
EXIT;
```

或一行命令:
```bash
mysql -u root -e "CREATE DATABASE quick_order CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

## 方法 2: 使用 Laragon Database Manager

1. 打開 Laragon
2. 點擊 "Database" 按鈕
3. 會打開 HeidiSQL 或 phpMyAdmin
4. 右鍵 → "Create new" → "Database"
5. 輸入資料庫名稱: `quick_order`
6. 選擇 Collation: `utf8mb4_unicode_ci`
7. 點擊 "OK"

## 方法 3: 使用 phpMyAdmin

1. 訪問 http://localhost/phpmyadmin (Laragon 預設)
2. 點擊 "New" 或 "新增"
3. 輸入資料庫名稱: `quick_order`
4. 選擇 "Collation": `utf8mb4_unicode_ci`
5. 點擊 "Create" 或 "建立"

## 驗證配置

確認 `.env` 文件中的設定:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quick_order
DB_USERNAME=root
DB_PASSWORD=
```

## 測試連接

```bash
# 測試資料庫連接
php artisan db:show

# 如果成功，應該顯示資料庫資訊
```

## 執行遷移

資料庫創建成功後:

```bash
# 執行所有遷移
php artisan migrate

# 或者重新開始（清除所有資料）
php artisan migrate:fresh

# 執行遷移並填充測試資料
php artisan migrate:fresh --seed
```

## 常見問題

### Q: 忘記資料庫密碼

Laragon 的 MySQL 預設:
- Username: `root`
- Password: (空白)

### Q: MySQL 連接被拒絕

檢查 MySQL 服務是否正在運行:
- 打開 Laragon
- 確認 MySQL 旁邊是綠色燈號
- 如果沒有，點擊 "Start All"

### Q: 端口衝突

如果預設的 3306 端口被占用:

```env
DB_PORT=3307  # 或其他可用端口
```

## 完成後

資料庫設定完成後，請參考 `SETUP_INSTRUCTIONS.md` 繼續設定系統。

---

**快速命令總結:**

```bash
# 1. 創建資料庫
mysql -u root -e "CREATE DATABASE quick_order;"

# 2. 執行遷移與填充
php artisan migrate:fresh --seed

# 3. 創建 storage 連結
php artisan storage:link

# 4. 啟動伺服器
php artisan serve
```

完成！🎉

