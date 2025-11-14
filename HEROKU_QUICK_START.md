# Quick Start - Heroku Deployment

## 🚀 Run These Commands in Order

### 1. Login to Heroku
```bash
heroku login
```

### 2. Create Your App
```bash
heroku create
```
_Or with a specific name:_
```bash
heroku create your-app-name
```

### 3. Add Database (Choose ONE)

**Option A: PostgreSQL (Free)**
```bash
heroku addons:create heroku-postgresql:essential-0
```

**Option B: MySQL**
```bash
heroku addons:create jawsdb:kitefin
```

### 4. Set Buildpacks
```bash
heroku buildpacks:add --index 1 heroku/nodejs
heroku buildpacks:add --index 2 heroku/php
```

### 5. Configure Environment Variables

**Important: Replace `your-app-name` with your actual Heroku app name!**

```bash
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set APP_URL=https://your-app-name.herokuapp.com
heroku config:set LOG_CHANNEL=errorlog
heroku config:set SESSION_DRIVER=cookie
heroku config:set CACHE_STORE=file
heroku config:set QUEUE_CONNECTION=sync
```

**Generate APP_KEY:**
```bash
php artisan key:generate --show
```
Copy the output and run:
```bash
heroku config:set APP_KEY=base64:YourGeneratedKeyHere
```

### 6. Configure Database (If using MySQL/JawsDB)

```bash
heroku config:get JAWSDB_URL
```

This will show URL like: `mysql://username:password@hostname:port/database`

Extract values and set:
```bash
heroku config:set DB_CONNECTION=mysql
heroku config:set DB_HOST=hostname_from_url
heroku config:set DB_PORT=port_from_url
heroku config:set DB_DATABASE=database_from_url
heroku config:set DB_USERNAME=username_from_url
heroku config:set DB_PASSWORD=password_from_url
```

**For PostgreSQL, Heroku sets DATABASE_URL automatically**

### 7. Deploy!
```bash
git add .
git commit -m "Ready for Heroku deployment"
git push heroku main
```

_If using master branch:_
```bash
git push heroku master
```

### 8. Run Migrations
```bash
heroku run php artisan migrate --force
```

### 9. Seed Database (Optional)
```bash
heroku run php artisan db:seed --force
```

### 10. Open Your App! 🎉
```bash
heroku open
```

---

## 📋 Verification Checklist

- [ ] Heroku CLI installed
- [ ] Logged into Heroku
- [ ] App created on Heroku
- [ ] Database addon added
- [ ] Buildpacks configured
- [ ] Environment variables set (especially APP_KEY)
- [ ] Database credentials configured
- [ ] Code pushed to Heroku
- [ ] Migrations run
- [ ] App opens successfully

---

## 🆘 If Something Goes Wrong

**View logs:**
```bash
heroku logs --tail
```

**Check config:**
```bash
heroku config
```

**Restart app:**
```bash
heroku restart
```

**Re-run migrations:**
```bash
heroku run php artisan migrate:fresh --force
heroku run php artisan db:seed --force
```

---

## 📞 Need Help?

See the full guide: `HEROKU_DEPLOYMENT_GUIDE.md`
