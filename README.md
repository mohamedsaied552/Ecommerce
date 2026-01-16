# Laravel Invoice System with Paymob Integration

A production-ready Laravel 10 application for creating invoices, accepting online payments via Paymob (Accept), and managing invoice payments. The system sends email notifications and handles payment webhooks securely.

## Features

- ✅ Admin dashboard for invoice management
- ✅ Create invoices with customer information
- ✅ Generate unique payment links for each invoice
- ✅ Paymob (Accept) payment gateway integration
- ✅ Secure webhook handling with HMAC verification
- ✅ Email notifications (invoice creation, payment confirmation)
- ✅ Idempotent payment processing
- ✅ Queue-based email sending
- ✅ Admin authentication
- ✅ Payment history tracking

## Requirements

- PHP 8.2 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Node.js & NPM (for asset compilation, optional)
- Paymob account with API credentials

## Installation

### 1. Clone and Install Dependencies

```bash
composer install
```

### 2. Environment Configuration

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 3. Database Setup

Update your `.env` file with database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run migrations:

```bash
php artisan migrate
```

### 4. Paymob Configuration

Add your Paymob credentials to `.env`:

```env
# Paymob Configuration
PAYMOB_API_KEY=your_api_key_here
PAYMOB_INTEGRATION_ID=your_integration_id_here
PAYMOB_IFRAME_ID=your_iframe_id_here
PAYMOB_HMAC_SECRET=your_hmac_secret_here
```

**How to get Paymob credentials:**

1. **API Key**: Log in to your Paymob dashboard → Settings → API Keys
2. **Integration ID**: Go to Settings → Payment Integrations → Select your integration → Copy the Integration ID
3. **Iframe ID**: Go to Settings → Payment Integrations → Select your integration → Copy the Iframe ID
4. **HMAC Secret**: Go to Settings → Webhooks → Copy the HMAC Secret

**Important:** 
- For testing, use Paymob's sandbox environment
- For production, ensure you use production credentials
- The webhook URL should be: `https://yourdomain.com/webhooks/paymob`

### 5. Mail Configuration

Configure SMTP settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

**For local development with Mailhog:**

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

### 6. Queue Configuration

For email sending, configure queues in `.env`:

```env
QUEUE_CONNECTION=database
```

Create the jobs table:

```bash
php artisan queue:table
php artisan migrate
```

### 7. Admin User Setup

Set admin credentials in `.env`:

```env
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=your_secure_password
```

Seed the admin user:

```bash
php artisan db:seed
```

### 8. Start the Application

Start the development server:

```bash
php artisan serve
```

In a separate terminal, start the queue worker:

```bash
php artisan queue:work
```

The application will be available at `http://localhost:8000`

## Usage

### Admin Login

1. Navigate to `/login`
2. Use the admin credentials configured in `.env`
3. Access the dashboard at `/admin/dashboard`

### Creating an Invoice

1. Go to **Invoices** → **New Invoice**
2. Fill in:
   - Amount (in EGP)
   - Description (optional)
   - Customer information (optional)
3. Click **Create Invoice**
4. The system will:
   - Generate a unique invoice number
   - Create a payment link
   - Send an email to the customer (if email provided)

### Payment Flow

1. Customer receives invoice email with payment link
2. Customer clicks the link: `https://yourdomain.com/i/{token}`
3. Customer clicks **Pay Now**
4. Customer is redirected to Paymob checkout
5. Customer completes payment
6. Paymob sends webhook to `/webhooks/paymob`
7. System:
   - Verifies webhook HMAC signature
   - Updates invoice status to "paid"
   - Sends confirmation emails to admin and customer
   - Logs payment details

### Important Notes

- **Webhook is authoritative**: Payment status is determined by webhook, not redirect URLs
- **Idempotency**: The system prevents duplicate payments
- **Email delivery**: Digital codes are delivered manually via chat (not automated)

## Deployment

### Production Checklist

1. **Environment Variables**
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Use production Paymob credentials
   - Configure production SMTP

2. **Queue Worker**
   - Set up a supervisor or systemd service for `php artisan queue:work`
   - Or use a cron job: `* * * * * cd /path-to-project && php artisan schedule:run`

3. **Webhook URL**
   - Configure in Paymob dashboard: `https://yourdomain.com/webhooks/paymob`
   - Ensure HTTPS is enabled
   - Test webhook delivery

4. **Database**
   - Use production database
   - Run migrations: `php artisan migrate --force`

5. **Permissions**
   - Set proper file permissions: `storage` and `bootstrap/cache` should be writable

### Nginx Configuration Example

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Supervisor Configuration for Queue Worker

Create `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/worker.log
stopwaitsecs=3600
```

Then run:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

## Testing Checklist

### Manual Testing Flow

1. **Create Invoice**
   - [ ] Login as admin
   - [ ] Create a new invoice
   - [ ] Verify invoice appears in list
   - [ ] Check email sent to customer (if email provided)

2. **Payment Process**
   - [ ] Open payment link
   - [ ] Click "Pay Now"
   - [ ] Complete payment on Paymob
   - [ ] Verify redirect to success page

3. **Webhook Processing**
   - [ ] Check logs for webhook receipt
   - [ ] Verify invoice status updated to "paid"
   - [ ] Check payment record created
   - [ ] Verify admin email received
   - [ ] Verify customer email received

4. **Idempotency**
   - [ ] Try to pay the same invoice twice
   - [ ] Verify duplicate payment prevented

5. **Error Handling**
   - [ ] Test with invalid webhook signature
   - [ ] Test with expired invoice
   - [ ] Test with already paid invoice

## Security Features

- ✅ CSRF protection on all forms
- ✅ HMAC signature verification for webhooks
- ✅ Idempotent payment processing
- ✅ Rate limiting on public endpoints
- ✅ Secure password hashing
- ✅ SQL injection protection (Eloquent ORM)
- ✅ XSS protection (Blade templating)

## Troubleshooting

### Emails Not Sending

1. Check queue worker is running: `php artisan queue:work`
2. Check mail configuration in `.env`
3. Check `storage/logs/laravel.log` for errors
4. For Mailhog: Ensure it's running on port 1025

### Webhook Not Working

1. Check webhook URL in Paymob dashboard
2. Verify HMAC secret matches
3. Check `storage/logs/laravel.log` for webhook errors
4. Ensure webhook endpoint is accessible (no CSRF protection)

### Payment Not Updating

1. Check webhook logs
2. Verify invoice exists with correct invoice number
3. Check payment status in database
4. Verify HMAC verification is passing

## API Endpoints

### Public Endpoints

- `GET /i/{token}` - View invoice
- `POST /i/{token}/pay` - Initiate payment
- `GET /success` - Payment success page
- `GET /cancel` - Payment cancel page

### Webhook Endpoints

- `POST /webhooks/paymob` - Paymob webhook handler

### Admin Endpoints (Authenticated)

- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/invoices` - List invoices
- `GET /admin/invoices/create` - Create invoice form
- `POST /admin/invoices` - Store invoice
- `GET /admin/invoices/{id}` - View invoice
- `POST /admin/invoices/{id}/resend-email` - Resend invoice email
- `POST /admin/invoices/{id}/mark-expired` - Mark invoice as expired

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues and questions:
1. Check the logs: `storage/logs/laravel.log`
2. Review Paymob documentation
3. Check Laravel documentation

## Development Notes

- Queue driver: Use `database` for MVP, upgrade to Redis for production
- Email: Use Mailhog for local development
- Logging: All Paymob webhooks are logged for audit
- Testing: Add unit tests for critical paths (invoice creation, webhook verification)
