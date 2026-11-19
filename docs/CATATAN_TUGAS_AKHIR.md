# 📚 Catatan Tugas Akhir — Putri Damayanti

## 🔖 Identitas Dokumen

| Field | Detail |
|---|---|
| **Judul** | Perancangan Aplikasi Pengolahan Data Anggota Perpustakaan Keliling Pada Dinas Perpustakaan dan Kearsipan Lampung Timur |
| **Jenis** | Tugas Akhir (D-III) |
| **Penulis** | Putri Damayanti |
| **NIM** | 16410031 |
| **Program Studi** | D-III Sistem Informasi |
| **Fakultas** | Fakultas Ilmu Komputer (FIKOM) |
| **Universitas** | Universitas Muhammadiyah Metro |
| **Tahun** | 2019 |
| **Pembimbing 1** | Dedi Irawan, S.Kom., M.T.I |
| **Pembimbing 2** | Darmawan, S.Kom., M.M |
| **Email** | yantiputri.dhama05@gmail.com |

---

## 🔑 Kata Kunci
Aplikasi · Perpustakaan Keliling · Java · Database XAMPP v3.2.2 · Dinas Perpustakaan dan Kearsipan Lampung Timur

---

## 📝 Isi Abstrak

### Latar Belakang Masalah
- Pengolahan data **sekolah** dan **anggota perpustakaan keliling** masih dilakukan secara **manual** (dalam bentuk berita acara)
- Risiko **dokumen hilang atau rusak**
- Pustakawan **kesulitan mencari data** sekolah maupun anggota
- Proses **pembuatan laporan** memakan waktu lama

### Metode Penelitian
- Pengamatan
- Wawancara
- Dokumentasi
- Studi pustaka

### Perancangan Sistem
- **Diagram:** Flowchart, DFD (Data Flow Diagram), ERD (Entity Relationship Diagram)
- **Tools/Software:**
  - NetBeans IDE 8.2
  - Bahasa pemrograman: **Java**
  - Laporan: **JasperReport**
  - Database: **MySQL**

### Input & Output Sistem
| Jenis | Detail |
|---|---|
| **Input** | Registrasi anggota, registrasi sekolah, registrasi pustakawan, berita acara |
| **Output** | Berita acara & laporan jumlah anggota perpustakaan keliling |

---

## 🙏 Kata Pengantar — Pihak yang Diapresiasi
1. **Drs. H. Jazim Ahmad, M.Pd** — Rektor Universitas Muhammadiyah Metro
2. **Sudarmaji, S.Kom., M.M** — Dekan Fakultas Ilmu Komputer UM Metro
3. **Dedi Irawan, S.Kom., M.T.I** — Ketua Prodi S1 Ilmu Komputer FIKOM UM Metro
4. **Mustika, S.Kom., M.Kom** — Ketua Prodi D-III Sistem Informasi FIKOM UM Metro
5. **Dedi Irawan & Dharmawan** — Pembimbing Utama & Kedua

---

> [!NOTE]
> Dokumen ini adalah Tugas Akhir D-III (bukan Skripsi S1). Sistem yang dirancang berbasis **Java + MySQL** untuk menggantikan pencatatan manual di perpustakaan keliling Lampung Timur.

---

## 📖 BAB I — PENDAHULUAN

### A. Latar Belakang Masalah

**Konteks umum:**
- Teknologi informasi dapat meningkatkan kinerja, kecepatan, akurasi, dan efisiensi kerja
- Perpustakaan keliling perlu sistem aplikasi manajemen pengolahan data (melibatkan komponen internal & eksternal)
- Tujuan: menunjang aktivitas perpustakaan keliling, mengolah data pengunjung dari seluruh masyarakat, meningkatkan kecerdasan masyarakat & tertib administrasi di Lampung Timur

**Profil Instansi:**
- **Dinas Perpustakaan dan Kearsipan Lampung Timur** mulai beroperasi: **04 Januari 2010**, dipimpin oleh **M. Juniar, S.Pd**
- Sempat berganti bentuk menjadi **KPAD** (Kantor Perpustakaan dan Kearsipan)
- Lalu menjadi **BPADD** (Badan Perpustakaan Daerah, Arsip dan Dokumentasi Daerah) sejak **01 Januari 2014**, dipimpin **Drs. Mulyanda**
- Dasar hukum: Perda No. 20 Tahun 2013 & Perbup No. 30 Tahun 2013

**Masalah yang ditemukan:**
- Pengolahan data sekolah & anggota perpustakaan keliling masih **manual** (berita acara)
- Pustakawan **kesulitan mencari** data sekolah maupun anggota
- **Lambat** dalam menghitung jumlah anggota
- Pembuatan laporan anggota perpustakaan keliling membutuhkan **waktu berhari-hari** → tidak efisien

---

### B. Rumusan Masalah

1. Bagaimana merancang **aplikasi pengolahan data sekolah & anggota** perpustakaan keliling yang efisien?
2. Bagaimana **mempercepat pencarian** data sekolah/anggota berdasarkan nama sekolah maupun nama anggota?
3. Bagaimana **mempercepat pembuatan laporan** data anggota perpustakaan keliling per tahun?

---

### C. Batasan Masalah

1. Hanya membahas proses **pendataan anggota** perpustakaan keliling di Dinas Perpustakaan dan Kearsipan Lampung Timur
2. Pencarian data berdasarkan **nama anggota**; pelaporan dilakukan **1 tahun sekali**
3. Sampel data: **1 sekolah** yaitu **SDN 1 Gunung Tiga** beserta data anggotanya
4. Tools yang digunakan:
   - IDE: **NetBeans IDE 8.2**
   - Bahasa pemrograman: **Java**
   - Laporan: **iReport**
   - Database: **MySQL**

---

### D. Tujuan Penelitian

1. Merancang aplikasi pengolahan data sekolah & anggota perpustakaan keliling yang **efisien**
2. Mempercepat proses **pencarian data** sekolah dan anggota berdasarkan nama
3. Mempercepat proses **pembuatan laporan** data anggota per tahun

---

### E. Kegunaan Penelitian

| Pihak | Manfaat |
|---|---|
| **Universitas** | Bahan referensi pembelajaran mahasiswa D-III Sistem Informasi FIKOM UM Metro |
| **Instansi** | ① Mempermudah pengolahan data anggota ② Menjaga data agar tidak hilang & tersimpan rapi ③ Mempermudah pembuatan laporan tahunan |
| **Penulis** | Melatih dalam pembuatan tugas akhir & membandingkan teori dengan praktik |

---

> [!IMPORTANT]
> **Kesimpulan Kebutuhan Sistem (dari BAB I):**
> Sistem harus mampu:
> - Input & simpan data **sekolah**, **anggota**, **pustakawan**, dan **berita acara**
> - **Pencarian** data berdasarkan nama anggota / nama sekolah
> - Generate **laporan tahunan** jumlah anggota perpustakaan keliling
> - Menggantikan sistem manual yang lambat dan rawan kehilangan data
