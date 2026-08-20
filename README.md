# CIT UMS (University Management System) 🎓🚀

![CIT UMS Banner](https://img.shields.io/badge/Status-Live-success?style=for-the-badge) ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white) ![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

CIT UMS is a highly scalable, custom-built Enterprise Resource Planning (ERP) platform designed to digitize and automate every aspect of modern university campus life. Built from scratch using a bespoke PHP MVC framework, it handles over 70+ interconnected modules ranging from academics to hostel maintenance.

🌐 **Live Demo:** [citums.thsite.top](https://citums.thsite.top)

---

## 🔐 Enterprise-Grade Security & 2FA OTP Integration

CIT UMS is fortified with a robust, custom-built **Two-Factor Authentication (2FA)** pipeline to ensure university data remains strictly protected against unauthorized access.

- **Dynamic OTP Generation:** Upon logging in with valid credentials, the system halts access and generates a secure, time-sensitive 6-digit One Time Password (OTP).
- **Email Delivery System:** The OTP is securely routed and dispatched instantly to the user's registered institutional email address via an integrated SMTP mailer.
- **Session-Locked Verification:** The user is restricted to an isolated verification screen. Only upon providing the correct, non-expired OTP matching their session hash will the system unlock their dashboard and issue a secure JWT.
- **User-Controlled Toggles:** Users have full autonomy to enable or disable 2FA directly from their dashboard settings, writing their preference securely into the database.
- **Stateless JWT Authorization:** Post-login, the system relies on stateless JSON Web Tokens (JWT) for extremely fast, database-free session validation across all 70+ modules.

---

## ✨ Key Features

- **Custom MVC Architecture:** Lightning-fast, bespoke PHP framework with dynamic routing and secure controller logic.
- **Role-Based Access Control (RBAC):** Strict security boundaries and dedicated dashboards for 6 distinct user types.
- **AI Helpdesk Integration:** A globally injected, floating AI chatbot that assists users with queries across the entire platform.
- **Smart Routing Engine:** Custom-built router capable of case-insensitive resolution for flawless cross-platform deployment (Windows/Linux).
- **Fully Responsive UI:** Mobile-first design with dynamic sidebar navigation, glassmorphism UI elements, and interactive data grids.

---

## 👥 Multi-Role Portals

CIT UMS provides specialized environments tailored to different campus stakeholders:

1. 👨‍🎓 **Student Portal:** Access attendance, assignments, fee payments, library e-books, and outpass requests.
2. 👨‍🏫 **Faculty Portal:** Manage course materials, grade assignments, and monitor student discipline.
3. 🛡️ **Admin Portal:** Global overview, fee collection analytics, system backups, and user role management.
4. 👨‍👩‍👧 **Parent Portal:** Real-time tracking of student progress, attendance, and fee dues.
5. 🛏️ **Warden Portal:** Approve/reject hostel outpasses, monitor hostel attendance, and track room maintenance.
6. 👮 **Security Guard Portal:** Verify digital outpasses at the gate and maintain live visitor logs.

---

## 🛠️ Tech Stack

- **Backend:** PHP 8+ (Custom MVC Framework)
- **Database:** MySQL / PDO
- **Frontend:** HTML5, CSS3 (Custom Utility Classes), Vanilla JavaScript
- **Deployment:** Apache, `.htaccess` URL Rewriting

---

## 🚀 Local Installation & Setup

Want to run CIT UMS locally? Follow these steps:

### 1. Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) (or any LAMP/WAMP stack)
- PHP 8.0+
- MySQL

### 2. Clone the Repository
```bash
git clone https://github.com/yourusername/cit-ums.git
cd cit-ums
```

### 3. Database Configuration
1. Open phpMyAdmin (`http://localhost/phpmyadmin`).
2. Create a new database named `cit_ums`.
3. Import the provided SQL schema (if applicable) or allow the system's setup scripts to generate the tables.
4. Rename `config/database.example.php` to `config/database.php` and update your credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'cit_ums');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

### 4. Serve the Application
If using XAMPP, place the project inside your `htdocs` folder. 
Access the system via your browser:
```text
http://localhost/cit-ums/
```
*(Note: The internal routing engine will automatically detect your local environment and set the `BASE_URL` accordingly).*

---

## 📁 Directory Structure

```text
cit_ums/
├── app/
│   ├── Controllers/    # 70+ Controller classes handling business logic
│   ├── Core/           # Core framework (Router, Controller, Model, JWT)
│   ├── Models/         # Database interaction layers
│   └── Views/          # UI templates and HTML/PHP views
├── config/             # Database & environment configurations
├── public/             # Publicly accessible assets
│   ├── css/            # Stylesheets
│   ├── js/             # Client-side scripts
│   ├── uploads/        # User-uploaded documents and profile pictures
│   └── index.php       # The main application entry point
├── .htaccess           # Apache rewrite rules for clean URLs
└── index.php           # Root forwarder to public/index.php
```

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the [issues page](https://github.com/yourusername/cit-ums/issues).

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---
*Built with ❤️ for educational innovation.*
