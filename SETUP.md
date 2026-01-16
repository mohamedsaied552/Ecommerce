# Quick Setup Guide

## .env Configuration

Add these lines to your `.env` file:

```env
# Paymob Configuration
PAYMOB_API_KEY=your_api_key_here
PAYMOB_INTEGRATION_ID=your_integration_id_here
PAYMOB_IFRAME_ID=your_iframe_id_here
PAYMOB_HMAC_SECRET=your_hmac_secret_here

# Admin User
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=your_secure_password

# Queue Configuration (for email sending)
QUEUE_CONNECTION=database
```

## Quick Start Commands

```bash
# Install dependencies
composer install

# Generate app key
php artisan key:generate

# Configure database in .env, then:
php artisan migrate

# Create queue table for email jobs
php artisan queue:table
php artisan migrate

# Seed admin user
php artisan db:seed

# Start development server
php artisan serve

# In another terminal, start queue worker
php artisan queue:work
```

## Paymob Setup

1. Sign up at https://accept.paymob.com
2. Get your API key from Settings → API Keys
3. Create a payment integration and get Integration ID and Iframe ID
4. Set up webhook URL: `https://yourdomain.com/webhooks/paymob`
5. Get HMAC secret from Settings → Webhooks

## Testing

Run the test suite:

```bash
php artisan test
```

## Production Deployment

1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Use production Paymob credentials
3. Set up supervisor/systemd for queue worker
4. Configure webhook URL in Paymob dashboard
5. Ensure HTTPS is enabled

