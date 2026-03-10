# Lakasir - Shared Hosting Installation Guide

## Requirements
- PHP 8.2 or higher
- MySQL 5.7 or higher
- Required PHP extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## Package Structure
After extracting `lakasir-shared-hosting.tar.gz`, you'll get:
```
lakasir-shared-hosting/
├── lakasir_app/          # Laravel core files (private)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── vendor/
│   ├── storage/
│   ├── public/           # Complete public folder with build assets
│   │   └── build/        # Compiled CSS/JS assets (Vite manifest here)
│   ├── .env              # Your environment file
│   └── artisan
└── public_html/          # Public web files (no build folder)
    ├── index.php         # Modified for shared hosting
    ├── .htaccess         # Optimized for shared hosting
    └── assets/           # Images, icons, etc.
```

## Installation Steps

### 1. Upload Files
- Extract `lakasir-shared-hosting.tar.gz` on your computer
- Upload `lakasir_app/` folder to your hosting account root (outside public_html)
- Upload contents of `public_html/` folder to your domain's public folder (usually `public_html` or `www`)

**Final server structure should look like:**
```
/home/yourusername/
├── lakasir_app/          # Upload here (private)
└── public_html/          # Upload public_html contents here
    ├── index.php
    ├── .htaccess
    └── build/
```

### 2. Configure Environment
- Edit `lakasir_app/.env` file with your database credentials:
```
APP_NAME=Lakasir
APP_ENV=production
APP_KEY=base64:YOUR_32_CHAR_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```
- Generate APP_KEY:
  - Option 1: Use online Laravel key generator (search "Laravel key generator")
  - Option 2: If your hosting supports PHP CLI: `cd lakasir_app && php artisan key:generate`
  - Option 3: Generate manually: `base64:` + 32 random characters

### 3. Set File Permissions (via cPanel File Manager)
- `lakasir_app/storage/` and all subdirectories: 755
- `lakasir_app/bootstrap/cache/`: 755
- All files in above directories: 644

Important directories to set permissions:
- `lakasir_app/storage/framework/cache/`
- `lakasir_app/storage/framework/cache/filament/`
- `lakasir_app/storage/framework/sessions/`
- `lakasir_app/storage/framework/views/`
- `lakasir_app/storage/logs/`

### 4. Database Setup
- Create a MySQL database through your hosting control panel (cPanel > MySQL Databases)
- Import your database via phpMyAdmin, or if CLI is available:
```
cd lakasir_app
php artisan migrate --force
php artisan db:seed --force
```

### 5. Verify Installation
- Visit your domain - you should see Lakasir running
- If you get 500 errors, check file permissions and `.env` configuration
- If styles are missing, ensure `public_html/build/` folder contains CSS/JS files

## What's Pre-Configured
- index.php - Modified to work with shared hosting structure
- .htaccess - Optimized for shared hosting
- Storage link - Manual storage link (no `artisan storage:link` needed)
- Production dependencies - No need to run composer install
- Built assets - CSS/JS compiled and placed in `lakasir_app/public/build/`
- Vite manifest - Located at `lakasir_app/public/build/manifest.json`
- Cached routes/config - For better performance

## Troubleshooting

Common Issues:
- 500 Internal Server Error: Check file permissions (755 for folders, 644 for files)
- Database Connection Error: Verify credentials in `lakasir_app/.env`
- Missing Styles/JS: Ensure `lakasir_app/public/build/` folder exists with compiled assets
- Vite manifest not found: Verify `lakasir_app/public/build/manifest.json` exists
- Storage files not accessible: Storage link is pre-configured in `public_html/storage/`
- "exec() disabled" error: Storage link uses manual PHP script instead of artisan command
- Filament components not loading: Check `lakasir_app/storage/framework/cache/filament/` permissions
- Icons not displaying: Ensure icon cache can be written to storage directory
- App Key Error: Generate a valid APP_KEY in your `.env` file
- Routes not working: Verify `.htaccess` file exists in `public_html/`

File Paths Issues:
If you placed files differently, edit `public_html/index.php` and update these lines:
```
require __DIR__.'/../lakasir_app/vendor/autoload.php';
(require_once __DIR__.'/../lakasir_app/bootstrap/app.php')
```

Support: https://github.com/sheenazien8/lakasir/issues
