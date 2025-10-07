# Quick Order - Quick Start Guide

## 🚀 Fast Setup (5 Minutes)

### Step 1: Configure Database

Create `.env` file from `.env.example` and update:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quick_order
DB_USERNAME=root
DB_PASSWORD=
```

### Step 2: Create Database

```bash
mysql -u root -e "CREATE DATABASE quick_order;"
```

### Step 3: Install & Migrate

```bash
# Install dependencies
composer install

# Generate application key
php artisan key:generate

# Run migrations and seed data
php artisan migrate --seed

# Create storage link
php artisan storage:link
```

### Step 4: Start Server

```bash
php artisan serve
```

## 🎉 You're Ready!

### Frontend
Visit: **http://localhost:8000**

### Admin Panel
Visit: **http://localhost:8000/admin**

**Login Credentials:**
- Email: `admin@quickorder.com`
- Password: `password`

## 📦 What's Included

✅ 8 Sample Products (burgers, pizza, drinks, etc.)  
✅ 3 Active Advertisements  
✅ 4 Working Coupon Codes  
✅ Complete Product Images & Ingredients  
✅ Full Admin Panel (Products, Orders, Coupons, Ads)  
✅ Customer Frontend (Browse, Cart, Checkout)  

## 🎟️ Test Coupons

Try these codes at checkout:

- `WELCOME10` - 10% off (min $20)
- `SAVE5` - $5 off (min $30)
- `SUMMER20` - 20% off (min $50)
- `FREESHIP` - $3 off (min $25)

## 📚 Full Documentation

See `README_SETUP.md` for detailed documentation.

## 🐛 Troubleshooting

**Issue: Storage images not showing**
```bash
php artisan storage:link
```

**Issue: Database connection error**
```bash
# Check .env DB credentials
# Ensure MySQL is running
# Database exists
```

**Issue: Can't access admin**
```bash
# Create new admin user
php artisan make:filament-user
```

## 🎯 Next Steps

1. ✅ Browse products on homepage
2. ✅ Add products to cart
3. ✅ Apply coupon code
4. ✅ Complete checkout
5. ✅ Check admin panel for new order
6. ✅ Update order status
7. ✅ Create your own products!

---

**Happy Ordering! 🍔🍕🍰**

