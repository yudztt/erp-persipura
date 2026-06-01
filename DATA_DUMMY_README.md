# Data Dummy ERP Persipura

## Overview
File ini berisi dokumentasi lengkap tentang 100+ data dummy yang telah dibuat untuk sistem ERP Persipura. Data ini dirancang untuk membuat website menjadi dinamis dan realistis.

## Struktur Data Dummy

### 1. Kategori Produk (15 kategori)
- Jersey Persipura
- Kaos Casual
- Jaket & Hoodie
- Celana Training
- Sepatu Futsal
- Sepatu Casual
- Aksesoris
- Tas & Ransel
- Topi & Kupluk
- Kaos Kaki
- Sarung Tangan
- Syal & Scarf
- Pin & Badge
- Gantungan Kunci
- Stiker & Decal

### 2. Supplier (15 supplier)
**Supplier Utama:**
- PT Garuda Sports Indonesia (Jakarta)
- CV Jayapura Textile (Jayapura)
- PT Mutiara Papua Garment (Sentani)
- Toko Olahraga Mandiri (Jayapura)
- CV Cendrawasih Sports (Jayapura)

**Supplier Tambahan:** 10 supplier random dengan data realistis

### 3. Customer (50 customer)
- Nama lengkap Indonesia
- Nomor telepon
- Email unik
- Alamat lengkap
- Tanggal registrasi bervariasi (2 tahun terakhir)

### 4. Produk (55+ produk)

#### Jersey & Apparel
- Jersey Home/Away/Third Persipura 2026
- Jersey Retro Persipura 1990
- Jersey Training
- Kaos casual dengan berbagai desain
- Jaket Varsity, Hoodie, Windbreaker
- Celana training dan jogger

#### Sepatu
- Sepatu Futsal (Pro, Elite, Junior, Classic, Limited Edition)
- Sneakers, Canvas, Slip On, High Top
- Running Shoes

#### Aksesoris
- Tas ransel, gym, selempang
- Topi baseball, snapback, kupluk
- Kaos kaki home/away/training
- Sarung tangan kiper dan winter
- Syal dan scarf supporter
- Pin, badge, gantungan kunci
- Stiker dan decal

**Fitur Produk:**
- Kode produk unik (PSP0001-PSP0055+)
- Harga beli dan jual realistis
- Stok dan stok minimum
- Relasi dengan kategori dan supplier

### 5. Transaksi Pembelian (30 transaksi)
- Periode: 6 bulan terakhir
- 2-5 item per transaksi
- Total nilai bervariasi
- Relasi dengan supplier dan user
- Detail pembelian lengkap

### 6. Transaksi Penjualan (80 transaksi)
- Periode: 6 bulan terakhir
- 1-4 item per transaksi
- Frekuensi lebih tinggi dari pembelian (realistis)
- Relasi dengan customer dan user
- Detail penjualan lengkap

### 7. Prediksi Penjualan (240 record)
- 12 bulan ke depan
- 20 produk top seller
- Variasi seasonal (boost di bulan 6, 7, 12)
- Akurasi 70-95%
- Algoritma prediksi sederhana

### 8. Rekomendasi Restock (25 rekomendasi)
- Status: pending, approved, rejected, completed
- Priority: low, medium, high, urgent
- Alasan restock yang realistis
- Estimasi biaya
- Target date

## Cara Menjalankan Seeder

### Opsi 1: Menggunakan Script Otomatis
```bash
php run_seeders.php
```

### Opsi 2: Manual Laravel Artisan
```bash
# Reset database dan jalankan migration
php artisan migrate:fresh

# Jalankan semua seeders
php artisan db:seed

# Atau jalankan seeder spesifik
php artisan db:seed --class=ProdukSeeder
```

### Opsi 3: Seeder Individual
```bash
php artisan db:seed --class=KategoriSeeder
php artisan db:seed --class=SupplierSeeder
php artisan db:seed --class=CustomerSeeder
php artisan db:seed --class=ProdukSeeder
php artisan db:seed --class=PembelianSeeder
php artisan db:seed --class=PenjualanSeeder
php artisan db:seed --class=PrediksiPenjualanSeeder
php artisan db:seed --class=RestockRekomendasiSeeder
```

## Fitur Data Dummy

### 1. Data Realistis
- Nama produk sesuai dengan brand Persipura
- Harga yang masuk akal untuk merchandise sepak bola
- Alamat dan nama Indonesia
- Tanggal transaksi yang bervariasi

### 2. Relasi Database Lengkap
- Foreign key constraints terpenuhi
- Data konsisten antar tabel
- Referential integrity terjaga

### 3. Variasi Data
- Stok produk bervariasi (5-100)
- Harga produk sesuai kategori
- Transaksi dengan periode waktu berbeda
- Status dan priority yang beragam

### 4. Business Logic
- Harga jual > harga beli
- Stok minimum < stok aktual
- Prediksi dengan seasonal pattern
- Rekomendasi restock berdasarkan kondisi stok

## Penggunaan untuk Development

### Dashboard
- Grafik penjualan dengan data 6 bulan
- Top selling products
- Low stock alerts
- Revenue trends

### Inventory Management
- Daftar produk dengan stok real-time
- Kategori produk lengkap
- Supplier information
- Restock recommendations

### Sales & Purchase
- History transaksi lengkap
- Customer database
- Supplier management
- Transaction details

### Reporting & Analytics
- Sales forecasting
- Inventory analysis
- Customer insights
- Supplier performance

## Customization

Untuk menambah atau mengubah data dummy:

1. **Edit Seeder Files**: Modifikasi file di `database/seeders/`
2. **Tambah Kategori**: Edit `KategoriSeeder.php`
3. **Tambah Produk**: Edit `ProdukSeeder.php`
4. **Ubah Jumlah Data**: Sesuaikan loop counter di seeder
5. **Reset Data**: Jalankan `php artisan migrate:fresh --seed`

## Notes

- Data dummy menggunakan Faker library untuk data random yang realistis
- Semua timestamp menggunakan data historis yang masuk akal
- Kode produk menggunakan format PSP + 4 digit angka
- Data dapat di-reset kapan saja tanpa mempengaruhi struktur database
- Cocok untuk demo, testing, dan development

---

**Total Data Dummy: 100+ records** mencakup semua aspek sistem ERP untuk merchandise Persipura, membuat website menjadi dinamis dan siap untuk presentasi atau testing.