# ACFberg (WIP) – ACF + Gutenberg

This is a custom WordPress theme currently in development. It's being built from the ground up using:

- 🛠️ [ACF (Advanced Custom Fields)](https://www.advancedcustomfields.com/) – for flexible content control
- ✍️ Gutenberg – WordPress' native block editor, extended with custom blocks

---

## 🚧 Status

> **Note:** This theme is a work in progress. Not ready for production use.

---

## 🎯 Goal

Create a lightweight, fast, and modular WordPress theme that:
- Supports Gutenberg and ACF custom blocks
- Scores well in PageSpeed Insights and GTmetrix
- Is easy to extend and maintain

---

## 📦 Stack Overview

| Tool | Purpose |
|------|---------|
| ACF (Free or Pro) | Manage custom fields and block content |
| Gutenberg | Custom block-based editing |
| PHP | WordPress templating and logic |

---

## 🗂 Planned Structure
```
ACFberg/
├── assets/ # CSS, JS files
├── acf-json/ # ACF field group exports
├── blocks/ # Gutenberg custom blocks (with ACF or native)
├── templates/ # Template partials
├── functions/ # Modular PHP (enqueue, setup, etc.)
│   └── disable-comments.php # Disable comments for Pages only
├── functions.php
├── style.css # Theme header info (required)
├── index.php # Main archive layout
└── 404.php # Custom 404 layout
```

---

## 🚀 Getting Started (Dev Setup – To Be Finalized)

Planned workflow (in progress):

1. Clone the repository into your WordPress `/wp-content/themes/`

---

## ✅ Planned Features

- [ ] ACF JSON sync
- [ ] Gutenberg block registration
- [ ] Custom template parts `(get_template_part)`
- [ ] Modular PHP (theme setup, enqueue, etc.)
- [ ] Performance optimization (lazy loading)

---

## 🔧 Current Features

### Comments Management
- **disable-comments.php**: Automatically disables comments for Pages while keeping them enabled for Posts
- Removes comment support from Pages only
- Hides comment forms and existing comments on Pages
- Removes comments column from Pages admin list
- Supports extending to other post types if needed

---

## 📌 Notes

- ACF JSON export will be stored in `/acf-json/` for version control
- Custom blocks will be either:
-   Native `block.json`-based blocks
-   ACF Blocks via `acf_register_block_type()`
- Modular PHP functions are organized in the `/functions/` directory

---

## 📄 License

MIT
