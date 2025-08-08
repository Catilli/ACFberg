# ACFberg Theme

A WordPress theme with advanced Tailwind CSS integration using automatic CSS capture and page-specific caching.

## Tailwind CSS Capture System

This theme implements an intelligent approach to using Tailwind CSS:

### How It Works

1. **Automatic Capture**: When logged-in users visit pages, Tailwind CSS is automatically captured
2. **Page-Specific Files**: CSS is saved to individual files for each page type
3. **Smart Injection**: Non-logged-in users receive the appropriate cached CSS file
4. **No CDN Dependency**: Frontend users get static CSS without external requests

### Benefits

- **Performance**: Frontend users get optimized, page-specific CSS
- **Reliability**: No dependency on external CDN for frontend users
- **Automatic**: No manual intervention required - CSS is captured automatically
- **Efficient**: Only loads the CSS needed for each specific page type

### CSS File Structure

The system creates individual CSS files for different page types:

```
cache/
├── home.css              # Homepage styles
├── single.css            # All posts and custom post types
├── page-123.css          # Individual pages (unique layouts)
├── archive.css           # Archive pages
├── search.css            # Search results
└── 404.css              # 404 error pages
```

### Usage

#### For Developers/Admins:
1. **Log into WordPress admin**
2. **Visit any page** - CSS is automatically captured and saved
3. **Check Theme Options** - Monitor CSS capture status
4. **Clear storage** - If needed, clear captured CSS from localStorage

#### For Frontend Users:
- **Automatic CSS delivery** - Receive appropriate cached CSS for each page
- **Fast loading** - Static CSS files without CDN requests
- **Page-specific optimization** - Only load CSS needed for current page

### File Structure

```
ACFberg/
├── functions/
│   ├── css-cache.php     # CSS capture and injection system
│   ├── options.php       # Admin interface for CSS management
│   ├── acf-functions.php # ACF helper functions
│   ├── script-system.php # Dynamic script loading
│   ├── block-patterns.php # Block pattern support
│   ├── disable-comments.php # Comment system
│   └── setup.php         # Theme setup and menus
├── assets/
│   ├── cache/            # CSS cache files
│   ├── js/              # JavaScript files
│   ├── css/             # CSS files
│   └── media/           # Media files
├── header.php            # Header template with Tailwind CDN
├── page.php              # Page template with Tailwind styling
├── index.php             # Main template
├── 404.php              # 404 error page
└── style.css            # Theme information
```

### Key Features

#### CSS Capture System
- **Automatic capture** when logged-in users visit pages
- **Page-specific files** for optimal performance
- **REST API integration** for seamless saving
- **Smart file naming** based on page type

#### Admin Interface
- **Theme Options page** for CSS management
- **Storage status indicators** for monitoring
- **Clear storage functionality** for maintenance
- **Visit frontend button** for easy testing

#### ACF Integration
- **Safe ACF functions** with fallbacks
- **Helper functions** for theme options
- **Field support** in templates

#### Dynamic Script Loading
- **JSON-based configuration** in `assets/script.json`
- **Class/ID detection** for automatic loading
- **Priority system** for script loading order

### Technical Implementation

#### CSS Capture Process
1. **Logged-in user visits page** → Tailwind CDN loads
2. **CSS automatically captured** → Stored in localStorage
3. **REST API call** → Saves CSS to appropriate cache file
4. **Logged-out users** → Receive cached CSS via `<head>` injection

#### File Naming Logic
- **Homepage**: `home.css`
- **Posts/Custom Post Types**: `single.css` (shared styles)
- **Individual Pages**: `page-{id}.css` (unique layouts)
- **Archives**: `archive.css`
- **Search**: `search.css`
- **404**: `404.css`

#### REST API Endpoint
- **URL**: `/wp-json/tailwind-cache/v1/save`
- **Method**: POST
- **Permission**: Logged-in users with `edit_posts` capability
- **Response**: JSON with saved filename

### Testing

Visit any page to see the system in action:

- **Logged in**: See Tailwind CDN working with real-time CSS generation
- **Not logged in**: See cached CSS being served from static files
- **Admin**: Use Theme Options to monitor and manage CSS capture

### Requirements

- WordPress 5.0+
- PHP 7.4+
- ACF Pro (recommended for full functionality)
- Write permissions for cache directory

### Installation

1. Upload theme to `/wp-content/themes/acfberg/`
2. Activate theme in WordPress admin
3. Visit pages while logged in to capture CSS
4. Check Theme Options for CSS management

### Customization

- **Add new page types**: Modify `tailwind_cache_get_filename()` function
- **Custom CSS files**: Update file naming logic in `css-cache.php`
- **Script loading**: Edit `assets/script.json` for dynamic scripts
- **ACF fields**: Use helper functions in `acf-functions.php`

## Features

- ✅ **Automatic CSS capture** for optimal performance
- ✅ **Page-specific CSS files** for efficient loading
- ✅ **ACF integration** with safe helper functions
- ✅ **Dynamic script loading** based on HTML content
- ✅ **Block pattern support** for reusable content
- ✅ **Responsive design** with Tailwind utilities
- ✅ **Admin interface** for CSS management
- ✅ **REST API integration** for seamless operation
