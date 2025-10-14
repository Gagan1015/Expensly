# Database Persistence on Render

## Important: Configure Persistent Disk

To prevent database loss on each deployment, you need to configure a persistent disk in Render:

### Steps to Add Persistent Disk:

1. Go to your Render service dashboard
2. Click on your "expensly" service
3. Go to the **"Disks"** tab
4. Click **"Add Disk"**
5. Configure:
   - **Name**: `expensly-database`
   - **Mount Path**: `/var/www/html/database`
   - **Size**: 1 GB (free tier allows up to 1GB)
6. Click **"Save"**
7. Redeploy your service

### What This Does:

- The SQLite database file will be stored on persistent disk
- Database survives deployments and restarts
- Your user accounts and data won't be lost

### Without Persistent Disk:

- Database is recreated on every deployment
- All user data is lost
- Only works for testing/demo purposes

### Login Credentials (After First Deployment):

**Admin:**
- Email: `admin@expensly.com`
- Password: `admin123`

**Demo User:**
- Email: `demo@expensly.com`
- Password: `demo123`

These accounts are created automatically on the first deployment only.
