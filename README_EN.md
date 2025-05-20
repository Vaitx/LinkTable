# 🔗 LinkTable — Link Management Panel  

**LinkTable** is a lightweight, modern, and secure panel for managing links with an intuitive interface. The project is designed for convenient work with URLs: adding, editing, deleting, and sorting. All data is stored in a JSON file, making it extremely simple to deploy and use.  

---  

## 🚀 Features  

- 📋 Display links in a table format  
- ✏️ Easy link editing  
- ➕ Quick addition of new entries  
- 🗑️ Delete links  
- 🌙 Modern themes  
- 🔐 Secure authorization via sessions or IP  
- ⚙️ Panel settings in a single configuration file  
- 🦶 Low resource consumption (no database required)  

---  

## ⚙️ Installation Guide:  

1. Download the [latest version](https://github.com/Vaitx/LinkTable/releases).  

2. Extract the contents of the **LinkTable** folder and upload the project to your PHP server (Apache, Nginx, or a local XAMPP/OpenServer setup).  

3. Configure the settings in `settings.php`, including the authorization method and interface.  

> By keeping the footer enabled, you help support and improve the project ❤️  

4. Navigate to `http://linktable/panel.php` and log in.  

> If you encounter any issues during installation or setup, feel free to reach out—I’ll be happy to help! :)  

---

Translation of the configuration file `settings.php`:

```php
<?php
//=================================================
//                  Basic Settings
//=================================================

const NAME_SITE  = 'LinkTable by Vaitx'; // Site name
const ICON_SITE  = '/logo.png';          // Path to the site icon / logo


//=================================================
//          Panel Access Authentication
//=================================================

// If true — login via username and password is used.
// If false — access is allowed only from specified IP addresses.
const SESSION_LOG_IN = true;


// --- Login and Password Authentication (if SESSION_LOG_IN = true) ---

const SESSION_TIME  = 300;               // Session lifetime (in seconds)
const ADMIN_LOGIN   = 'root';            // Admin username
const ADMIN_PASS    = '12343412';        // Admin password


// --- IP-based Authentication (if SESSION_LOG_IN = false) ---

const ADMIN_IP = [
    '127.0.0.1',
    '192.168.1.1'
];                                       // List of allowed IP addresses for panel access


//=================================================
//                Interface Settings
//=================================================

// false — use the theme set in browser settings.
// 'dark' or 'light' — force usage of a specific theme.
const THEME_DEFAULT = false;

// If true — a footer block with project attribution will be displayed at the bottom.
// Keeping this option enabled helps promote and improve the project.
// This option has little impact, and enabling the footer improves the overall appearance.
const FOOTER_DISPLAY = true;

// Additional link setting. You can specify your main website here.
const FOOTER_SITE_NAME = 'Support the Project';                                 // Website name
const FOOTER_SITE_URL  = 'https://github.com/Vaitx/vaitx/blob/main/DONATE.md ';   // Website URL


//=================================================
//          Link Table Behavior & Logic
//=================================================

// If true — clicking a link opens it in a new tab.
// If false — clicking a link opens it in the same tab.
const TARGET_MODE = true;

// If true — links in the table are sorted from newest to oldest.
// This is useful when newly added links appear at the top of the list.
// If false — sorted from oldest to newest (natural order).
const SORTING_END = false;
```

---  

## 🔐 Security  

LinkTable uses:  

* Session-based authorization with a lifetime  
* IP-based authorization option  
* XSS protection when editing links  
* Secure JSON file handling  

---  

## ✨ Screenshots  

> 📌 Dark theme interface:  

![Screenshot Dark](https://raw.githubusercontent.com/Vaitx/LinkTable/refs/heads/main/.github/LinkTable-dark.png)  

> 📌 Light theme interface:  

![Screenshot Light](https://raw.githubusercontent.com/Vaitx/LinkTable/refs/heads/main/.github/LinkTable-light.png)  

> 📌 Admin panel interface:  

![Screenshot Admin Panel](https://raw.githubusercontent.com/Vaitx/LinkTable/refs/heads/main/.github/LinkTable-AdminPanel.png)  

---  

## 👨‍💻 Author  

**Contact me** [here](https://github.com/Vaitx/)  

**Support the project** [here](https://github.com/Vaitx/vaitx/blob/main/DONATE.md#-support-the-project)  

💡 Project version: **1.0.0**  

---  

## 📄 License  

This project is licensed under the **MIT License**.  
Free for personal and commercial use.
