# Setup Instructions

## Quick Start

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Setup Environment**
   - Copy `.env.example` to `.env`
   - Set database credentials in `.env`
   - Run: `php artisan key:generate`

3. **Database Setup**
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Compile Assets**
   ```bash
   npm run dev
   ```

5. **Run Server**
   ```bash
   php artisan serve
   ```

## File Locations

- **POS View**: `resources/views/sales/pos.blade.php`
- **Main Migration**: `database/migrations/2024_01_01_000004_create_products_table.php`
- **Sales API**: `app/Http/Controllers/SalesController.php` (method: `store`)

## API Testing

### Test Sales Endpoint (after login):
```bash
curl -X POST http://localhost:8000/api/sales \
  -H "Content-Type: application/json" \
  -H "Cookie: laravel_session=YOUR_SESSION" \
  -d '{
    "items": [{"product_id": 1, "qty": 1, "price": 55000}],
    "discount": 0,
    "payment_method": "cash"
  }'
```

**Note**: Replace `YOUR_SESSION` with actual session cookie from browser after logging in.

## Default Users

- Admin: admin@example.com / password123
- Kasir: kasir@example.com / password123  
- Gudang: gudang@example.com / password123

