# Fix for CORS Asset Loading Issue

## Problem
The production site at `https://expansly.app` is trying to load JavaScript assets from `https://expensly-krg5.onrender.com`, causing CORS errors and breaking Alpine.js functionality (mobile menu and dark/light mode toggle).

## Root Cause
Laravel Vite plugin is generating asset URLs using the Render deployment URL instead of the custom domain.

## Solution Steps

### 1. Environment Variables (Critical)
In your **Render Dashboard**, add/update these environment variables:

```bash
APP_URL=https://expansly.app
ASSET_URL=https://expansly.app
APP_ENV=production
NODE_ENV=production
```

### 2. Clear All Caches
After updating environment variables, run these commands in your Render deployment:

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### 3. Rebuild Assets
Force a complete rebuild of your assets:

```bash
npm ci
NODE_ENV=production npm run build
```

### 4. Cache Configuration
After rebuilding, cache the configuration:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Files Modified

### 1. `config/app.php`
- Added `asset_url` configuration

### 2. `app/Providers/AppServiceProvider.php`
- Added asset URL configuration for production

### 3. `vite.config.js`
- Cleaned up configuration for better compatibility

### 4. `.env.example`
- Added `ASSET_URL` variable

## Deployment Script
Use the provided `deploy.sh` script for consistent deployments.

## Expected Results
After implementing these changes:
- ✅ Assets will load from `https://expansly.app`
- ✅ No more CORS errors
- ✅ Mobile menu will work properly
- ✅ Dark/light mode toggle will function correctly
- ✅ Only one theme icon will show at a time

## Testing
1. Clear browser cache
2. Test on both domains:
   - `https://expansly.app`
   - `https://expensly-krg5.onrender.com`
3. Check browser console for errors
4. Test mobile menu functionality
5. Test dark/light mode toggle

## Troubleshooting
If issues persist:
1. Check browser Network tab to see actual asset URLs
2. Verify environment variables are set correctly in Render
3. Ensure deployment completed successfully
4. Check Laravel logs for any errors
