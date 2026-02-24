# 🚀 Monitorly — Website Monitoring & Incident Management

A futuristic, real-time website monitoring system built with **Laravel**. Track your websites' uptime, get instant email alerts, manage incidents, and share public status pages.

![License](https://img.shields.io/badge/license-MIT-green)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![Laravel](https://img.shields.io/badge/Laravel-11-red)

---

## ✨ Features

| Feature | Description |
|---------|-------------|
| 📡 **Real-time Monitoring** | Check websites at configurable intervals (1–60 min) |
| ⚡ **Instant First Check** | No "Pending" — monitors are checked immediately on creation |
| 🔄 **Check Now** | Manual check button for on-demand testing |
| 📧 **Email Alerts** | Beautiful HTML emails for downtime & recovery |
| 🎫 **Issue Tracking** | Auto-created incident tickets for every downtime |
| 📊 **Response Time Charts** | Visual bar charts showing response time history |
| 🌐 **Public Status Page** | Shareable page showing all monitors' health |
| 📥 **CSV Export** | Export issues/incidents as CSV files |
| 🔄 **Auto-Refresh** | Dashboard auto-refreshes stats every 30 seconds |
| 🌍 **Bilingual** | Full Arabic & English UI with RTL support |
| 🎨 **Futuristic Design** | Dark theme with neon accents, Matrix effects |
| 📱 **Responsive** | Works on desktop, tablet, and mobile |

---

## 🛠 Tech Stack

- **Backend:** PHP 8.2+ / Laravel 11
- **Database:** MySQL 8.0+
- **Queue:** Laravel Jobs (Sync/Database/Redis)
- **Mail:** SMTP (Mailtrap, Mailgun, Gmail, etc.)
- **Frontend:** Blade Templates + Vanilla CSS + JavaScript
- **Fonts:** Orbitron, Inter, JetBrains Mono (Google Fonts)
- **CI/CD:** GitHub Actions

---

## 📦 Installation (Local Development)

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js (optional, for Vite assets)

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/Sha3booony/monyterly.git
cd monyterly

# 2. Install dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure your .env file (see below)

# 6. Create the database
mysql -u root -e "CREATE DATABASE monitorly CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 7. Run migrations
php artisan migrate

# 8. Start the development server
php artisan serve
```

Your app will be available at: `http://127.0.0.1:8000`

---

## ⚙️ Configuration (.env)

### Database
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=monitorly
DB_USERNAME=root
DB_PASSWORD=
```

### SMTP Email (Required for notifications)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io        # Or your SMTP provider
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=alerts@monitorly.dev
MAIL_FROM_NAME="Monitorly Alerts"
```

#### Popular SMTP Providers:
| Provider | Host | Port |
|----------|------|------|
| Mailtrap | smtp.mailtrap.io | 587 |
| Gmail | smtp.gmail.com | 587 |
| Mailgun | smtp.mailgun.org | 587 |
| SendGrid | smtp.sendgrid.net | 587 |
| Outlook | smtp.office365.com | 587 |

---

## ⏰ Cron Jobs (CRITICAL for WHM/cPanel)

### Main Scheduler Cron Job

This is **the most important step** for the monitoring to work. You need to add ONE cron job that runs every minute:

#### Option 1: cPanel → Cron Jobs

Go to **cPanel → Cron Jobs** and add:

```
* * * * * /opt/cpanel/ea-php84/root/usr/bin/php /home/YOUR_USERNAME/public_html/monitorly.sha3booony.dev/artisan schedule:run >> /dev/null 2>&1
```

> ⚠️ Replace `YOUR_USERNAME` with your cPanel username.
> ⚠️ Replace the PHP path if your server uses a different PHP version.

#### Option 2: WHM → Cron Jobs

If you have WHM root access:

```bash
# Edit crontab
crontab -e

# Add this line:
* * * * * /opt/cpanel/ea-php84/root/usr/bin/php /home/YOUR_USERNAME/public_html/monitorly.sha3booony.dev/artisan schedule:run >> /dev/null 2>&1
```

#### Option 3: Local Development

```bash
# Run the scheduler continuously (keeps running)
php artisan schedule:work

# Or run once manually
php artisan schedule:run
```

### What the Scheduler Does

The scheduler runs the `monitors:check` command every minute which:
1. Finds all active monitors whose check interval has elapsed
2. Dispatches a `CheckMonitorJob` for each monitor
3. Each job:
   - Makes an HTTP request to the monitored URL
   - Logs the response (status, time, errors)
   - If site went **DOWN** → Creates an Issue ticket + Sends alert email
   - If site came **UP** → Resolves open issues + Sends recovery email
   - Updates uptime percentage

### Verify the Cron Job is Working

```bash
# Check if the scheduler runs correctly
/opt/cpanel/ea-php84/root/usr/bin/php /home/YOUR_USERNAME/public_html/monitorly.sha3booony.dev/artisan schedule:run

# Run monitors check manually
/opt/cpanel/ea-php84/root/usr/bin/php /home/YOUR_USERNAME/public_html/monitorly.sha3booony.dev/artisan monitors:check

# Check scheduled tasks
/opt/cpanel/ea-php84/root/usr/bin/php /home/YOUR_USERNAME/public_html/monitorly.sha3booony.dev/artisan schedule:list
```

---

## 🚀 Server Deployment (WHM/cPanel)

### 1. Upload Files

Upload the project to your server via Git or FTP:
```
/home/YOUR_USERNAME/public_html/monitorly.sha3booony.dev/
```

### 2. Set Document Root

In cPanel, point your domain's **Document Root** to:
```
/home/YOUR_USERNAME/public_html/monitorly.sha3booony.dev/public
```

> ⚠️ Laravel's entry point is the `public/` folder, NOT the root.

### 3. File Permissions

```bash
# Storage and cache must be writable
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Make sure ownership is correct
chown -R YOUR_USERNAME:YOUR_USERNAME .
```

### 4. Environment Setup

```bash
# Copy .env and configure it
cp .env.example .env

# Generate app key
/opt/cpanel/ea-php84/root/usr/bin/php artisan key:generate

# Edit .env with production values
nano .env
```

**Important `.env` changes for production:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://monitorly.sha3booony.dev
```

### 5. Install Dependencies

```bash
/usr/local/bin/composer install --no-dev --optimize-autoloader
```

### 6. Run Migrations

```bash
/opt/cpanel/ea-php84/root/usr/bin/php artisan migrate --force
```

### 7. Cache Configuration

```bash
/opt/cpanel/ea-php84/root/usr/bin/php artisan config:cache
/opt/cpanel/ea-php84/root/usr/bin/php artisan route:cache
/opt/cpanel/ea-php84/root/usr/bin/php artisan view:cache
```

### 8. Set Up Cron Job

Add the cron job as described in the **Cron Jobs** section above.

### 9. Set Up SSL (Recommended)

Use cPanel's **AutoSSL** or Let's Encrypt to enable HTTPS.

---

## 🔄 CI/CD (GitHub Actions)

The project includes a GitHub Actions workflow (`.github/workflows/deploy.yml`) that automatically deploys on push to `main`.

### Required GitHub Secrets

Go to **GitHub → Repository → Settings → Secrets** and add:

| Secret Name | Description | Example |
|-------------|-------------|---------|
| `SERVER_HOST` | Your server IP or hostname | `123.456.789.0` |
| `SERVER_USER` | SSH username | `sha3booony` |
| `SSH_PRIVATE_KEY` | Your SSH private key | (contents of `~/.ssh/id_rsa`) |
| `GH_PAT` | GitHub Personal Access Token | `ghp_xxxx...` |

### Generate SSH Key (if needed)

```bash
# On your LOCAL machine
ssh-keygen -t rsa -b 4096 -C "deploy@monitorly"

# Copy the PUBLIC key to your server
ssh-copy-id -i ~/.ssh/id_rsa.pub YOUR_USERNAME@YOUR_SERVER_IP

# The PRIVATE key content goes into GitHub Secrets as SSH_PRIVATE_KEY
cat ~/.ssh/id_rsa
```

---

## 📁 Project Structure

```
monitorly/
├── app/
│   ├── Console/Commands/
│   │   └── RunMonitorChecks.php    # Artisan command to check monitors
│   ├── Http/Controllers/
│   │   ├── AuthController.php      # Login/Register/Logout
│   │   ├── DashboardController.php # Dashboard overview + API
│   │   ├── MonitorController.php   # CRUD + CheckNow + Export
│   │   ├── IssueController.php     # Issue management
│   │   ├── StatusPageController.php # Public status page
│   │   └── LanguageController.php  # Language switcher
│   ├── Jobs/
│   │   └── CheckMonitorJob.php     # Core monitoring logic
│   ├── Mail/
│   │   ├── MonitorDownMail.php     # Downtime alert email
│   │   └── MonitorUpMail.php       # Recovery notification email
│   ├── Models/
│   │   ├── User.php
│   │   ├── Monitor.php
│   │   ├── Issue.php
│   │   └── MonitorLog.php
│   └── Http/Middleware/
│       └── SetLocale.php           # Language middleware
├── database/migrations/
│   ├── create_monitors_table.php
│   ├── create_issues_table.php
│   └── create_monitor_logs_table.php
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php           # Landing/Auth layout
│   │   └── dashboard.blade.php     # Dashboard layout
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── dashboard/
│   │   ├── index.blade.php         # Dashboard overview
│   │   ├── monitors/               # Monitor CRUD views
│   │   └── issues/                 # Issue views
│   ├── emails/
│   │   ├── monitor-down.blade.php  # Down alert email template
│   │   └── monitor-up.blade.php    # Recovery email template
│   ├── landing.blade.php           # Landing page
│   └── status-page.blade.php       # Public status page
├── lang/
│   ├── en/messages.php             # English translations
│   └── ar/messages.php             # Arabic translations
├── routes/
│   ├── web.php                     # All web routes
│   └── console.php                 # Scheduler config
└── .github/workflows/
    └── deploy.yml                  # CI/CD pipeline
```

---

## 🔗 Available Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/` | Landing page |
| GET | `/login` | Login page |
| GET | `/register` | Register page |
| POST | `/logout` | Logout |
| GET | `/status/{userId}` | Public status page |
| GET | `/api/status/{userId}` | Status API (JSON) |
| GET | `/dashboard` | Dashboard overview |
| GET | `/dashboard/stats` | Auto-refresh stats (JSON) |
| GET | `/dashboard/monitors` | Lists all monitors |
| GET | `/dashboard/monitors/create` | Add new monitor |
| POST | `/dashboard/monitors` | Store new monitor |
| GET | `/dashboard/monitors/{id}` | Monitor details |
| GET | `/dashboard/monitors/{id}/edit` | Edit monitor |
| PUT | `/dashboard/monitors/{id}` | Update monitor |
| DELETE | `/dashboard/monitors/{id}` | Delete monitor |
| POST | `/dashboard/monitors/{id}/toggle` | Pause/Resume |
| POST | `/dashboard/monitors/{id}/check-now` | Manual check |
| GET | `/dashboard/monitors/{id}/export-issues` | Export CSV |
| GET | `/dashboard/issues` | All issues |
| GET | `/dashboard/issues/{id}` | Issue details |
| PATCH | `/dashboard/issues/{id}/status` | Update issue status |
| GET | `/lang/{locale}` | Switch language |

---

## 🧪 Artisan Commands

```bash
# Check all due monitors (the scheduler runs this)
php artisan monitors:check

# Run the scheduler (development)
php artisan schedule:work

# Clear caches
php artisan optimize:clear

# List routes
php artisan route:list
```

---

## 📧 Email Templates

The app sends two types of emails:
- **🔴 Monitor Down Alert** — Red gradient header, incident details, link to issue
- **🟢 Monitor Recovery** — Green gradient header, downtime duration, recovery confirmation

Both emails use a dark futuristic design matching the app's theme.

---

## 🌍 Bilingual Support

Switch between **Arabic** and **English** from any page:
- Dashboard sidebar has a language toggle
- Landing page has a language switcher in the nav
- Full RTL (Right-to-Left) support for Arabic

---

## 📝 License

This project is open-sourced under the [MIT License](LICENSE).

---

## 👨‍💻 Author

**Sha3booony** — Built with ❤️ and ☕

---

## 🆘 Troubleshooting

### Cron job not running?
```bash
# Check PHP path
which php
# or for cPanel
ls /opt/cpanel/ea-php*/root/usr/bin/php

# Test the command manually
/opt/cpanel/ea-php84/root/usr/bin/php /path/to/artisan schedule:run
```

### Emails not sending?
1. Check `.env` SMTP settings
2. Check Laravel logs: `storage/logs/laravel.log`
3. Test with Mailtrap first (catches all emails)

### Permission errors?
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Database connection error?
```bash
# Test MySQL connection
mysql -u root -p -e "SHOW DATABASES;"

# Make sure .env database settings are correct
```
