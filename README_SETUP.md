# Quick Order - Complete Implementation Guide

## Project Overview

Quick Order is a complete food ordering system built with Laravel 11 and Filament v4. It features a full-featured admin panel for managing products, orders, coupons, and advertisements, along with a modern frontend for customers to browse and order food.

## Tech Stack

- **Backend:** Laravel 11
- **Admin Panel:** Filament v4
- **Database:** MySQL
- **Frontend:** Blade Templates with Custom CSS
- **Image Processing:** Intervention Image

## Database Structure

### Tables Overview

1. **products** - Product catalog with pricing options
2. **product_images** - Product image gallery
3. **product_ingredients** - Product ingredients with extra pricing
4. **ads** - Advertisement management
5. **coupons** - Discount coupon system
6. **carts** - Shopping cart (session/user-based)
7. **cart_items** - Cart item details
8. **orders** - Order management
9. **order_items** - Order line items

### Key Relationships

- Product → hasMany ProductImages
- Product → hasMany ProductIngredients
- Product → hasMany Ads
- Order → belongsTo User (nullable)
- Order → belongsTo Coupon (nullable)
- Order → hasMany OrderItems
- Cart → hasMany CartItems

## Installation Steps

### 1. Database Setup

Update your `.env` file with MySQL configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quick_order
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Create Database

```bash
# Create the database
mysql -u root -e "CREATE DATABASE quick_order CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 3. Install Dependencies

```bash
composer install
npm install
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Seed Sample Data

```bash
php artisan db:seed
```

This will create:
- Admin user (admin@quickorder.com / password)
- 8 sample products with categories
- Product ingredients
- 3 active advertisements
- 4 sample coupons

### 7. Create Storage Link

```bash
php artisan storage:link
```

### 8. Compile Assets

```bash
npm run dev
# or for production
npm run build
```

### 9. Start Development Server

```bash
php artisan serve
```

## Access Points

### Frontend
- **URL:** http://localhost:8000
- **Features:**
  - Browse products by category
  - View product details with images
  - Add to cart with temperature options
  - Apply discount coupons
  - Complete checkout process
  - View order confirmation

### Admin Panel
- **URL:** http://localhost:8000/admin
- **Credentials:**
  - Email: admin@quickorder.com
  - Password: password

## Filament Resources

### Product Resource
- Full CRUD operations
- Image gallery management (multiple images per product)
- Ingredient management with extra pricing
- Multiple price options:
  - Base price (required)
  - Special price (optional)
  - Hot price (optional)
  - Cold price (optional)
- Category filtering
- Availability toggle

### Ad Resource
- Advertisement management
- Link to specific products
- Display order control
- Date range scheduling
- Active/inactive status

### Order Resource
- View all orders
- Update order status (pending → confirmed → preparing → ready → completed)
- Update payment status
- View order details with items
- Customer information
- Order summary with discounts

### Coupon Resource
- Two discount types:
  - Fixed amount
  - Percentage-based
- Minimum purchase requirements
- Maximum discount caps
- Usage limits
- Date range validity
- Usage tracking

## Sample Coupons

Test these coupon codes in the checkout:

1. **WELCOME10**
   - 10% discount
   - Min purchase: $20
   - Max discount: $5

2. **SAVE5**
   - $5 fixed discount
   - Min purchase: $30

3. **SUMMER20**
   - 20% discount
   - Min purchase: $50
   - Max discount: $15

4. **FREESHIP**
   - $3 fixed discount
   - Min purchase: $25

## Features

### Frontend Features

1. **Homepage**
   - Active advertisements carousel
   - Product grid with images
   - Category-based browsing
   - Availability status

2. **Product Detail Page**
   - Image gallery with thumbnails
   - Product description
   - Temperature selection (hot/cold/regular)
   - Quantity selector
   - Ingredient list
   - Add to cart functionality

3. **Shopping Cart**
   - View cart items
   - Update quantities
   - Remove items
   - Apply coupon codes
   - Real-time total calculation
   - Discount display

4. **Checkout**
   - Customer information form
   - Payment method selection
   - Order summary
   - Coupon display
   - Order placement

5. **Order Confirmation**
   - Order number display
   - Order status tracking
   - Customer details
   - Order items breakdown
   - Total with discounts

### Admin Features

1. **Product Management**
   - Create/edit/delete products
   - Upload multiple images
   - Set display order and primary image
   - Manage ingredients
   - Configure pricing for different temperatures
   - Set special prices
   - Category management
   - Availability control

2. **Advertisement Management**
   - Create promotional ads
   - Link to products
   - Schedule start/end dates
   - Control display order
   - Active/inactive status

3. **Order Management**
   - View all orders
   - Filter by status/payment status
   - View detailed order information
   - Update order status
   - Update payment status
   - View customer information
   - See applied coupons

4. **Coupon Management**
   - Create discount coupons
   - Set discount type and value
   - Configure minimum purchase
   - Set maximum discount
   - Define usage limits
   - Schedule validity periods
   - Track usage statistics

## Product Pricing Logic

The system supports flexible pricing:

```php
// Priority order for pricing:
1. Special Price (if set) - overrides base price
2. Temperature-based price (hot_price or cold_price)
3. Base Price (fallback)

// Example:
Product: Iced Coffee
- Base price: $4.99
- Hot price: $4.49
- Cold price: $4.99
- Special price: null

Customer selects "Hot" → charged $4.49
Customer selects "Cold" → charged $4.99
Customer selects "Regular" → charged $4.99
```

## Coupon Validation Rules

Coupons are validated against:

1. **Active Status:** Must be active
2. **Date Range:** Current date within starts_at and ends_at
3. **Usage Limit:** Used count less than usage limit
4. **Minimum Purchase:** Cart subtotal meets minimum requirement
5. **Maximum Discount:** Applied discount capped at max amount (for percentage coupons)

## Project Structure

```
app/
├── Filament/
│   └── Resources/
│       ├── ProductResource.php
│       ├── AdResource.php
│       ├── OrderResource.php
│       └── CouponResource.php
├── Http/
│   └── Controllers/
│       ├── FrontendController.php
│       ├── ProductController.php
│       ├── CartController.php
│       └── CheckoutController.php
├── Models/
│   ├── Product.php
│   ├── ProductImage.php
│   ├── ProductIngredient.php
│   ├── Ad.php
│   ├── Coupon.php
│   ├── Cart.php
│   ├── CartItem.php
│   ├── Order.php
│   └── OrderItem.php
database/
├── migrations/
│   ├── create_products_table.php
│   ├── create_product_images_table.php
│   ├── create_product_ingredients_table.php
│   ├── create_ads_table.php
│   ├── create_coupons_table.php
│   ├── create_carts_table.php
│   ├── create_cart_items_table.php
│   ├── create_orders_table.php
│   └── create_order_items_table.php
└── seeders/
    ├── ProductSeeder.php
    ├── AdSeeder.php
    └── CouponSeeder.php
resources/
└── views/
    ├── layouts/
    │   └── app.blade.php
    └── frontend/
        ├── index.blade.php
        ├── product.blade.php
        ├── cart.blade.php
        ├── checkout.blade.php
        └── order-confirmation.blade.php
```

## Common Operations

### Adding New Products (Admin)

1. Go to Products → Create
2. Fill in product details (name, description, price)
3. Add images (upload multiple, mark one as primary)
4. Add ingredients with extra pricing
5. Set availability status
6. Save

### Processing Orders (Admin)

1. Go to Orders
2. Click on order to view details
3. Update status through workflow:
   - Pending → Confirmed → Preparing → Ready → Completed
4. Update payment status if needed

### Creating Promotional Campaigns (Admin)

1. Create coupon code in Coupons
2. Create ad in Advertisements
3. Link ad to featured product
4. Set start/end dates
5. Activate both

## Troubleshooting

### Images not showing
```bash
# Ensure storage link exists
php artisan storage:link

# Check permissions
chmod -R 775 storage/app/public
```

### Database errors
```bash
# Clear config cache
php artisan config:clear

# Re-run migrations
php artisan migrate:fresh --seed
```

### Filament access issues
```bash
# Create new admin user
php artisan make:filament-user
```

## Production Deployment

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Run `php artisan view:cache`
6. Run `npm run build`
7. Set proper file permissions
8. Configure web server (Apache/Nginx)
9. Set up SSL certificate
10. Configure email for order notifications

## Support

For issues or questions, please refer to:
- Laravel Documentation: https://laravel.com/docs
- Filament Documentation: https://filamentphp.com/docs

## License

This project is open-sourced software licensed under the MIT license.

