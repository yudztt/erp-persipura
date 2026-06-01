-- ============================================
-- QUICK SETUP - PERSIPURA ERP
-- Copy-paste SQL ini ke phpMyAdmin
-- ============================================

-- 1. Disable foreign key checks
SET FOREIGN_KEY_CHECKS = 0;

-- 2. Hapus data lama jika ada
DELETE FROM users;
DELETE FROM roles;

-- 3. Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- 4. Insert Roles
INSERT INTO `roles` (`id_role`, `nama_role`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'System Admin', 'Administrator dengan akses penuh', NOW(), NOW()),
(2, 'Warehouse Manager', 'Manager gudang', NOW(), NOW()),
(3, 'Sales Staff', 'Staff penjualan', NOW(), NOW()),
(4, 'Inventory Staff', 'Staff inventory', NOW(), NOW());

-- 5. Insert Users
-- Password untuk semua user: admin123
INSERT INTO `users` (`id_user`, `id_role`, `nama`, `email`, `password`, `email_verified_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin Persipura', 'admin@persipura.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NOW()),
(2, 2, 'Siti Maryam', 'manager@persipura.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NOW()),
(3, 3, 'Rudi Kurniawan', 'sales@persipura.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NOW()),
(4, 4, 'Dewi Puspita', 'inventory@persipura.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NOW());

-- ============================================
-- SELESAI!
-- ============================================
-- Sekarang login dengan salah satu akun:
-- 
-- ADMIN:
-- Email    : admin@persipura.id
-- Password : admin123
--
-- MANAGER:
-- Email    : manager@persipura.id
-- Password : admin123
--
-- SALES:
-- Email    : sales@persipura.id
-- Password : admin123
--
-- INVENTORY:
-- Email    : inventory@persipura.id
-- Password : admin123
-- ============================================
