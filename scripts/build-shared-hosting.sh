#!/usr/bin/env bash
set -euo pipefail

# Build Shared Hosting Package for Lakasir
# This script mirrors .github/workflows/release.yaml steps locally

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")"/.. && pwd)"
cd "$ROOT_DIR"

# Check required tools
require() {
  if ! command -v "$1" >/dev/null 2>&1; then
    echo "Error: required tool '$1' is not installed or not in PATH" >&2
    exit 1
  fi
}

# Optional tools: node/npm may not be present if you only want PHP part
require php
require composer

if command -v node >/dev/null 2>&1; then
  HAS_NODE=1
else
  HAS_NODE=0
fi

if command -v npm >/dev/null 2>&1; then
  HAS_NPM=1
else
  HAS_NPM=0
fi

# Clean bootstrap cache files that may cause issues
rm -f bootstrap/cache/packages.php || true
rm -f bootstrap/cache/services.php || true

# Install PHP dependencies (production)
COMPOSER_NO_INTERACTION=1 composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Install and build frontend assets if Node/NPM available
if [[ "$HAS_NODE" -eq 1 && "$HAS_NPM" -eq 1 ]]; then
  if [[ -f package-lock.json ]]; then
    npm ci
  else
    npm install --no-audit --no-fund
  fi
  npm run build
else
  echo "Skipping frontend build (node/npm not found)."
fi

# Create production env file and temporary build env
cp .env.example .env.production
cat > .env << 'EOF'
APP_NAME=Lakasir
APP_ENV=local
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=:memory:

CACHE_DRIVER=array
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
SESSION_LIFETIME=120

MAIL_MAILER=log

SELLING_METHOD=normal
SPA_MODE=false
DARK_MODE=false
EOF

# If openssl is available, generate a key for temporary .env
if command -v openssl >/dev/null 2>&1; then
  TMP_KEY="base64:$(openssl rand -base64 32)"
  sed -i.bak "s/^APP_KEY=.*/APP_KEY=${TMP_KEY}/" .env && rm -f .env.bak
fi

# Clear caches (avoid generating ones that hit DB)
php artisan config:clear --quiet || true
php artisan route:clear --quiet || true
php artisan view:clear --quiet || true
php artisan cache:clear --quiet || true
php artisan filament:cache-components --quiet || true
php artisan filament:clear-cached-components --quiet || true
php artisan icons:cache --quiet || true
php artisan icons:clear --quiet || true

# Remove temporary .env to avoid packaging it accidentally
rm -f .env

# Prepare shared hosting structure
rm -rf shared-hosting-release
mkdir -p shared-hosting-release/lakasir_app
mkdir -p shared-hosting-release/public_html

# Copy Laravel core to lakasir_app (exclude .git and node_modules)
cp -r app shared-hosting-release/lakasir_app/
cp -r bootstrap shared-hosting-release/lakasir_app/
cp -r config shared-hosting-release/lakasir_app/
cp -r database shared-hosting-release/lakasir_app/
cp -r lang shared-hosting-release/lakasir_app/
cp -r resources shared-hosting-release/lakasir_app/
cp -r routes shared-hosting-release/lakasir_app/
cp -r storage shared-hosting-release/lakasir_app/
cp -r vendor shared-hosting-release/lakasir_app/
cp artisan shared-hosting-release/lakasir_app/
cp composer.json shared-hosting-release/lakasir_app/
cp .env.production shared-hosting-release/lakasir_app/.env

# Clean possible cache files
rm -f shared-hosting-release/lakasir_app/bootstrap/cache/packages.php || true
rm -f shared-hosting-release/lakasir_app/bootstrap/cache/services.php || true
rm -f shared-hosting-release/lakasir_app/bootstrap/cache/config.php || true
rm -f shared-hosting-release/lakasir_app/bootstrap/cache/routes.php || true
rm -f shared-hosting-release/lakasir_app/bootstrap/cache/filament.php || true
rm -rf shared-hosting-release/lakasir_app/storage/framework/cache/filament || true

# Ensure storage directories
mkdir -p shared-hosting-release/lakasir_app/storage/framework/cache/data
mkdir -p shared-hosting-release/lakasir_app/storage/framework/cache/filament
mkdir -p shared-hosting-release/lakasir_app/storage/framework/sessions
mkdir -p shared-hosting-release/lakasir_app/storage/framework/views
mkdir -p shared-hosting-release/lakasir_app/storage/logs

touch shared-hosting-release/lakasir_app/storage/framework/cache/data/.gitkeep
touch shared-hosting-release/lakasir_app/storage/framework/cache/filament/.gitkeep
touch shared-hosting-release/lakasir_app/storage/framework/sessions/.gitkeep
touch shared-hosting-release/lakasir_app/storage/framework/views/.gitkeep
touch shared-hosting-release/lakasir_app/storage/logs/.gitkeep

# Copy full public (including built assets) into lakasir_app
cp -r public shared-hosting-release/lakasir_app/

# Public files for public_html
cp public/index.php shared-hosting-release/public_html/
cp public/.htaccess shared-hosting-release/public_html/ 2>/dev/null || true

# Copy some top-level public files
find public -maxdepth 1 -type f \( -name "*.ico" -o -name "*.txt" -o -name "*.js" \) -exec cp {} shared-hosting-release/public_html/ \; 2>/dev/null || true

# Copy public directories except build to public_html
for dir in public/*/; do
  dirname=$(basename "$dir")
  if [[ "$dirname" != "build" ]]; then
    cp -r "$dir" shared-hosting-release/public_html/ 2>/dev/null || true
  fi
done

# Create manual storage symlink replacement for shared hosting
mkdir -p shared-hosting-release/public_html/storage
cat > shared-hosting-release/public_html/storage/index.php << 'PHP'
<?php
// Manual storage link for shared hosting (no exec())
$requestPath = $_SERVER['REQUEST_URI'];
$path = parse_url($requestPath, PHP_URL_PATH);
$storagePath = ltrim(str_replace('/storage', '', $path), '/');
$filePath = __DIR__ . '/../../lakasir_app/storage/app/public/' . $storagePath;
if (!file_exists($filePath) || !is_file($filePath)) {
    http_response_code(404);
    exit('File not found');
}
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $filePath);
finfo_close($finfo);
header('Content-Type: ' . $mimeType);
header('Content-Length: ' . filesize($filePath));
readfile($filePath);
exit;
PHP

cat > shared-hosting-release/public_html/storage/.htaccess << 'APACHE'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
APACHE

# Create modified index.php for shared hosting
cat > shared-hosting-release/public_html/index.php << 'PHP'
<?php
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));
if (file_exists($maintenance = __DIR__.'/../lakasir_app/storage/framework/maintenance.php')) {
    require $maintenance;
}
require __DIR__.'/../lakasir_app/vendor/autoload.php';
(require_once __DIR__.'/../lakasir_app/bootstrap/app.php')->handleRequest(Request::capture());
PHP

# Create .htaccess for shared hosting
cat > shared-hosting-release/public_html/.htaccess << 'APACHE'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>
    RewriteEngine On
    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]
    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
APACHE

# Create archive
(
  cd shared-hosting-release
  tar -czf ../lakasir-shared-hosting.tar.gz .
)

# Create installation instructions
cat > INSTALLATION.md << 'MD'
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
MD

echo "Created artifact: lakasir-shared-hosting.tar.gz"
