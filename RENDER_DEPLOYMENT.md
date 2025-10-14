# Database Persistence on Render - FREE Solution

## ⚠️ Important: Free Tier Limitation

Render's **free tier does NOT support persistent disks**. You have two options:

## Option 1: Free PostgreSQL Database (RECOMMENDED ✅)

Use Render's **free PostgreSQL database** - your data persists across deployments!

### Steps to Add Free PostgreSQL:

1. **Create PostgreSQL Database**
   - In Render Dashboard, click **"New +"** → **"PostgreSQL"**
   - Name: `expensly-db`
   - Database: `expensly`
   - User: (auto-generated)
   - Region: Same as your web service
   - Plan: **Free**
   - Click **"Create Database"**

2. **Connect Database to Your Service**
   - Go to your **"Expensly"** web service
   - Click **"Environment"** tab
   - Click **"Add Environment Variable"**
   - The PostgreSQL database will show under "Add from Database"
   - Click your database name to auto-populate `DATABASE_URL`
   - Click **"Save Changes"**

3. **Redeploy**
   - Go to **"Manual Deploy"** → **"Clear build cache & deploy"**
   - Wait for deployment to complete

### What You Get with PostgreSQL:

✅ **FREE** - No cost
✅ **Persistent** - Data survives deployments
✅ **Automatic backups** - Daily backups included
✅ **Better performance** - More suitable for production
✅ **No storage limit** on free tier

---

## Option 2: Upgrade to Starter Plan ($7/month)

If you prefer SQLite with persistent disk:

1. Upgrade to **Starter plan** ($7/month)
2. Add persistent disk at `/var/www/html/database`
3. Keep using SQLite

---

## Login Credentials (After First Deployment):

**Admin:**
- Email: `admin@expensly.com`
- Password: `admin123`

**Demo User:**
- Email: `demo@expensly.com`
- Password: `demo123`

These accounts are created automatically on the first deployment only.

---

## Current Setup:

The application now supports **BOTH** SQLite and PostgreSQL:
- If `DATABASE_URL` environment variable exists → Uses PostgreSQL
- If not → Falls back to SQLite (data lost on each deploy on free tier)
