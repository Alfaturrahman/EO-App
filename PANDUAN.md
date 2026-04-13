# 🎉 Sistem Manajemen Penyewaan Barang - Sonsun Event Organizer

Aplikasi berbasis web untuk mengelola penyewaan barang event organizer dengan optimalisasi UI/UX menggunakan framework Laravel, Bootstrap, dan MySQL.

## 🚀 Fitur Aplikasi

### User (Customer)
- ✅ Registrasi dan Login
- ✅ Katalog Paket Event dengan tampilan card yang menarik
- ✅ Detail Paket dengan informasi lengkap
- ✅ Form Pemesanan paket event
- ✅ Riwayat Pemesanan 
- ✅ Upload Bukti Pembayaran
- ✅ Tracking status pemesanan

### Admin
- ✅ Dashboard dengan statistik (Total Pesanan, In Progress, Pemasukan)
- ✅ Kelola Paket (CRUD) dengan upload foto
- ✅ Kelola Barang (CRUD) dengan manajemen stok
- ✅ Kelola Pemesanan
- ✅ Validasi Pembayaran
- ✅ Update Status Pengambilan Barang

## 🛠 Teknologi yang Digunakan

- **Framework**: Laravel 11.x
- **Frontend**: Bootstrap 5.3, Bootstrap Icons
- **Database**: MySQL (dapat diganti ke PostgreSQL)
- **PHP**: 8.3+
- **Tools**: Laragon (PHP, Composer, MySQL bundled)

## 📋 Persyaratan Sistem

- PHP >= 8.3
- Composer
- MySQL >= 8.0 atau PostgreSQL >= 13
- Laragon (recommended) atau XAMPP/WAMP

## 🔧 Cara Instalasi

### 1. Project sudah ada di:
```
C:\laragon\www\sonsun-rental
```

### 2. Setup Environment
```bash
cd C:\laragon\www\sonsun-rental
copy .env.example .env
```

### 3. Edit File `.env`
Buka file `.env` dan sesuaikan konfigurasi database:

```env
APP_NAME="Sonsun Rental"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sonsun_rental
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

###  5. Buat Database
Buka **HeidiSQL** (di Laragon) atau phpMyAdmin, lalu buat database baru:
```sql
CREATE DATABASE sonsun_rental;
```

### 6. Jalankan Migration
```bash
php artisan migrate
```

### 7. Jalankan Seeder (Data Admin Default)
```bash
php artisan db:seed --class=AdminSeeder
```

Ini akan membuat akun admin default:
- **Email**: admin@sonsun.com
- **Password**: password

### 8. Jalankan Server
```bash
php artisan serve
```

Aplikasi dapat diakses di: **http://localhost:8000**

## 📱 Cara Menggunakan

### 🟦 Akses User (Customer)
1. Buka http://localhost:8000
2. **Registrasi** akun baru atau login
3. Browse katalog paket di halaman utama
4. Klik paket yang diinginkan untuk melihat **Detail**
5. Klik "Pesan Sekarang"
6. Isi form pemesanan:
   - Nama acara
   - Tanggal mulai & selesai
   - Alamat acara
7. Setelah pemesanan dibuat, **upload bukti pembayaran**
8. Pantau status pemesanan di menu **"Pesanan Saya"**

### 🟥 Akses Admin
1. Buka **http://localhost:8000/admin/login**
2. Login dengan:
   - **Email**: `admin@sonsun.com`
   - **Password**: `password`
3. **Dashboard** menampilkan:
   - Total Pesanan
   - Pesanan In Progress
   - Total Pemasukan
   - Daftar pemesanan terbaru
4. **Kelola Paket**: Tambah, edit, hapus paket event
5. **Kelola Barang**: Manage inventaris barang & stok
6. **Kelola Pemesanan**: 
   - Validasi bukti pembayaran
   - Update status pengambilan barang

## 📂 Struktur Database

### Tabel Utama:
- `users` - Data customer (name, email, password, phone, address)
- `admins` - Data admin (name, email, password)
- `pakets` - Paket event (nama, deskripsi, harga, foto)
- `barangs` - Inventaris barang (nama, deskripsi, harga_sewa, stok, foto)
- `pemesanans` - Data pemesanan dengan relasi ke users & pakets

### Status Pemesanan:
- **Status Pembayaran**: 
  - `pending` - Menunggu pembayaran/validasi
  - `lunas` - Sudah dibayar dan divalidasi admin
  
- **Status Pengambilan**: 
  - `belum_diambil` - Barang belum diambil customer
  - `dalam_penggunaan` - Barang sedang digunakan
  - `selesai` - Barang sudah dikembalikan

## 🎨 UI/UX Features

- ✅ Responsive design (mobile & desktop)
- ✅ Modern gradient navbar
- ✅ Card-based layout dengan hover effects
- ✅ Bootstrap Icons untuk visual yang menarik
- ✅ Color-coded badges untuk status
- ✅ Admin sidebar navigation
- ✅ Alert notifications (success/error)
- ✅ Image preview untuk foto paket & barang

## 📸 Upload Foto

Foto akan disimpan di folder:
- **Paket**: `public/uploads/paket/`
- **Barang**: `public/uploads/barang/`
- **Bukti Pembayaran**: `public/uploads/bukti/`

Format yang didukung: JPG, PNG, JPEG (Max: 2MB)

## 🔒 Keamanan

- ✅ Password di-hash menggunakan bcrypt
- ✅ CSRF Protection pada semua form
- ✅ Middleware authentication untuk protected routes
- ✅ Session-based admin authentication
- ✅ Form validation di backend & frontend
- ✅ Authorization checks (user hanya bisa akses data sendiri)

## 🐛 Troubleshooting

### ❌ Error: "Class 'Hash' not found"
```bash
composer dump-autoload
```

### ❌ Error: "SQLSTATE[HY000] [1045] Access denied"
Pastikan konfigurasi database di `.env` sudah benar (username & password MySQL).

### ❌ Gambar tidak muncul
Pastikan folder `public/uploads` sudah dibuat dan memiliki permission write.

### ❌ Middleware 'admin' not found
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### ❌ Error migration
Hapus semua tabel di database, lalu jalankan ulang:
```bash
php artisan migrate:fresh --seed
```

## 📝 Routes

### Public Routes:
- `GET /` - Home (Katalog paket)
- `GET /login` - Login user
- `GET /register` - Registrasi user

### User Routes (Auth Required):
- `GET /paket/{id}` - Detail paket
- `GET /pemesanan/create/{paket}` - Form pemesanan
- `POST /pemesanan` - Submit pemesanan
- `GET /pemesanan` - Daftar pesanan user
- `GET /pemesanan/{id}` - Detail pesanan
- `POST /pemesanan/{id}/upload-bukti` - Upload bukti pembayaran

### Admin Routes:
- `GET /admin/login` - Login admin
- `GET /admin/dashboard` - Dashboard admin
- `resource /admin/paket` - CRUD Paket
- `resource /admin/barang` - CRUD Barang
- `GET /admin/pemesanan` - List pemesanan
- `GET /admin/pemesanan/{id}` - Detail pemesanan
- `POST /admin/pemesanan/{id}/validasi-pembayaran` - Validasi pembayaran
- `POST /admin/pemesanan/{id}/update-status` - Update status pengambilan

## 📊 Testing

Aplikasi dapat diuji menggunakan:
- **Black Box Testing** - Menguji fungsionalitas setiap fitur
- **System Usability Scale (SUS)** - Menguji tingkat kemudahan penggunaan

## 📖 Dokumentasi Penelitian

Aplikasi ini dikembangkan berdasarkan penelitian:

**"Pengembangan Sistem Manajemen Penyewaan Barang dengan Optimalisasi UI/UX pada Event Organizer Sonsun"**

Menggunakan metode **Design Thinking**:
1. **Empathize** - Memahami kebutuhan pengguna melalui wawancara
2. **Define** - Merumuskan pain points & how might we questions
3. **Ideate** - Brainstorming solusi  dan fitur
4. **Prototype** - Desain UI/UX menggunakan Figma
5. **Test** - Pengujian Black Box & SUS

## 👨‍💻 About Sonsun

**CV Sonsun Multi Kreasi**
- **Jenis Usaha**: Event Organizer & Event Specialist
- **Berdiri**: 16 Juli 2020
- **Pemilik**: Eka Septian Nugraha
- **Lokasi**: Ruko Mall Botani Blok A22 No5, Batam
- **Fokus**: Perencanaan ide & tema, supplier vendor, perencanaan budget

**Misi**: Menjadi layanan jasa Event Specialist dan produksi terbaik di semua kalangan, dengan kualitas dan kepuasan yang melampaui ekspektasi.

## 🎓 Credits

- **Framework**: Laravel (Taylor Otwell)
- **UI Framework**: Bootstrap
- **Icons**: Bootstrap Icons
- **Database**: MySQL/PostgreSQL
- **Development**: Penelitian Tugas Akhir

## 📞 Support

Untuk pertanyaan atau issues, silakan hubungi developer atau create issue.

---

**© 2026 Sonsun Event Organizer. All Rights Reserved.**
