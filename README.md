# Ambon Oceanic Tourism - Sistem Informasi Destinasi Wisata

Sistem Informasi Destinasi Wisata Kota Ambon berbasis web dengan fitur Chatbot AI menggunakan teknologi NLP dan LLM.

## 🌊 Fitur Utama

### Untuk Pengunjung (Tanpa Login)
- ✅ Browse destinasi wisata dengan desain modern
- ✅ Lihat detail lengkap destinasi (lokasi, jam buka, harga tiket)
- ✅ Pencarian destinasi berdasarkan keyword
- ✅ Filter destinasi berdasarkan kategori
- ✅ AI Chatbot untuk informasi wisata

### Untuk User Terdaftar
- ✅ Semua fitur pengunjung
- ✅ Simpan destinasi favorit
- ✅ Lihat riwayat chat dengan AI
- ✅ Rekomendasi personal dari chatbot

### Untuk Admin
- ✅ Dashboard statistik
- ✅ Kelola destinasi (CRUD)
- ✅ Kelola kategori (CRUD)
- ✅ Kelola user
- ✅ Lihat log chatbot

## 🛠 Tech Stack

- **Backend**: PHP Native (OOP, MVC Pattern)
- **Database**: MySQL with PDO
- **Frontend**: Tailwind CSS, JavaScript
- **Icons**: Google Material Symbols
- **Fonts**: Plus Jakarta Sans, Manrope
- **API**: OpenAI GPT / Google Gemini
- **Server**: Apache/Nginx

## 📋 Prerequisites

- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Apache/Nginx dengan mod_rewrite enabled
- XAMPP/WAMP/LAMP (recommended)

## 🚀 Instalasi

### 1. Clone/Download Project

```bash
cd C:\xampp\htdocs
git clone <repository-url> ambon_oceanic
```

Atau download ZIP dan extract ke `C:\xampp\htdocs\ambon_oceanic`

### 2. Setup Database

1. Jalankan XAMPP Control Panel
2. Start **Apache** dan **MySQL**
3. Buka phpMyAdmin: `http://localhost/phpmyadmin`
4. Buat database baru: `ambon_oceanic`
5. Import file `database.sql`

### 3. Konfigurasi Database

Edit `config/database.php`:

```php
private $host = 'localhost';
private $db_name = 'ambon_oceanic';
private $username = 'root';
private $password = '';
```

### 4. Konfigurasi Aplikasi

Edit `config/app.php`:

```php
// Base URL
define('BASE_URL', 'http://localhost/ambon_oceanic/');

// LLM API Configuration
define('LLM_API_KEY', 'your-openai-api-key-here');
define('LLM_API_ENDPOINT', 'https://api.openai.com/v1/chat/completions');
define('LLM_MODEL', 'gpt-3.5-turbo');
```

### 5. Enable Mod Rewrite (Apache)

Edit `C:\xampp\apache\conf\httpd.conf`:

1. Uncomment:
```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

2. Ubah `AllowOverride None` menjadi `AllowOverride All`:
```apache
<Directory "C:/xampp/htdocs">
    AllowOverride All
</Directory>
```

3. Restart Apache

### 6. Akses Aplikasi

- **Website**: `http://localhost/ambon_oceanic/`
- **Admin Panel**: `http://localhost/ambon_oceanic/admin/login`
  - Username: `admin`
  - Password: `admin123`

## 🎨 Desain & UI

Aplikasi menggunakan desain modern dengan:
- **Material Design 3** color system
- **Glassmorphism** effects
- **Smooth animations** dan transitions
- **Responsive** untuk semua device
- **Dark mode ready** (dapat diaktifkan)

## 🔑 Mendapatkan API Key

### OpenAI (Recommended)

1. Daftar di [OpenAI Platform](https://platform.openai.com/)
2. Buat API key di dashboard
3. Copy dan paste ke `config/app.php`

### Google Gemini (Alternative)

1. Daftar di [Google AI Studio](https://makersuite.google.com/)
2. Buat API key
3. Update konfigurasi:

```php
define('LLM_API_KEY', 'your-gemini-key');
define('LLM_API_ENDPOINT', 'https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent');
define('LLM_MODEL', 'gemini-pro');
```

## 📁 Struktur Project

```
ambon_oceanic/
├── app/
│   ├── controllers/      # Controller files
│   ├── models/          # Model files
│   └── views/           # View templates
├── config/
│   ├── app.php          # App configuration
│   └── database.php     # Database config
├── core/
│   ├── Controller.php   # Base controller
│   └── Model.php        # Base model
├── public/
│   ├── assets/          # CSS, JS, Images
│   └── index.php        # Entry point
├── routes/
│   └── web.php          # Router
├── database.sql         # Database schema
├── .htaccess           # Apache rewrite rules
├── index.php           # Root entry point
└── README.md
```

## 🔒 Keamanan

- ✅ SQL Injection prevention (PDO prepared statements)
- ✅ XSS protection (input sanitization)
- ✅ Password hashing (bcrypt)
- ✅ Secure session management
- ✅ CSRF protection ready

## 🐛 Troubleshooting

### Error 404 / Routing tidak bekerja

**Solusi:**
1. Pastikan mod_rewrite aktif
2. Cek file `.htaccess` ada di root folder
3. Pastikan `AllowOverride All` di Apache config
4. Restart Apache

### Database connection error

**Solusi:**
1. Cek MySQL sudah running
2. Verifikasi kredensial di `config/database.php`
3. Pastikan database `ambon_oceanic` sudah dibuat

### Chatbot tidak merespon

**Solusi:**
1. Pastikan API key sudah diisi dengan benar
2. Cek koneksi internet
3. Verifikasi API endpoint benar
4. Cek error log di browser console (F12)

### Gambar tidak muncul

**Solusi:**
1. Gunakan URL lengkap untuk gambar
2. Atau upload gambar ke `public/assets/images/`
3. Update path gambar di database

## 📱 Fitur Responsif

Aplikasi fully responsive untuk:
- 📱 Mobile (320px - 767px)
- 📱 Tablet (768px - 1023px)
- 💻 Desktop (1024px+)
- 🖥 Large Desktop (1920px+)

## 🎯 URL Structure

```
Home              : /
Destinasi List    : /destinasi
Destinasi Detail  : /destinasi/detail/{id}
Search            : /destinasi?search={keyword}
Filter            : /destinasi?kategori={id}
Chatbot           : /chatbot
Login             : /auth/login
Register          : /auth/register
Favorites         : /auth/favorites
Admin Login       : /admin/login
Admin Dashboard   : /admin
```

## 👨‍💻 Development

### Menambah Destinasi Baru

1. Login sebagai admin
2. Pilih menu "Destinasi"
3. Klik "Tambah Destinasi"
4. Isi form dan simpan

### Upload Gambar

Simpan gambar di `public/assets/images/` dan gunakan path:
```
public/assets/images/nama-file.jpg
```

### Customize Styling

Edit file:
- Tailwind classes langsung di view files
- Custom CSS (jika perlu) di `public/assets/css/style.css`

## 📝 Default Credentials

**Admin:**
- Username: `admin`
- Password: `admin123`

⚠️ **PENTING**: Ganti password default setelah login pertama!

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is created for educational purposes.

## 📞 Support

Untuk pertanyaan dan dukungan:
- Baca dokumentasi lengkap di `INSTALLATION.md`
- Check issues di repository
- Contact development team

## 🙏 Credits

- Design inspiration: Material Design 3
- Icons: Google Material Symbols
- Fonts: Google Fonts
- Images: Unsplash (placeholder)

---

**Dibuat dengan ❤️ untuk Kota Ambon**

© 2024 Ambon Oceanic Tourism. All rights reserved.
