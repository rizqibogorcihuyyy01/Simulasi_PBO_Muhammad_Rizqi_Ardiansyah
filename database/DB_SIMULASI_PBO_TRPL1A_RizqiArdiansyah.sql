-- Pembuatan database jika belum ada
CREATE DATABASE IF NOT EXISTS db_pendaftaran;
USE db_pendaftaran;
-- Pembuatan tabel_pendaftaran terpusat (Single Table Inheritance)
CREATE TABLE IF NOT EXISTS `tabel_pendaftaran` (
    -- Atribut Global (Induk)
    `id_pendaftaran` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_calon` VARCHAR(150) NOT NULL,
    `asal_sekolah` VARCHAR(150) NOT NULL,
    `nilai_ujian` DOUBLE NOT NULL,
    `biaya_pendaftaran_dasar` DECIMAL(12, 2) NOT NULL,
    `jalur_pendaftaran` ENUM('Reguler', 'Prestasi', 'Kedinasan') NOT NULL,
    -- Atribut Spesifik (Anak - Set Menjadi Nullable / Boleh NULL)
    `pilihan_prodi` VARCHAR(100) DEFAULT NULL,       -- Spesifik untuk Reguler
    `lokasi_kampus` VARCHAR(100) DEFAULT NULL,      -- Spesifik untuk Reguler
    `jenis_prestasi` VARCHAR(100) DEFAULT NULL,     -- Spesifik untuk Prestasi
    `tingkat_prestasi` VARCHAR(100) DEFAULT NULL,   -- Spesifik untuk Prestasi
    `sk_ikatan_dinas` VARCHAR(100) DEFAULT NULL,    -- Spesifik untuk Kedinasan
    `instansi_sponsor` VARCHAR(150) DEFAULT NULL    -- Spesifik untuk Kedinasan
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Memasukkan minimal 20 data sampel untuk masing-masing jalur pendaftaran
INSERT INTO `tabel_pendaftaran` (
    `nama_calon`, `asal_sekolah`, `nilai_ujian`, `biaya_pendaftaran_dasar`, `jalur_pendaftaran`,
    `pilihan_prodi`, `lokasi_kampus`, `jenis_prestasi`, `tingkat_prestasi`, `sk_ikatan_dinas`, `instansi_sponsor`
) VALUES 
-- Jalur Reguler (pilihan_prodi & lokasi_kampus terisi, lainnya NULL)
('Ahmad Fauzi', 'SMAN 1 Jakarta', 85.50, 100000.00, 'Reguler', 'Teknik Informatika', 'Kampus Utama', NULL, NULL, NULL, NULL),
('Budi Santoso', 'SMAN 3 Bandung', 78.00, 100000.00, 'Reguler', 'Sistem Informasi', 'Kampus Utama', NULL, NULL, NULL, NULL),
('Citra Lestari', 'SMA Kristen 1 Tomohon', 92.00, 100000.00, 'Reguler', 'Teknologi Rekayasa Perangkat Lunak', 'Kampus B', NULL, NULL, NULL, NULL),
('Dewi Sartika', 'SMAN 2 Surabaya', 88.50, 100000.00, 'Reguler', 'Teknik Elektro', 'Kampus Utama', NULL, NULL, NULL, NULL),
('Eko Prasetyo', 'SMKN 1 Semarang', 80.00, 100000.00, 'Reguler', 'Teknik Mesin', 'Kampus C', NULL, NULL, NULL, NULL),
('Fitri Handayani', 'SMA Al-Azhar Jakarta', 89.00, 100000.00, 'Reguler', 'Akuntansi', 'Kampus Utama', NULL, NULL, NULL, NULL),
('Gilang Dirga', 'SMAN 8 Yogyakarta', 83.50, 100000.00, 'Reguler', 'Manajemen Bisnis', 'Kampus B', NULL, NULL, NULL, NULL),
-- Jalur Prestasi (jenis_prestasi & tingkat_prestasi terisi, lainnya NULL)
('Hadi Wijaya', 'SMAN 1 Medan', 95.00, 150000.00, 'Prestasi', NULL, NULL, 'Olimpiade Matematika', 'Nasional', NULL, NULL),
('Indah Permata', 'SMAN 5 Bogor', 91.50, 150000.00, 'Prestasi', NULL, NULL, 'Lomba Karya Ilmiah Remaja', 'Provinsi', NULL, NULL),
('Joko Widodo', 'SMAN 1 Surakarta', 87.00, 150000.00, 'Prestasi', NULL, NULL, 'Piala Menpora Sepakbola', 'Nasional', NULL, NULL),
('Kartika Putri', 'SMA Taruna Nusantara', 93.50, 150000.00, 'Prestasi', NULL, NULL, 'Olimpiade Fisika', 'Nasional', NULL, NULL),
('Lutfi Hakim', 'MAN 2 Pekanbaru', 89.50, 150000.00, 'Prestasi', NULL, NULL, 'Hafiz Quran 30 Juz', 'Nasional', NULL, NULL),
('Megawati Soekarno', 'SMAN 1 Denpasar', 90.00, 150000.00, 'Prestasi', NULL, NULL, 'Lomba Tari Tradisional', 'Internasional', NULL, NULL),
('Naufal Rizqi', 'SMAN 3 Malang', 94.00, 150000.00, 'Prestasi', NULL, NULL, 'Lomba Web Design', 'Provinsi', NULL, NULL),
-- Jalur Kedinasan (sk_ikatan_dinas & instansi_sponsor terisi, lainnya NULL)
('Oki Setiana', 'SMAN 1 Padang', 88.00, 200000.00, 'Kedinasan', NULL, NULL, NULL, NULL, 'SK-990/IKD/2026', 'Kementerian Perhubungan'),
('Putra Pratama', 'SMAN 2 Balikpapan', 86.50, 200000.00, 'Kedinasan', NULL, NULL, NULL, NULL, 'SK-102/DINAS/2026', 'Kementerian Komunikasi dan Informatika'),
('Qori Sandioriva', 'SMAN 1 Banda Aceh', 90.50, 200000.00, 'Kedinasan', NULL, NULL, NULL, NULL, 'SK-342/IKD/2026', 'Pemerintah Provinsi Aceh'),
('Rian Adianto', 'SMAN 4 Bandung', 84.00, 200000.00, 'Kedinasan', NULL, NULL, NULL, NULL, 'SK-775/DINAS/2026', 'Badan Siber dan Sandi Negara'),
('Siti Aminah', 'MAN 1 Makassar', 87.50, 200000.00, 'Kedinasan', NULL, NULL, NULL, NULL, 'SK-881/IKD/2026', 'Kementerian Keuangan'),
('Taufik Hidayat', 'SMAN 1 Cirebon', 85.00, 200000.00, 'Kedinasan', NULL, NULL, NULL, NULL, 'SK-204/DINAS/2026', 'Kementerian Pemuda dan Olahraga');