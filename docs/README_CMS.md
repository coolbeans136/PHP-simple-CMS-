# Simple CMS with PHP & MySQL

A clean, simple content management system with a robust backend built with PHP and MySQL.

## Features

### Public Side
- **Home Page** - Dynamic homepage with recent posts
- **Blog** - View all blog posts/articles
- **Pages** - Static content pages
- **Clean, responsive design** using Bootstrap 5

### Admin Side (CMS)
- **Dashboard** - Overview with statistics
- **Page Management** - Create, edit, delete pages
- **Post Management** - Create, edit, delete blog posts
- **Settings** - Configure site name, description, and email
- **User Authentication** - Secure login system with sessions

## Structure

```
/workspaces/1by1/
├── config.php              # Database configuration and helper functions
├── database.sql            # Database schema and sample data
├── public/                 # Public-facing website
│   ├── index.php          # Homepage
│   ├── blog.php           # Blog listing
│   ├── post.php           # Single post view
│   └── pages.php          # Pages listing/view
└── admin/                  # Admin CMS
    ├── login.php          # Admin login
    ├── index.php          # Admin dashboard
    ├── pages.php          # Manage pages
    ├── edit_page.php      # Add/edit pages
    ├── posts.php          # Manage posts
    ├── edit_post.php      # Add/edit posts
    ├── settings.php       # Site settings
    └── logout.php         # Logout handler
```

## Setup Instructions

### 1. Database Setup

First, update the database credentials in `config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // Your MySQL username
define('DB_PASS', '');              // Your MySQL password
define('DB_NAME', 'cms_db');
```

### 2. Create Database

Import the database schema:

```bash
mysql -u root -p < database.sql
```

Or manually:
1. Open phpMyAdmin or MySQL command line
2. Run the SQL commands from `database.sql`

This will create:
- Database `cms_db`
- Tables: `admins`, `pages`, `posts`, `settings`
- Default admin user (username: `admin`, password: `admin123`)
- Sample pages and posts

### 3. Configure Web Server

#### Option A: Using PHP Built-in Server (Development)

```bash
# Navigate to the project directory
cd /workspaces/1by1

# Start PHP server
php -S localhost:8000
```

Then access:
- Public site: http://localhost:8000/public/index.php
- Admin panel: http://localhost:8000/admin/login.php

#### Option B: Using Apache/Nginx (Production)

Configure your web server document root to `/workspaces/1by1`

**Apache .htaccess example:**
```apache
RewriteEngine On
RewriteBase /

# Redirect to public folder by default
RewriteCond %{REQUEST_URI} !^/public/
RewriteCond %{REQUEST_URI} !^/admin/
RewriteRule ^(.*)$ public/$1 [L]
```

### 4. Access the System

**Public Website:**
- http://localhost:8000/public/index.php

**Admin Panel:**
- http://localhost:8000/admin/login.php
- Username: `admin`
- Password: `admin123`

## Admin Features

### Dashboard
- View statistics (total pages, posts, published content)
- See recent posts at a glance

### Manage Pages
- Create new static pages
- Edit existing pages with HTML support
- Set page slug (URL)
- Add meta descriptions for SEO
- Publish/unpublish pages

### Manage Posts
- Create blog posts/articles
- Add excerpts for listings
- Full HTML content support
- Publish/unpublish posts
- Author tracking

### Settings
- Configure site name
- Set site description
- Update contact email

## Database Schema

### admins
- `id` - Primary key
- `username` - Admin username
- `password` - Hashed password (bcrypt)
- `email` - Admin email
- `created_at` - Registration timestamp

### pages
- `id` - Primary key
- `title` - Page title
- `slug` - URL-friendly identifier
- `content` - HTML content
- `meta_description` - SEO description
- `is_published` - Visibility status
- `created_at` / `updated_at` - Timestamps

### posts
- `id` - Primary key
- `title` - Post title
- `slug` - URL-friendly identifier
- `content` - HTML content
- `excerpt` - Short summary
- `author_id` - Foreign key to admins
- `is_published` - Visibility status
- `created_at` / `updated_at` - Timestamps

### settings
- `id` - Primary key
- `setting_key` - Setting identifier
- `setting_value` - Setting value
- `updated_at` - Last modified timestamp

## Security Features

- Password hashing with bcrypt
- Session-based authentication
- SQL injection protection using prepared statements
- Input sanitization
- CSRF protection ready (can be enhanced)

## Customization

### Styling
The system uses Bootstrap 5 CDN. To customize:
- Edit inline styles in each PHP file
- Create a separate CSS file and link it
- Modify the color scheme in the gradient backgrounds

### Adding Features
- **Categories**: Add a `categories` table and link to posts
- **Media Upload**: Implement file upload functionality
- **User Roles**: Extend `admins` table with role field
- **Comments**: Add a `comments` table
- **Search**: Implement full-text search

## Production Checklist

- [ ] Change default admin password
- [ ] Update database credentials
- [ ] Enable HTTPS
- [ ] Set proper file permissions
- [ ] Add .htaccess security rules
- [ ] Enable error logging (disable display_errors)
- [ ] Implement backup strategy
- [ ] Add CSRF tokens to forms
- [ ] Implement rate limiting on login
- [ ] Add file upload validation if implementing media

## Troubleshooting

### Database Connection Failed
- Check MySQL is running
- Verify credentials in config.php
- Ensure database exists

### Cannot Login
- Verify database was imported correctly
- Check session support is enabled in PHP
- Clear browser cookies/session

### Pages Not Displaying
- Check `is_published` status in database
- Verify database connection
- Check for PHP errors (enable error_reporting in development)

## Requirements

- PHP 7.4+ (with mysqli extension)
- MySQL 5.7+ or MariaDB 10.3+
- Web server (Apache/Nginx or PHP built-in server)

## License

Free to use and modify for your projects.
