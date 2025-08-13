# DPCV - A Responsive Education Platform

This is a fully responsive education platform website built with PHP and SQL. It features an admin panel for managing content and a user-friendly frontend with a modern design.

## Features

### Frontend
- **Homepage:** Displays recent uploads and a powerful search bar.
- **Dark/Light Mode:** Toggle between color schemes.
- **Scroll-to-Top:** Smooth scrolling back to the top of the page.
- **Class Menu:** Hover dropdown menu showing all available classes.
- **Responsive Design:** Works on PC, mobile, and tablets.

### Backend (Admin Panel)
- **Secure Login:** Admin login system.
- **File Uploads:** Upload various file types (PDF, DOCX, etc.).
- **Class Management:** Add, update, and delete classes.
- **Menu Management:** Control the navigation menu items.

## Requirements

- [XAMPP](https://www.apachefriends.org/index.html) or any other local server environment with:
  - PHP >= 7.4
  - MySQL or MariaDB

## How to Run Locally

1.  **Clone the repository or download the zip file.**
    ```bash
    git clone <repository_url>
    ```
    Or simply place the project files in your server's web directory.

2.  **Place the project in your server's root directory.**
    - For XAMPP, this is typically the `htdocs` folder (e.g., `C:/xampp/htdocs/dpcv`).

3.  **Create the database.**
    - Open your database management tool (like phpMyAdmin).
    - Create a new database named `dpcv_db`.
    - Import the `database.sql` file into the `dpcv_db` database. This will create the necessary tables and insert some sample data.

4.  **Update database credentials (if necessary).**
    - Open the `includes/config.php` file.
    - If your database credentials are different from the defaults (user: `root`, no password), update the `DB_USER` and `DB_PASS` constants.

5.  **Access the website.**
    - Open your web browser and navigate to `http://localhost/dpcv/`.

6.  **Access the Admin Panel.**
    - Navigate to `http://localhost/dpcv/admin/`.
    - **Username:** `admin`
    - **Password:** `password` (Note: The password is intentionally simple for this project. For a production environment, you should use a strong, hashed password.)

## File Structure

```
dpcv/
├── admin/
│   ├── index.php           # Admin login
│   ├── dashboard.php       # Admin dashboard
│   ├── classes.php         # Manage classes
│   ├── edit_class.php      # Edit a class
│   ├── uploads.php         # Manage file uploads
│   ├── menu.php            # Manage menu items
│   ├── edit_menu.php       # Edit a menu item
│   ├── logout.php          # Admin logout
│   └── includes/
│       ├── admin_header.php
│       └── admin_footer.php
├── css/
│   └── style.css           # All styles for front and backend
├── js/
│   └── script.js           # JavaScript for interactivity
├── includes/
│   ├── config.php          # Database connection and site config
│   ├── header.php          # Frontend header
│   └── footer.php          # Frontend footer
├── uploads/                # Directory for uploaded files
├── images/                 # For file type icons and other images
├── index.php               # Homepage
├── about.php               # About Us page
├── contact.php             # Contact Us page
├── class.php               # Displays files for a specific class
├── search.php              # Search results page
├── database.sql            # SQL file to set up the database
└── README.md               # This file
```
