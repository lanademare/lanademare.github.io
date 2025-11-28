# Aplikasi Web To-Do List Sederhana (PHP & MySQL)

Proyek ini adalah aplikasi web dasar untuk mengelola daftar tugas harian. Dibangun sebagai proyek mandiri untuk memahami alur kerja penuh (Full-Stack) dalam pengembangan web, mulai dari antarmuka pengguna hingga manajemen database.

## 💻 Teknologi yang Digunakan

- **Frontend:** HTML5, CSS3, JavaScript
- **Styling/Framework:** Bootstrap (untuk tampilan yang responsif)
- **Backend:** PHP
- **Database:** MySQL
- **Server Lokal:** XAMPP

## ✨ Fungsionalitas Utama (CRUD)

Aplikasi ini berhasil mengimplementasikan siklus **CRUD (Create, Read, Update, Delete)** secara penuh:

1.  **Create (C):** Pengguna dapat menambahkan tugas baru ke dalam daftar.
2.  **Read (R):** Daftar semua tugas yang tersimpan di _database_ akan ditampilkan.
3.  **Update (U):** Pengguna dapat mengubah atau mengedit teks tugas yang sudah ada.
4.  **Delete (D):** Pengguna dapat menghapus tugas yang sudah selesai.

## ⚠️ Catatan Struktur Kode (Penting untuk Program Bootcamp)

Saat ini, keseluruhan logika _frontend_ (HTML, CSS) dan _backend_ (PHP, _database connection_) diorganisasi dalam **satu file (`index.php`)** untuk fokus utama pada fungsionalitas CRUD yang mendasar.

**Rencana Perbaikan Jangka Pendek (Refactoring):**

- Kode ini berada dalam proses **restrukturisasi (refactoring)**.
- Langkah selanjutnya adalah memisahkan kode CSS ke file `style.css` terpisah, dan memisahkan _database connection_ serta _logic_ CRUD ke modul/file PHP terpisah. Hal ini bertujuan untuk mengikuti prinsip **Separation of Concerns** dan praktik kode yang lebih baik.

## ⚙️ Cara Menjalankan Proyek (Lokal)

1.  Pastikan **XAMPP** sudah terinstal di komputer Anda.
2.  _Clone_ repositori ini ke folder `htdocs` XAMPP Anda.
3.  Buat _database_ baru di phpMyAdmin (db: `bootcamp_todolist2025`).
4.  Buat tabel `tasks` dengan kolom ID, `task`, `priority`,'due_date','status'.
5.  Jalankan Apache dan MySQL melalui XAMPP Control Panel.
6.  Akses melalui _browser:chrome_: `localhost/bootcamp_todolist2025/index.php`.

---
