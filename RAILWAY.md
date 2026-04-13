# Railway Deployment Guide

## Environment Variables

Set these in Railway Dashboard → Variables:

```env
APP_NAME=Sonsun-Rental
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}

# PostgreSQL - Use Railway's built-in variables
DB_CONNECTION=pgsql
DB_HOST=${{Postgres.PGHOST}}
DB_PORT=${{Postgres.PGPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

## Generate APP_KEY

Run locally:
```bash
php artisan key:generate --show
```

Copy the output and paste to Railway's APP_KEY variable.

## Deploy Steps

1. Connect Railway to this GitHub repository
2. Add PostgreSQL database service
3. Set environment variables above
4. Railway will auto-deploy on push to main branch

## Database Migration

Migration will run automatically on deploy via Procfile.

## Security Note

⚠️ **NEVER commit `.env` or `.env.railway` files to Git!**
- All sensitive credentials should be set via Railway Dashboard
- Use Railway's reference variables like `${{Postgres.PGHOST}}`
