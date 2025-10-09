# Quick Order - Food Ordering Platform

A production-ready food ordering system built with **Laravel 11** and **Filament v4**, featuring a complete admin panel and customer-facing storefront with cart, checkout, and order management.

---

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Quick Start](#quick-start)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Running the Application](#running-the-application)
- [Default Accounts](#default-accounts)
- [Backend Admin Panel Guide](#backend-admin-panel-guide)
- [Project Structure](#project-structure)
- [Key Features Guide](#key-features-guide)
- [Development](#development)
- [Production Deployment](#production-deployment)
- [Troubleshooting](#troubleshooting)
- [License](#license)

---

## ✨ Features

### Admin Panel (Filament v4)
- **Product Management**: Complete CRUD with multiple images, ingredients, and temperature-based pricing
- **Store Management**: Multi-store support with images and operating hours
- **Order Management**: Track orders with status workflows and detailed order items
- **Coupon System**: Create and manage discount coupons with various rules
- **Advertisement Management**: Manage promotional banners and ads
- **Member Management**: View and manage customer accounts
- **Advanced Features**: Bulk actions, image galleries, conditional forms, custom infolists

### Customer Storefront
- **Browse Products**: View products by store with images and detailed information
- **Shopping Cart**: Add items with temperature options and ingredient customization
- **Coupon Redemption**: Apply discount codes at checkout
- **Order Placement**: Complete checkout flow with order confirmation
- **Member Profile**: Update profile information and change password
- **Order History**: View past orders with detailed item breakdown
- **Toast Notifications**: Modern slide-in notifications for user feedback

### System Features
- **Multi-image Support**: Products and stores can have multiple images
- **Image Processing**: Automatic WebP conversion with Intervention Image
- **Temperature Options**: Hot, cold, regular pricing variations
- **Member Authentication**: Separate auth guard for customers
- **Order Number Generation**: Auto-incrementing daily order numbers (ORDYmd-0001)
- **Responsive Design**: Mobile-friendly interface across all pages
- **Database Seeding**: Sample data for quick testing

---

## 🛠 Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Admin UI**: Filament 4
- **Database**: MySQL 8+ / SQLite (default)
- **Frontend**: Blade templates, Vite, Custom CSS
- **Image Processing**: Intervention/Image
- **Authentication**: Laravel Sanctum + Multi-guard
- **Queue**: Database driver (configurable)
- **Cache**: Database driver (configurable)

---

## 📦 Prerequisites

- **PHP**: 8.2 or higher with extensions:
  - BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer
  - GD or Imagick (for image processing)
- **Composer**: Latest version
- **Node.js**: 18+ with npm
- **Database**: MySQL 8+ (recommended) or SQLite

---

## 🚀 Quick Start

### Option 1: Automated Setup (Recommended)

**macOS/Linux:**
```bash
git clone <repository-url>
cd quick-order-dev
chmod +x setup.sh
./setup.sh
```

**Windows:**
```cmd
git clone <repository-url>
cd quick-order-dev
setup.bat
```

### Option 2: Manual Setup

See the [Installation](#installation) section below for step-by-step instructions.

---

## 📥 Installation

### 1. Clone the Repository
   ```bash
   git clone <repository-url>
   cd quick-order-dev
   ```

### 2. Copy Environment File
   ```bash
# macOS/Linux
cp .env.example .env

# Windows (PowerShell)
copy .env.example .env
```

### 3. Configure Environment

Edit `.env` and configure your database:

**For MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quick_order
DB_USERNAME=root
DB_PASSWORD=your_password
```

**For SQLite (Default - Quick Start):**
```env
DB_CONNECTION=sqlite
# DB_HOST, DB_PORT, DB_DATABASE will be ignored
```

### 4. Install Dependencies
   ```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
   npm install
   ```

### 5. Generate Application Key
   ```bash
   php artisan key:generate
   ```

### 6. Create Storage Link
   ```bash
   php artisan storage:link
   ```

This creates a symbolic link from `public/storage` to `storage/app/public` for serving uploaded images.

---

## 🗄 Database Setup

### Create Database (MySQL only)

```sql
CREATE DATABASE quick_order 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;
```

### Run Migrations

```bash
# Fresh install with sample data (recommended)
php artisan migrate:fresh --seed

# Or just migrations
php artisan migrate

# Then seed separately
php artisan db:seed
```

### What Gets Seeded

The seeders create:
- **1 Admin User** for Filament panel
- **5 Members** with sample orders and carts
- **5 Stores** (4 active, 1 inactive)
- **8 Products** with images and ingredients
- **3 Advertisements**
- **4 Coupons** (WELCOME10, SAVE5, SUMMER20, FREESHIP)

---

## 🏃 Running the Application

### Development Server

**Start Laravel server:**
  ```bash
  php artisan serve
  ```
Application available at: `http://localhost:8000`

**Start Vite dev server (separate terminal):**
  ```bash
  npm run dev
  ```

**All-in-one (with concurrently):**
  ```bash
  composer dev
  ```
This starts Laravel server, queue worker, logs, and Vite simultaneously.

### Access Points

- **Customer Storefront**: `http://localhost:8000`
- **Admin Panel**: `http://localhost:8000/admin`
- **Member Login**: `http://localhost:8000/auth`
- **Member Profile**: `http://localhost:8000/member/profile`
- **Member Orders**: `http://localhost:8000/member/orders`
- **Shopping Cart**: `http://localhost:8000/cart`
- **Toast Demo**: `http://localhost:8000/toast-demo`

---

## 🔐 Default Accounts

### Admin Panel
- URL: `http://localhost:8000/admin`
  - Email: `admin@quickorder.com`
  - Password: `password`

### Customer Account
- URL: `http://localhost:8000/auth`
- Email: `member@test.com`
- Password: `password`

**Additional Test Members:**
- `john@example.com` / `password`
- `jane@example.com` / `password` (has active cart)
- `michael@example.com` / `password`
- `sarah@example.com` / `password`

### Sample Coupon Codes
- `WELCOME10` - 10% off
- `SAVE5` - $5 off
- `SUMMER20` - 20% off
- `FREESHIP` - Free shipping

---

## 🎛 Backend Admin Panel Guide

For detailed backend administration instructions, see **[BACKEND_GUIDE.md](BACKEND_GUIDE.md)**.

For backend optimization details and technical documentation, see **[BACKEND_COMPLETE.md](BACKEND_COMPLETE.md)**.

### Navigation Structure

The admin panel is organized into the following groups:

**🏪 Store Management**
- Stores - Manage physical/virtual stores
- Products - Manage product catalog

**💰 Sales**
- Orders - View and manage customer orders
- Coupons - Create and manage discount codes

**📢 Marketing**
- Ads - Manage promotional banners

**👥 Customer Management**
- Members - Manage customer accounts

**⚙️ System Management**
- Users - Manage admin users

### Quick Tips

1. **Creating Products**: Navigate to `Store Management > Products > Create`
2. **Processing Orders**: Go to `Sales > Orders`, click order number, then `Edit` to update status
3. **Creating Coupons**: Navigate to `Sales > Coupons > Create`
4. **Managing Ads**: Go to `Marketing > Ads` to create promotional banners

For complete backend usage instructions, refer to [BACKEND_GUIDE.md](BACKEND_GUIDE.md).

---

## 📁 Project Structure

```
quick-order-dev/
├── app/
│   ├── Filament/
│   │   └── Resources/
│   │       ├── Products/         # Product management
│   │       │   ├── Schemas/
│   │       │   │   ├── ProductForm.php
│   │       │   │   ├── ProductTable.php
│   │       │   │   └── ProductInfolist.php
│   │       │   └── ProductResource.php
│   │       ├── Stores/           # Store management
│   │       ├── Orders/           # Order management
│   │       ├── Coupons/          # Coupon management
│   │       ├── Ads/              # Advertisement management
│   │       └── Members/          # Member management
│   ├── Http/Controllers/
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── FrontendController.php
│   │   ├── MemberAuthController.php
│   │   ├── MemberController.php
│   │   ├── ProductController.php
│   │   └── StoreController.php
│   ├── Models/
│   │   ├── Ad.php
│   │   ├── Cart.php
│   │   ├── CartItem.php
│   │   ├── Coupon.php
│   │   ├── Member.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Product.php
│   │   ├── ProductImage.php
│   │   ├── ProductIngredient.php
│   │   ├── Store.php
│   │   └── StoreImage.php
│   └── Services/
│       └── ImageService.php      # Image processing service
├── database/
│   ├── migrations/               # Database schema
│   └── seeders/                  # Sample data seeders
├── public/
│   ├── css/custom/              # Custom stylesheets
│   │   ├── auth.css
│   │   ├── cart.css
│   │   ├── checkout.css
│   │   ├── index.css
│   │   ├── order.css
│   │   ├── product.css
│   │   ├── store.css
│   │   └── toast.css
│   └── js/
│       └── toast.js             # Toast notification system
├── resources/
│   ├── views/
│   │   ├── frontend/
│   │   │   ├── index.blade.php         # Home page
│   │   │   ├── auth.blade.php          # Login/Register
│   │   │   ├── cart.blade.php          # Shopping cart
│   │   │   ├── checkout.blade.php      # Checkout
│   │   │   ├── store-detail.blade.php  # Store detail
│   │   │   ├── product.blade.php       # Product detail
│   │   │   ├── member-profile.blade.php # Member profile
│   │   │   ├── member-orders.blade.php  # Order history
│   │   │   └── order-confirmation.blade.php
│   │   └── layouts/
│   │       └── app.blade.php    # Main layout with header/footer
│   ├── css/app.css
│   └── js/app.js
├── routes/
│   └── web.php                  # Application routes
└── storage/
    └── app/public/              # Uploaded images (symlinked)
```

---

## 🎯 Key Features Guide

### 1. Member Authentication System

**Separate Guard**: Members use `member` guard, separate from admin users.

**Routes:**
- `GET /auth` - Login/Register page
- `POST /login` - Member login
- `POST /register` - Member registration
- `POST /logout` - Member logout

**Middleware**: Use `auth:member` for protected routes.

### 2. Shopping Cart System

**Features:**
- Temperature selection (hot/cold/regular)
- Ingredient customization
- Quantity management
- Coupon application
- Session-based for guests, member-based for logged-in users

**Cart Structure:**
```php
Cart {
    member_id, session_id, subtotal, discount_amount, total_amount, status
    -> items: CartItem[]
}

CartItem {
    product_id, product_name, quantity, temperature, unit_price, subtotal
}
```

### 3. Order System

**Order Number Format**: `ORDYmd-0001` (e.g., `ORD20251008-0001`)
- Resets daily
- Auto-incrementing sequence
- 4-digit padding

**Order Statuses**: pending, confirmed, processing, ready, completed, cancelled

**Payment Methods**: cash, card, mobile_payment, bank_transfer

### 4. Toast Notification System

**Global Usage:**
```javascript
// Basic usage
successToast('Operation successful!');
errorToast('An error occurred');
warningToast('Warning message');
infoToast('Information message');

// With custom title
successToast('Order placed!', 'Success');

// Custom duration (ms)
Toast.show('Message', 'success', 'Title', 3000);
```

**Laravel Integration:**
```php
return redirect()->back()->with('success', 'Profile updated!');
```

Auto-converts to toast notifications.

### 5. Image Processing

**Automatic WebP Conversion:**
```php
use App\Services\ImageService;

$imageService = app(ImageService::class);
$path = $imageService->store($uploadedFile, 'products');
```

**Features:**
- Converts to WebP format
- Maintains aspect ratio
- Generates unique UUID filenames
- Stores in `storage/app/public/{folder}`

### 6. Multi-Store Support

Products belong to stores. Browse products by store on the frontend.

**Store Detail Page**: `/stores/{store}`
- Shows store information
- Lists all available products
- Store images gallery

---

## 💻 Development

### Build Assets

```bash
# Development (watch mode)
npm run dev

# Production build
npm run build
```

### Clear Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Or clear all
php artisan optimize:clear
```

### Queue Worker

```bash
# Run queue worker
php artisan queue:work

# With supervisor (production)
php artisan queue:listen --tries=3
```

### Testing

```bash
# Run tests
php artisan test

# Or with composer
composer test
```

### Code Style

```bash
# Format code (if Pint is installed)
./vendor/bin/pint
```

---

## 🚢 Production Deployment

### 1. Environment Configuration

```bash
# Set app environment
APP_ENV=production
APP_DEBUG=false

# Generate new app key
php artisan key:generate --force

# Configure production database
DB_CONNECTION=mysql
DB_HOST=your_db_host
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password
```

### 2. Optimize Application

```bash
# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
npm run build
```

### 3. Set Permissions

```bash
# Storage and bootstrap cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 4. Queue Worker (Supervisor)

Create supervisor config `/etc/supervisor/conf.d/quick-order.conf`:

```ini
[program:quick-order-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/quick-order-dev/artisan queue:work --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/quick-order-dev/storage/logs/worker.log
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start quick-order-worker:*
```

### 5. Schedule (Cron)

Add to crontab:
```cron
* * * * * cd /path/to/quick-order-dev && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🐛 Troubleshooting

### Images Not Loading

**Issue**: Uploaded images return 404

**Solution**:
```bash
# Create storage symlink
php artisan storage:link

# Check permissions
chmod -R 775 storage
chown -R www-data:www-data storage
```

### Database Connection Errors

**Issue**: `SQLSTATE[HY000] [2002] Connection refused`

**Solution**:
1. Verify database server is running
2. Check `.env` credentials
3. For MySQL: ensure database exists
4. For SQLite: ensure `database/database.sqlite` exists

### Seeder Errors

**Issue**: Missing columns during seeding

**Solution**:
```bash
# Fresh migration and seed
php artisan migrate:fresh --seed
```

### Admin Panel Not Accessible

**Issue**: Cannot access `/admin`

**Solution**:
```bash
# Create admin user
php artisan make:filament-user
```

### Toast Notifications Not Showing

**Solution**:
1. Clear browser cache
2. Check `toast.js` and `toast.css` are loaded
3. Check browser console for errors

### Stale Configuration

**Solution**:
```bash
php artisan optimize:clear
```

### Permission Denied Errors

**Solution**:
```bash
# Fix storage permissions
sudo chown -R $USER:www-data storage
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache
```

---

## 📚 Additional Documentation

### Filament Resources

- [Filament Official Docs](https://filamentphp.com/docs)
- Modular resource architecture with Schemas (Form, Table, Infolist)
- Custom pages for specialized workflows
- Relationship managers for related data

### Custom CSS Structure

- `public/css/custom.css` - Global styles
- `public/css/custom/*.css` - Page-specific styles
- All styles use consistent color scheme and spacing

### API Endpoints

Currently, the application uses traditional web routes. RESTful API can be added using Laravel Sanctum for mobile app integration.

---

## 📝 License

This project is proprietary software. All rights reserved.

---

## 🤝 Contributing

Internal development only. Contact the development team for collaboration.

---

## 📧 Support

For issues or questions, contact the development team.

---

**Built with ❤️ using Laravel 11 & Filament v4**

Happy coding! 🚀🍔
