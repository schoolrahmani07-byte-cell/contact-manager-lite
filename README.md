# Contact Manager Lite

A lightweight, custom-built WordPress plugin designed to securely collect, sanitize, and manage user contact inquiries directly from the WordPress admin dashboard.

## 🚀 Key Features
- **Frontend Shortcode:** Easy-to-use shortcode to embed a clean contact form on any page or post.
- **Security First:** Implements WordPress Nonces (`wp_nonce_field`) and strict input sanitization (`sanitize_text_field`, `sanitize_email`, `sanitize_textarea_field`) to prevent XSS and unauthorized submissions.
- **Database Storage:** Automatically creates and manages a custom MySQL table upon activation to log all incoming messages safely.
- **Admin Management Dashboard:** Dedicated menu page in the WordPress backend to view, track, and manage submitted inquiries with clean empty-state handling.

## 🛠️ Technologies Used
- **Backend:** PHP, WordPress Plugin API (Hooks, Shortcodes, `$wpdb`)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3

## 📦 Installation
1. Download or clone this repository into your `/wp-content/plugins/` directory.
2. Activate **Contact Manager Lite** through the **Plugins** menu in WordPress.
3. Add the shortcode to any page where you want the contact form to appear.

## 👨‍💻 Author
Developed by **Cezar** — WordPress Plugin & Backend Developer.
