# 🔧 Troubleshooting Login ERP Persipura

## ✅ Status Pemeriksaan

### Database & Authentication
- ✅ Database connection: OK
- ✅ Users table: 4 users created
- ✅ Password hashing: Working correctly
- ✅ Laravel Auth::attempt: Working
- ✅ User model configuration: Fixed
- ✅ Routes configuration: OK

### Cache & Configuration
- ✅ Config cache: Cleared
- ✅ Route cache: Cleared
- ✅ View cache: Cleared

## 🚀 Cara Menjalankan Website

### 1. Pastikan XAMPP Berjalan
```bash
# Pastikan Apache dan MySQL aktif di XAMPP Control Panel
```

### 2. Jalankan Laravel Development Server
```bash
# Buka terminal di folder project
cd c:\xampp\htdocs\erp-persipura

# Jalankan server development Laravel
php artisan serve
```

### 3. Akses Website
- Buka browser
- Kunjungi: `http://127.0.0.1:8000` atau `http://localhost:8000`
- Akan redirect otomatis ke halaman login

## 🔐 Kredensial Login

### Admin Account
- **Email:** `admin@persipura.id`
- **Password:** `admin123`

### Akun Lainnya
- **Manager:** `manager@persipura.id` / `admin123`
- **Sales:** `sales@persipura.id` / `admin123`
- **Inventory:** `inventory@persipura.id` / `admin123`

## 🐛 Jika Masih Tidak Bisa Login

### 1. Periksa Log Error
```bash
# Lihat log Laravel
tail -f storage/logs/laravel.log
```

### 2. Test Manual Authentication
```bash
# Jalankan test script
php simple_login_test.php
```

### 3. Reset Session
```bash
# Hapus semua session
php artisan session:table
# Atau restart browser dan clear cookies
```

### 4. Periksa Database Connection
```bash
# Test koneksi database
php artisan tinker
# Dalam tinker:
DB::connection()->getPdo();
```

### 5. Regenerate App Key
```bash
php artisan key:generate
```

## 🔄 Reset Lengkap (Jika Diperlukan)

```bash
# 1. Reset database dan data
php artisan migrate:fresh --seed

# 2. Clear semua cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 3. Regenerate key
php artisan key:generate

# 4. Restart server
php artisan serve
```

## 📱 Browser Troubleshooting

### 1. Clear Browser Cache
- Tekan `Ctrl + Shift + Delete`
- Hapus cookies dan cache

### 2. Coba Incognito/Private Mode
- Buka browser dalam mode private
- Test login

### 3. Coba Browser Berbeda
- Chrome, Firefox, Edge

## 🆘 Jika Semua Gagal

### 1. Periksa Error Message
- Lihat pesan error di browser
- Periksa Network tab di Developer Tools

### 2. Periksa XAMPP
- Pastikan Apache port 80 tidak bentrok
- Pastikan MySQL berjalan di port 3306

### 3. Periksa File .env
- Pastikan DB_DATABASE=persipura_erp
- Pastikan DB_USERNAME=root
- Pastikan DB_PASSWORD= (kosong)

## 📞 Langkah Selanjutnya

Jika masih bermasalah, berikan informasi:
1. Pesan error yang muncul
2. Screenshot halaman login
3. Output dari `php simple_login_test.php`
4. Isi file `storage/logs/laravel.log` (bagian terakhir)

---

**💡 Tips:** Pastikan selalu menjalankan `php artisan serve` untuk development, jangan akses langsung melalui XAMPP htdocs!