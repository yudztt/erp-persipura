-- ============================================
-- Default Users for Persipura ERP
-- Password untuk semua user: password123
-- ============================================

-- Insert Roles
INSERT INTO `roles` (`id_role`, `nama_role`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'System Admin', 'Administrator dengan akses penuh ke seluruh sistem', NOW(), NOW()),
(2, 'Warehouse Manager', 'Manager gudang dengan akses ke inventory dan warehouse', NOW(), NOW()),
(3, 'Sales Staff', 'Staff penjualan dengan akses ke sales dan customer', NOW(), NOW()),
(4, 'Inventory Staff', 'Staff inventory dengan akses ke stock management', NOW(), NOW());

-- Insert Users
-- Password: password123 (hashed dengan bcrypt)
INSERT INTO `users` (`id_user`, `id_role`, `nama`, `email`, `password`, `email_verified_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ahmad Bintoro', 'admin@persipura.id', '$2y$12$LQv3c1yytEgMVkgZ4Sj5SuXW.kEkXIWepDU9rgO3oc9ILB.hlO3Gy', NOW(), NOW(), NOW()),
(2, 2, 'Siti Maryam', 'manager@persipura.id', '$2y$12$LQv3c1yytEgMVkgZ4Sj5SuXW.kEkXIWepDU9rgO3oc9ILB.hlO3Gy', NOW(), NOW(), NOW()),
(3, 3, 'Rudi Kurniawan', 'sales@persipura.id', '$2y$12$LQv3c1yytEgMVkgZ4Sj5SuXW.kEkXIWepDU9rgO3oc9ILB.hlO3Gy', NOW(), NOW(), NOW()),
(4, 4, 'Dewi Puspita', 'inventory@persipura.id', '$2y$12$LQv3c1yytEgMVkgZ4Sj5SuXW.kEkXIWepDU9rgO3oc9ILB.hlO3Gy', NOW(), NOW(), NOW());

-- ============================================
-- Akun Login:
-- ============================================
-- Email: admin@persipura.id
-- Password: password123
-- Role: System Admin
--
-- Email: manager@persipura.id
-- Password: password123
-- Role: Warehouse Manager
--
-- Email: sales@persipura.id
-- Password: password123
-- Role: Sales Staff
--
-- Email: inventory@persipura.id
-- Password: password123
-- Role: Inventory Staff
-- ============================================
