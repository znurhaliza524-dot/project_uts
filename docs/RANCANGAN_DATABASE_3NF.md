# 📐 Perancangan Normalisasi Basis Data (1NF s/d 3NF) — Disempurnakan
**Sistem Informasi Pengolahan Data Perpustakaan Keliling**
*(Dinas Perpustakaan dan Kearsipan Lampung Timur)*

---

## 🔎 Analisis Kekurangan Relasi Tabel Sebelumnya & Solusi Perbaikannya

Setelah dilakukan peninjauan mendalam dari sudut pandang **Kaidah Basis Data Lanjut (Integritas Data, Constraint, & Kebutuhan Sistem Nyata)**, ditemukan 5 kekurangan penting pada relasi sebelumnya:

| No | Kekurangan Desain Lama | Dampak / Risiko Masalah | Solusi Perbaikan yang Diterapkan |
|---|---|---|---|
| **1** | **Tidak Ada `UNIQUE(id_ba, id_anggota)` di `detail_kunjungan`** | Siswa yang sama bisa terinput 2 kali atau lebih pada kunjungan yang sama (misal barcode ter-scan ganda). Akibatnya perhitungan jumlah pengunjung menjadi salah dan tidak valid. | Ditambahkan **`UNIQUE KEY uq_kunjungan_anggota (id_ba, id_anggota)`**. Database otomatis menolak jika ada data presensi duplikat di satu kegiatan. |
| **2** | **Kaidah Berita Acara Belum Sah (Hanya 1 Pihak)** | Berita Acara instansi pemerintahan wajib merupakan kesepakatan dua pihak. Sebelumnya hanya ada pihak pustakawan, tidak ada tanda tangan perwakilan sekolah. | Ditambahkan atribut legalitas: `nama_perwakilan_sekolah`, `nip_perwakilan_sekolah`, `waktu_mulai`, dan `waktu_selesai`. |
| **3** | **Atribut Anggota Kurang untuk Kebutuhan Laporan Dinas** | Laporan tahunan perpustakaan selalu membutuhkan rekapitulasi pembaca berdasarkan jenis kelamin (L/P), status keaktifan siswa, dan nomor registrasi kartu. | Ditambahkan kolom: `nomor_anggota` (Unique untuk kartu anggota), `jenis_kelamin` (ENUM 'L','P'), dan `status_anggota` ('aktif','lulus','pindah'). |
| **4** | **Belum Ada Indexing untuk Kecepatan Pencarian** | Rumusan Masalah BAB I menyebutkan pustakawan lambat mencari data. Tabel sebelumnya belum diindeks kolom pencariannya. | Ditambahkan **Index** pada `nama_anggota`, `nama_sekolah`, `id_sekolah`, dan `tgl_kunjungan` untuk mempercepat query pencarian (*search performance*). |
| **5** | **Integritas Penghapusan (`ON DELETE`) Kurang Tepat** | Jika data sekolah/pustakawan yang memiliki riwayat kunjungan dihapus tidak sengaja, data historis bisa hilang mendadak atau menjadi tidak valid. | Diterapkan aturan `ON DELETE RESTRICT` pada master data penting agar riwayat kunjungan tidak bisa terhapus sembarangan tanpa verifikasi. |

---

## 📋 Spesifikasi Struktur Tabel Final (Standar 3NF Sempurna)

### 1. Tabel: `sekolah` (Master Sekolah Tujuan Kunjungan)
| Kolom | Tipe Data | Kunci | Keterangan |
|---|---|:---:|---|
| `id_sekolah` | BIGINT UNSIGNED | **PK** | Primary Key (Auto Increment) |
| `npsn` | VARCHAR(20) | Unique | Nomor Pokok Sekolah Nasional |
| `nama_sekolah` | VARCHAR(100) | Index | Nama Resmi Sekolah / Madrasah |
| `nama_kepsek` | VARCHAR(100) | - | Nama Kepala Sekolah |
| `alamat` | TEXT | - | Alamat Lengkap Sekolah |
| `kecamatan` | VARCHAR(50) | - | Wilayah Kecamatan di Kab. Lampung Timur |
| `kontak_sekolah` | VARCHAR(25) | - | Nomor Telepon / WA Sekolah |
| `created_at` | TIMESTAMP | - | Waktu Dibuat |
| `updated_at` | TIMESTAMP | - | Waktu Diperbarui |

---

### 2. Tabel: `pustakawan` (Master Petugas Perpustakaan Keliling)
| Kolom | Tipe Data | Kunci | Keterangan |
|---|---|:---:|---|
| `id_pustakawan` | BIGINT UNSIGNED | **PK** | Primary Key (Auto Increment) |
| `nip` | VARCHAR(30) | Unique | Nomor Induk Pegawai |
| `nama` | VARCHAR(100) | Index | Nama Lengkap Petugas |
| `jabatan` | VARCHAR(50) | - | Jabatan Struktural / Fungsional Petugas |
| `no_telp` | VARCHAR(20) | - | Nomor Kontak / WA Petugas |
| `created_at` | TIMESTAMP | - | Waktu Dibuat |
| `updated_at` | TIMESTAMP | - | Waktu Diperbarui |

---

### 3. Tabel: `users` (Autentikasi Akun Petugas & Admin)
| Kolom | Tipe Data | Kunci | Keterangan |
|---|---|:---:|---|
| `id_user` | BIGINT UNSIGNED | **PK** | Primary Key (Auto Increment) |
| `id_pustakawan` | BIGINT UNSIGNED | **FK / Unique** | Foreign Key -> `pustakawan(id_pustakawan)` (1:1) |
| `username` | VARCHAR(50) | Unique | Username Login |
| `password` | VARCHAR(255) | - | Hash Password Bcrypt Standar Laravel |
| `role` | ENUM('admin','pustakawan') | - | Hak Akses Pengguna |
| `status_aktif` | BOOLEAN | - | Status Akun (Aktif/Nonaktif) |
| `created_at` | TIMESTAMP | - | Waktu Dibuat |
| `updated_at` | TIMESTAMP | - | Waktu Diperbarui |

---

### 4. Tabel: `anggota` (Master Siswa / Pembaca di Sekolah)
| Kolom | Tipe Data | Kunci | Keterangan |
|---|---|:---:|---|
| `id_anggota` | BIGINT UNSIGNED | **PK** | Primary Key (Auto Increment) |
| `id_sekolah` | BIGINT UNSIGNED | **FK / Index** | Foreign Key -> `sekolah(id_sekolah)` |
| `nomor_anggota` | VARCHAR(30) | Unique | Nomor Kartu Anggota Perpustakaan Keliling |
| `nisn` | VARCHAR(20) | - | NISN Siswa |
| `nama` | VARCHAR(100) | Index | Nama Lengkap Siswa |
| `jenis_kelamin` | ENUM('L', 'P') | - | L = Laki-laki, P = Perempuan (Laporan Tahunan) |
| `kelas` | VARCHAR(20) | - | Kelas Siswa (contoh: IV A, V B) |
| `alamat` | TEXT | - | Alamat Tempat Tinggal Siswa |
| `status_anggota` | ENUM('aktif','lulus','pindah') | - | Status Keanggotaan Siswa |
| `created_at` | TIMESTAMP | - | Waktu Dibuat |
| `updated_at` | TIMESTAMP | - | Waktu Diperbarui |

---

### 5. Tabel: `berita_acara` (Transaksi Kunjungan Mobil Keliling)
| Kolom | Tipe Data | Kunci | Keterangan |
|---|---|:---:|---|
| `id_ba` | BIGINT UNSIGNED | **PK** | Primary Key (Auto Increment) |
| `nomor_ba` | VARCHAR(50) | Unique | Nomor Surat Resmi Berita Acara |
| `id_sekolah` | BIGINT UNSIGNED | **FK** | Foreign Key -> `sekolah(id_sekolah)` |
| `id_pustakawan` | BIGINT UNSIGNED | **FK** | Foreign Key -> `pustakawan(id_pustakawan)` |
| `tgl_kunjungan` | DATE | Index | Tanggal Mobil Keliling Berkunjung |
| `waktu_mulai` | TIME | - | Waktu Mulai Layanan |
| `waktu_selesai` | TIME | - | Waktu Selesai Layanan |
| `nama_perwakilan_sekolah` | VARCHAR(100) | - | Guru / Kepala Sekolah yang Menerima |
| `nip_perwakilan_sekolah` | VARCHAR(30) | - | NIP Perwakilan Sekolah (Opsional) |
| `catatan` | TEXT | - | Resume / Laporan Singkat Kegiatan |
| `created_at` | TIMESTAMP | - | Waktu Dibuat |
| `updated_at` | TIMESTAMP | - | Waktu Diperbarui |

---

### 6. Tabel: `detail_kunjungan` (Presensi Siswa Saat Kunjungan)
| Kolom | Tipe Data | Kunci | Keterangan |
|---|---|:---:|---|
| `id_detail` | BIGINT UNSIGNED | **PK** | Primary Key (Auto Increment) |
| `id_ba` | BIGINT UNSIGNED | **FK / Index** | Foreign Key -> `berita_acara(id_ba)` |
| `id_anggota` | BIGINT UNSIGNED | **FK / Index** | Foreign Key -> `anggota(id_anggota)` |
| `waktu_hadir` | TIMESTAMP | - | Waktu Siswa Memasuki Mobil Keliling |

> 🔒 **Constraint Integritas:**
> `UNIQUE KEY (id_ba, id_anggota)` menjamin bahwa seorang siswa hanya dapat diabsen 1 kali pada sesi kunjungan yang sama.

---

## 📊 Diagram Relasi Entitas (ERD 3NF Disempurnakan)

```mermaid
erDiagram
    SEKOLAH ||--o{ ANGGOTA : "1:N (id_sekolah)"
    SEKOLAH ||--o{ BERITA_ACARA : "1:N (id_sekolah)"
    PUSTAKAWAN ||--o{ BERITA_ACARA : "1:N (id_pustakawan)"
    PUSTAKAWAN ||--o| USERS : "1:1 (id_pustakawan)"
    BERITA_ACARA ||--o{ DETAIL_KUNJUNGAN : "1:N (id_ba)"
    ANGGOTA ||--o{ DETAIL_KUNJUNGAN : "1:N (id_anggota)"

    SEKOLAH {
        bigint id_sekolah PK
        string npsn UK
        string nama_sekolah IDX
        string nama_kepsek
        text alamat
        string kecamatan
        string kontak_sekolah
    }

    PUSTAKAWAN {
        bigint id_pustakawan PK
        string nip UK
        string nama IDX
        string jabatan
        string no_telp
    }

    USERS {
        bigint id_user PK
        bigint id_pustakawan FK,UK
        string username UK
        string password
        enum role
        bool status_aktif
    }

    ANGGOTA {
        bigint id_anggota PK
        bigint id_sekolah FK,IDX
        string nomor_anggota UK
        string nisn
        string nama IDX
        enum jenis_kelamin
        string kelas
        enum status_anggota
    }

    BERITA_ACARA {
        bigint id_ba PK
        string nomor_ba UK
        bigint id_sekolah FK
        bigint id_pustakawan FK
        date tgl_kunjungan IDX
        time waktu_mulai
        time waktu_selesai
        string nama_perwakilan_sekolah
        string nip_perwakilan_sekolah
        text catatan
    }

    DETAIL_KUNJUNGAN {
        bigint id_detail PK
        bigint id_ba FK,IDX
        bigint id_anggota FK,IDX
        timestamp waktu_hadir
    }
```
