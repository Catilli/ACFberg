# ACFberg Theme

A WordPress theme with advanced Tailwind CSS integration using an admin-only CDN approach.

## Tailwind CSS Capture System

This theme implements a unique approach to using Tailwind CSS:

### How It Works

1. **Admin-Only CDN**: The Tailwind CDN only loads when users are logged in (admin area)
2. **CSS Capture**: When logged-in users visit pages, the generated CSS is captured and stored
3. **Frontend Delivery**: Non-logged-in users receive the stored CSS instead of the CDN

### Benefits

- **Performance**: Frontend users get static CSS instead of dynamic CDN loading
- **Reliability**: No dependency on external CDN for frontend users
- **Control**: Admins can manually capture and manage CSS
- **Flexibility**: Still get the benefits of Tailwind CDN for development

### Usage

#### For Developers/Admins:
1. Log into WordPress admin
2. Visit any page with Tailwind classes
3. CSS is automatically captured and stored
4. Use "Theme Options → Tailwind CSS" to manually capture or clear CSS

#### For Frontend Users:
- Automatically receive stored CSS without any CDN dependencies
- Fast loading with static CSS
- No external requests to Tailwind CDN

### File Structure

```
functions/
├── class-system.php      # Main Tailwind capture system
├── acf-options.php       # Admin interface for CSS management
└── script-system.php     # Dynamic script loading
```

### Key Functions

- `capture_tailwind_css()`: Captures CSS from admin users
- `inject_stored_css()`: Serves stored CSS to frontend users
- `get_stored_tailwind_css()`: Retrieves captured CSS from options

### Storage

CSS is stored in WordPress options:
- `tailwind_captured_css`: The actual CSS content
- `tailwind_css_last_updated`: Timestamp of last capture

### Testing

Visit the homepage to see the system in action:
- **Logged in**: See Tailwind CDN working with real-time CSS
- **Not logged in**: See stored CSS being served
- **Admin**: Use Theme Options to manage CSS capture

## Features

- Dynamic script loading based on HTML classes/IDs
- ACF integration for theme options
- Block pattern support
- Comment system disabled
- Responsive design with Tailwind utilities
