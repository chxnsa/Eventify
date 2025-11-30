<p align="center">
  <img src="public/images/logo.png" alt="Eventify Logo" width="200">
</p>

<h1 align="center">Eventify</h1>

<p align="center">
  <strong>Platform Pemesanan Tiket Event Modern</strong>
</p>

<p align="center">
  <a href="#fitur">Fitur</a> •
  <a href="#teknologi">Teknologi</a> •
  <a href="#instalasi">Instalasi</a> •
  <a href="#penggunaan">Penggunaan</a> •
  <a href="#screenshot">Screenshot</a> •
  <a href="#kontributor">Kontributor</a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

---

## 📖 Tentang Eventify

**Eventify** adalah platform pemesanan tiket event berbasis web yang memungkinkan pengguna untuk menemukan, memesan, dan mengelola tiket untuk berbagai jenis event seperti konser, konferensi, workshop, festival, dan lainnya.

Platform ini menyediakan tiga jenis akun dengan peran berbeda:
- **User** - Mencari event, memesan tiket, dan mengelola booking
- **Organizer** - Membuat dan mengelola event, tiket, serta melihat laporan penjualan
- **Admin** - Mengelola seluruh platform termasuk persetujuan organizer dan monitoring

---

## ✨ Fitur

### 🎫 Untuk User
| Fitur | Deskripsi |
|-------|-----------|
| **Pencarian Event** | Cari event berdasarkan nama, lokasi, tanggal, dan kategori |
| **Filter & Sorting** | Filter berdasarkan lokasi, tanggal dengan calendar picker, dan sorting |
| **Pemesanan Tiket** | Pesan tiket dengan pilihan berbagai jenis tiket dan kuantitas |
| **E-Ticket Digital** | Lihat dan download tiket dalam format PDF |
| **Favorit Event** | Simpan event favorit dengan toggle love icon |
| **Review & Rating** | Berikan review dan rating setelah menghadiri event |
| **Cancel Booking** | Batalkan booking dengan pengembalian kuota tiket otomatis |
| **Riwayat Booking** | Lihat semua riwayat pemesanan tiket |

### 🎭 Untuk Organizer
| Fitur | Deskripsi |
|-------|-----------|
| **Dashboard** | Statistik event, penjualan, dan revenue |
| **Manajemen Event** | Buat, edit, dan hapus event |
| **Manajemen Tiket** | Kelola berbagai jenis tiket per event |
| **Manajemen Booking** | Approve atau cancel booking dari user |
| **Laporan Penjualan** | Lihat laporan penjualan dengan grafik |

### 👨‍💼 Untuk Admin
| Fitur | Deskripsi |
|-------|-----------|
| **Dashboard** | Statistik keseluruhan platform |
| **Manajemen User** | Kelola semua user di platform |
| **Approval Organizer** | Setujui atau tolak pendaftaran organizer |
| **Manajemen Event** | Monitor dan kelola semua event |
| **Featured Event** | Tandai event untuk ditampilkan di hero slider |
| **Laporan & Export** | Generate laporan dan export ke CSV |

### 🎨 Fitur Umum
- ✅ Hero slider dengan featured events
- ✅ Responsive design untuk semua perangkat
- ✅ Real-time kuota tiket
- ✅ QR Code pada e-ticket
- ✅ Multi-role authentication
- ✅ Modern UI dengan Tailwind CSS

---

## 🛠 Teknologi

### Backend
- **Laravel 12** - PHP Framework
- **PHP 8.2+** - Programming Language
- **MySQL 8.0** - Database
- **Laravel Breeze** - Authentication

### Frontend
- **Tailwind CSS 3.x** - CSS Framework
- **Blade Template** - Templating Engine
- **Vite** - Build Tool
- **Chart.js** - Charts & Graphs

### Package Tambahan
- **DomPDF** - PDF Generation
- **Intervention Image** - Image Processing

---

## 📋 Persyaratan Sistem

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.x
- NPM >= 9.x
- MySQL >= 8.0
- Git

---

## 🚀 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/username/eventify.git
cd eventify
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Konfigurasi Environment
```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eventify
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrasi & Seeding Database
```bash
# Jalankan migrasi
php artisan migrate

# Jalankan seeder (opsional, untuk data dummy)
php artisan db:seed
```

### 6. Storage Link
```bash
php artisan storage:link
```

### 7. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Jalankan Aplikasi
```bash
php artisan serve
```

Akses aplikasi di: `http://127.0.0.1:8000`

---

## 👤 Akun Demo

Setelah menjalankan seeder, gunakan akun berikut untuk testing:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@eventify.com | password |
| Organizer | organizer@eventify.com | password |
| User | user@eventify.com | password |

---

## 📁 Struktur Folder
```
eventify/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Controller untuk admin
│   │   │   ├── Organizer/      # Controller untuk organizer
│   │   │   ├── User/           # Controller untuk user
│   │   │   └── ...
│   │   └── Middleware/         # Custom middleware
│   └── Models/                 # Eloquent models
├── config/
│   └── locations.php           # Daftar kota Indonesia
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── public/
│   └── images/                 # Static images
├── resources/
│   ├── css/                    # Stylesheets
│   ├── js/                     # JavaScript
│   └── views/
│       ├── admin/              # Views untuk admin
│       ├── auth/               # Views untuk authentication
│       ├── components/         # Reusable components
│       ├── events/             # Views untuk event
│       ├── home/               # Views untuk home
│       ├── layouts/            # Layout templates
│       ├── organizer/          # Views untuk organizer
│       └── user/               # Views untuk user
├── routes/
│   ├── web.php                 # Main routes
│   ├── admin.php               # Admin routes
│   ├── organizer.php           # Organizer routes
│   └── user.php                # User routes
└── storage/
    └── app/public/
        ├── events/             # Event images
        ├── avatars/            # User avatars
        └── tickets/            # Ticket images
```

---

## 🗄 Database Schema

### Entity Relationship Diagram
```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│    users     │     │   events     │     │  categories  │
├──────────────┤     ├──────────────┤     ├──────────────┤
│ id           │     │ id           │     │ id           │
│ name         │     │ organizer_id │────>│ name         │
│ email        │     │ category_id  │────>│ slug         │
│ role         │     │ name         │     │ icon         │
│ organizer_   │     │ slug         │     └──────────────┘
│   status     │     │ description  │
└──────────────┘     │ location     │
       │             │ venue        │
       │             │ date_start   │
       │             │ status       │
       │             │ is_featured  │
       │             └──────────────┘
       │                    │
       │                    │
       ▼                    ▼
┌──────────────┐     ┌──────────────┐
│   bookings   │     │   tickets    │
├──────────────┤     ├──────────────┤
│ id           │     │ id           │
│ user_id      │     │ event_id     │
│ booking_code │     │ name         │
│ total_amount │     │ price        │
│ status       │     │ quota        │
└──────────────┘     │ sold         │
       │             └──────────────┘
       │
       ▼
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│booking_details│    │   reviews    │     │  favorites   │
├──────────────┤     ├──────────────┤     ├──────────────┤
│ id           │     │ id           │     │ id           │
│ booking_id   │     │ user_id      │     │ user_id      │
│ ticket_id    │     │ event_id     │     │ event_id     │
│ quantity     │     │ booking_id   │     │ created_at   │
│ price        │     │ rating       │     └──────────────┘
│ subtotal     │     │ comment      │
└──────────────┘     └──────────────┘
```

---

## 🔐 Roles & Permissions

### User Flow
```
Register → Login → Browse Events → Book Ticket → Wait Approval → Get E-Ticket → Attend Event → Give Review
```

### Organizer Flow
```
Register as Organizer → Wait Admin Approval → Create Event → Add Tickets → Manage Bookings → View Reports
```

### Admin Flow
```
Login → Approve/Reject Organizers → Manage Users → Monitor Events → Set Featured Events → View Reports
```

---

## 📱 Screenshot

### Landing Page
![Landing Page](docs/screenshots/landing.png)

### Event Detail
![Event Detail](docs/screenshots/event-detail.png)

### E-Ticket
![E-Ticket](docs/screenshots/e-ticket.png)

### Admin Dashboard
![Admin Dashboard](docs/screenshots/admin-dashboard.png)

### Organizer Dashboard
![Organizer Dashboard](docs/screenshots/organizer-dashboard.png)

---

## 🧪 Testing
```bash
# Jalankan semua tests
php artisan test

# Jalankan test dengan coverage
php artisan test --coverage
```

---

## 📝 API Endpoints (Opsional)

Jika ingin mengembangkan API, berikut beberapa endpoint yang bisa diimplementasikan:

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/events` | List semua events |
| GET | `/api/events/{slug}` | Detail event |
| POST | `/api/bookings` | Buat booking baru |
| GET | `/api/user/bookings` | List booking user |
| POST | `/api/favorites/{event}` | Toggle favorite |

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan ikuti langkah berikut:

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

---

## 📄 Lisensi

Didistribusikan di bawah Lisensi MIT. Lihat `LICENSE` untuk informasi lebih lanjut.

---

## 👨‍💻 Kontributor

<table>
  <tr>
    <td align="center">
      <a href="https://github.com/chxnsa">
        <img src="https://github.com/chxnsa.png" width="100px;" alt=""/>
        <br />
        <sub><b>Andi Khaerunnisa Oddang</b></sub>
      </a>
    </td>
  </tr>
</table>

---

## 📞 Kontak

- **Email**: anisaandi1980@gmail.com
- **LinkedIn**: [Andi Khaerunnisa Oddang](https://linkedin.com/in/andi-khaerunnisa-oddang-632330280)
- **GitHub**: [@chxnsa](https://github.com/chxnsa)

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Tailwind CSS](https://tailwindcss.com) - CSS Framework
- [Chart.js](https://www.chartjs.org) - Charts Library
- [DomPDF](https://github.com/dompdf/dompdf) - PDF Generator

---

<p align="center">
  Made with ❤️ by <a href="https://github.com/chxnsa">Your Name</a>
</p>

<p align="center">
  <a href="#eventify">⬆ Back to Top</a>
</p>