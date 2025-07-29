# ACFberg (WIP) – Tailwind + ACF + Gutenberg

This is a custom WordPress theme currently in development. It's being built from the ground up using:

- 🌀 <a href="https://tailwindcss.com/" target="_blank" rel="noopener noreferrer">Tailwind CSS</a> – for utility-first styling
- 🛠️ [ACF (Advanced Custom Fields)](https://www.advancedcustomfields.com/) – for flexible content control
- ✍️ Gutenberg – WordPress' native block editor, extended with custom blocks

---

## 🚧 Status

> **Note:** This theme is a work in progress. Not ready for production use.

---

## 🎯 Goal

Create a lightweight, fast, and modular WordPress theme that:
- Supports Gutenberg and ACF custom blocks
- Uses Tailwind CSS for styling
- Scores well in PageSpeed Insights and GTmetrix
- Is easy to extend and maintain

---

## 📦 Stack Overview

| Tool | Purpose |
|------|---------|
| Tailwind CSS | Utility-first styling |
| ACF (Free or Pro) | Manage custom fields and block content |
| Gutenberg | Custom block-based editing |
| PostCSS | CSS compilation and optimization |
| PHP | WordPress templating and logic |
| NPM | Managing frontend dependencies |

---

## 🗂 Planned Structure
```
ACFberg/
├── assets/ # Tailwind CSS, JS files
├── acf-json/ # ACF field group exports
├── blocks/ # Gutenberg custom blocks (with ACF or native)
├── templates/ # Template partials
├── functions/ # Modular PHP (enqueue, setup, etc.)
├── functions.php
├── style.css # Theme header info (required)
├── index.php # Main archive layout
├── 404.php # Custom 404 layout
├── tailwind.config.js
└── package.json
```
---

## 🚀 Getting Started (Dev Setup – To Be Finalized)

Planned workflow (in progress):

1. Clone the repository into your WordPress `/wp-content/themes/`
2. Run:
`npm install
npm run dev`   # Tailwind watcher and build
3. Build for production:
`npm run build`

---

## ✅ Planned Features

- [ ] Tailwind CSS setup
- [ ] ACF JSON sync
- [ ] Gutenberg block registration
- [ ] Custom template parts `(get_template_part)`
- [ ] Modular PHP (theme setup, enqueue, etc.)
- [ ] Performance optimization (PurgeCSS, lazy loading)

---

## 📌 Notes

- ACF JSON export will be stored in `/acf-json/` for version control
- Custom blocks will be either:
-   Native `block.json`-based blocks
-   ACF Blocks via `acf_register_block_type()`

---

## 📄 License

MIT
