# Heroku Deployment Guide for Expensly

Complete step-by-step guide to deploy your Laravel application to Heroku.

## Prerequisites

1. **Heroku Account**: Sign up at https://signup.heroku.com/
2. **Heroku CLI**: Download and install from https://devcenter.heroku.com/articles/heroku-cli
3. **Git**: Ensure Git is installed on your system

## Step 1: Install Heroku CLI

1. Download Heroku CLI from: https://devcenter.heroku.com/articles/heroku-cli
2. Install it on your system
3. Verify installation by opening a new terminal and running:
   ```bash
   heroku --version
   ```

## Step 2: Login to Heroku

Open your terminal (PowerShell) and run:
```bash
heroku login
```
This will open a browser window for you to log in.

## Step 3: Initialize Git Repository (if not already done)

```bash
cd c:\Users\admin\OneDrive\Documents\expensly
git init
git add .
git commit -m "Initial commit for Heroku deployment"
```

## Step 4: Create Heroku Application

```bash
heroku create your-app-name
```
Replace `your-app-name` with your desired app name (must be unique across Heroku).
If you want Heroku to generate a random name, just run:
```bash
heroku create
```

## Step 5: Add Database to Heroku

For MySQL (recommended for Laravel):
```bash
heroku addons:create jawsdb:kitefin
```

**OR** for PostgreSQL (free tier):
```bash
heroku addons:create heroku-postgresql:essential-0
```

**Note**: JawsDB MySQL has a free tier with 5MB storage. For more storage, you'll need a paid plan.

## Step 6: Set Environment Variables

Set all required Laravel environment variables:

```bash
# Generate and set APP_KEY
heroku config:set APP_KEY=$(php artisan key:generate --show)

# Basic Laravel settings
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set LOG_CHANNEL=errorlog

# Session and cache
heroku config:set SESSION_DRIVER=cookie
heroku config:set CACHE_STORE=file
heroku config:set QUEUE_CONNECTION=sync
```

**Get your app URL** (after creation):
```bash
heroku info
```
Then set the APP_URL:
```bash
heroku config:set APP_URL=https://your-app-name.herokuapp.com
```

## Step 7: Configure Buildpacks

Heroku needs to know you're using both Node.js (for frontend assets) and PHP:

```bash
heroku buildpacks:add --index 1 heroku/nodejs
heroku buildpacks:add --index 2 heroku/php
```

Verify buildpacks:
```bash
heroku buildpacks
```

## Step 8: Add Post-Deploy Scripts to composer.json

Your `composer.json` should include these scripts (already configured):
```json
"scripts": {
    "post-install-cmd": [
        "php artisan clear-compiled",
        "php artisan optimize"
    ],
    "post-autoload-dump": [
        "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
        "@php artisan package:discover --ansi"
    ]
}
```

## Step 9: Deploy to Heroku

```bash
git add .
git commit -m "Configure for Heroku deployment"
git push heroku main
```

**If your branch is named `master`** instead of `main`:
```bash
git push heroku master
```

## Step 10: Run Database Migrations

After deployment, run migrations:
```bash
heroku run php artisan migrate --force
```

Optionally, seed the database:
```bash
heroku run php artisan db:seed --force
```

## Step 11: Open Your Application

```bash
heroku open
```

This will open your deployed app in the browser!

## Step 12: Monitor Your Application

View logs in real-time:
```bash
heroku logs --tail
```

Check dyno status:
```bash
heroku ps
```

View configuration:
```bash
heroku config
```

## Common Commands

### View logs
```bash
heroku logs --tail
```

### Restart application
```bash
heroku restart
```

### Run artisan commands
```bash
heroku run php artisan [command]
```

### Access database console
For PostgreSQL:
```bash
heroku pg:psql
```

For MySQL (JawsDB):
```bash
heroku config:get JAWSDB_URL
# Use the URL with a MySQL client
```

### Scale dynos
```bash
heroku ps:scale web=1
```

## Troubleshooting

### 1. Application Error (500)
Check logs:
```bash
heroku logs --tail
```

### 2. Database Connection Issues
Verify database credentials:
```bash
heroku config
```

For JawsDB MySQL, manually set database config:
```bash
# Get JAWSDB_URL
heroku config:get JAWSDB_URL
# It will be in format: mysql://username:password@hostname:port/database

# Set individual values
heroku config:set DB_CONNECTION=mysql
heroku config:set DB_HOST=hostname
heroku config:set DB_PORT=port
heroku config:set DB_DATABASE=database
heroku config:set DB_USERNAME=username
heroku config:set DB_PASSWORD=password
```

### 3. Assets Not Loading
Make sure you've built assets before deploying:
```bash
npm run build
git add public/build
git commit -m "Add built assets"
git push heroku main
```

### 4. Permission Errors
Laravel needs write access to `storage` and `bootstrap/cache`:
These are handled automatically by Heroku's PHP buildpack.

### 5. APP_KEY Not Set
```bash
heroku config:set APP_KEY=base64:$(openssl rand -base64 32)
```

## Environment Variables Checklist

Make sure these are set:
- ✅ APP_KEY
- ✅ APP_ENV=production
- ✅ APP_DEBUG=false
- ✅ APP_URL
- ✅ DB_CONNECTION
- ✅ DB_HOST
- ✅ DB_PORT
- ✅ DB_DATABASE
- ✅ DB_USERNAME
- ✅ DB_PASSWORD

View all config:
```bash
heroku config
```

## Optional: Add Monitoring (Addressing Health Check Warnings)

### Add Logging (Papertrail - Free tier available)
```bash
heroku addons:create papertrail:choklad
```

View logs:
```bash
heroku addons:open papertrail
```

### Add Monitoring (New Relic - Free tier available)
```bash
heroku addons:create newrelic:wayne
```

### Scale to Multiple Dynos (for redundancy)
```bash
heroku ps:scale web=2
```
**Note**: This requires a paid plan.

## Updating Your Application

After making changes:
```bash
git add .
git commit -m "Your commit message"
git push heroku main
```

Run migrations if database changed:
```bash
heroku run php artisan migrate --force
```

## Cost Optimization

**Free Tier Limits**:
- 1 free dyno (sleeps after 30 min of inactivity)
- 550-1000 dyno hours per month
- Limited database storage

**Upgrade Options**:
- Basic dynos: $7/month (no sleep)
- Standard dynos: $25-50/month (better performance + metrics)
- Database upgrades for more storage

## Support

- Heroku Dev Center: https://devcenter.heroku.com/
- Heroku Status: https://status.heroku.com/
- Laravel Deployment Docs: https://laravel.com/docs/deployment

## Quick Reference Card

```bash
# Deploy
git push heroku main

# View logs
heroku logs --tail

# Run artisan
heroku run php artisan [command]

# Restart app
heroku restart

# Open app
heroku open

# Config vars
heroku config
heroku config:set KEY=value

# Database
heroku run php artisan migrate --force
heroku run php artisan db:seed --force
```

---

**You're now ready to deploy! Follow the steps sequentially, and your app will be live on Heroku.**
