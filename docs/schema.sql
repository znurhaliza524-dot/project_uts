-- ====================================================================
-- SISTEM PENGOLAHAN DATA ANGGOTA PERPUSTAKAAN KELILING
-- DINAS PERPUSTAKAAN DAN KEARSIPAN LAMPUNG TIMUR
-- Skema Database: Standar 3NF (Disempurnakan & Bebas Celah Integritas)
-- ====================================================================

CREATE DATABASE IF NOT EXISTS db_perpustakaan_keliling;
USE db_perpustakaan_keliling;

-- --------------------------------------------------------------------
-- 1. TABEL SEKOLAH (Master Data Sekolah Tujuan Kunjungan)
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS sekolah (
    id_sekolah BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    npsn VARCHAR(20) UNIQUE NULL COMMENT 'Nomor Pokok Sekolah Nasional',
    nama_sekolah VARCHAR(100) NOT NULL,
    nama_kepsek VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    kecamatan VARCHAR(50) NOT NULL DEFAULT 'Lampung Timur',
    kontak_sekolah VARCHAR(25) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nama_sekolah (nama_sekolah)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 2. TABEL PUSTAKAWAN (Master Data Petugas Perpustakaan Keliling)
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS pustakawan (
    id_pustakawan BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nip VARCHAR(30) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    jabatan VARCHAR(50) DEFAULT 'Petugas Layanan Keliling',
    no_telp VARCHAR(20) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nama_pustakawan (nama)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 3. TABEL USERS (Autentikasi Akun Petugas & Admin)
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id_user BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_pustakawan BIGINT UNSIGNED NULL UNIQUE COMMENT '1 Petugas maksimal memiliki 1 akun login',
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL COMMENT 'Bcrypt Hash',
    role ENUM('admin', 'pustakawan') DEFAULT 'pustakawan',
    status_aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_pustakawan FOREIGN KEY (id_pustakawan) 
        REFERENCES pustakawan(id_pustakawan) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 4. TABEL ANGGOTA (Master Data Siswa Pembaca di Sekolah)
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS anggota (
    id_anggota BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_sekolah BIGINT UNSIGNED NOT NULL,
    nomor_anggota VARCHAR(30) UNIQUE NOT NULL COMMENT 'ID/No Kartu Anggota Perpustakaan',
    nisn VARCHAR(20) NULL,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL COMMENT 'L = Laki-laki, P = Perempuan (Kebutuhan Laporan Statistik)',
    kelas VARCHAR(20) NOT NULL,
    alamat TEXT NULL,
    status_anggota ENUM('aktif', 'lulus', 'pindah') DEFAULT 'aktif',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_anggota_sekolah FOREIGN KEY (id_sekolah) 
        REFERENCES sekolah(id_sekolah) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_nama_anggota (nama),
    INDEX idx_sekolah_anggota (id_sekolah)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 5. TABEL BERITA ACARA (Dokumen Legal Kunjungan Mobil Keliling)
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS berita_acara (
    id_ba BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nomor_ba VARCHAR(50) UNIQUE NOT NULL COMMENT 'Format Resmi: BA/TAHUN/BULAN/NO',
    id_sekolah BIGINT UNSIGNED NOT NULL,
    id_pustakawan BIGINT UNSIGNED NOT NULL,
    tgl_kunjungan DATE NOT NULL,
    waktu_mulai TIME NULL,
    waktu_selesai TIME NULL,
    nama_perwakilan_sekolah VARCHAR(100) NOT NULL COMMENT 'Pihak ke-2 (Guru/Kepsek penandatangan Berita Acara)',
    nip_perwakilan_sekolah VARCHAR(30) NULL,
    catatan TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_ba_sekolah FOREIGN KEY (id_sekolah) 
        REFERENCES sekolah(id_sekolah) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_ba_pustakawan FOREIGN KEY (id_pustakawan) 
        REFERENCES pustakawan(id_pustakawan) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_tgl_kunjungan (tgl_kunjungan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 6. TABEL DETAIL KUNJUNGAN (Presensi Siswa Saat Mobil Keliling Beroperasi)
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS detail_kunjungan (
    id_detail BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_ba BIGINT UNSIGNED NOT NULL,
    id_anggota BIGINT UNSIGNED NOT NULL,
    waktu_hadir TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_detail_ba FOREIGN KEY (id_ba) 
        REFERENCES berita_acara(id_ba) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_detail_anggota FOREIGN KEY (id_anggota) 
        REFERENCES anggota(id_anggota) ON DELETE RESTRICT ON UPDATE CASCADE,
    -- INTEGRITY FIX: Mencegah 1 siswa terinput 2 kali pada kunjungan yang sama!
    UNIQUE KEY uq_kunjungan_anggota (id_ba, id_anggota),
    INDEX idx_detail_ba (id_ba),
    INDEX idx_detail_anggota (id_anggota)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================================
-- DATA AWAL / SAMPLE PENGUJIAN (SDN 1 GUNUNG TIGA)
-- ====================================================================

-- 1. Insert Data Sekolah
INSERT INTO sekolah (id_sekolah, npsn, nama_sekolah, nama_kepsek, alamat, kecamatan, kontak_sekolah) VALUES
(1, '10801234', 'SDN 1 Gunung Tiga', 'Drs. H. Mulyadi', 'Desa Gunung Tiga', 'Batanghari Nuban', '081369001122');

-- 2. Insert Data Pustakawan
INSERT INTO pustakawan (id_pustakawan, nip, nama, jabatan, no_telp) VALUES
(1, '198501012010011005', 'Dedi Irawan, S.Kom., M.T.I', 'Koordinator Pustakawan Keliling', '081234567890');

-- 3. Insert Akun Login User
INSERT INTO users (id_user, id_pustakawan, username, password, role) VALUES
(1, 1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- 4. Insert Data Anggota (Siswa)
INSERT INTO anggota (id_anggota, id_sekolah, nomor_anggota, nisn, nama, jenis_kelamin, kelas, alamat, status_anggota) VALUES
(1, 1, 'AGT-2026-0001', '0081234561', 'Ahmad Fauzi', 'L', 'IV A', 'Dusun 1, Gunung Tiga', 'aktif'),
(2, 1, 'AGT-2026-0002', '0081234562', 'Siti Rahmawati', 'P', 'IV A', 'Dusun 2, Gunung Tiga', 'aktif'),
(3, 1, 'AGT-2026-0003', '0081234563', 'Budi Santoso', 'L', 'V B', 'Dusun 1, Gunung Tiga', 'aktif');

-- 5. Insert Berita Acara Kunjungan
INSERT INTO berita_acara (id_ba, nomor_ba, id_sekolah, id_pustakawan, tgl_kunjungan, waktu_mulai, waktu_selesai, nama_perwakilan_sekolah, nip_perwakilan_sekolah, catatan) VALUES
(1, 'BA/2026/XI/001', 1, 1, '2026-11-05', '08:30:00', '12:00:00', 'Siti Maryam, S.Pd', '198902022015022001', 'Pelayanan membaca di perpustakaan keliling berjalan tertib dan antusias.');

-- 6. Insert Presensi Detail Kunjungan
INSERT INTO detail_kunjungan (id_ba, id_anggota) VALUES
(1, 1),
(1, 2),
(1, 3);
