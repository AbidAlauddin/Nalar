# Nalar — Project Progress

## 1. Current Status

- **Current Phase:** Core LMS Development
- **Current Priority:** Verify and complete the dashboard, then continue core LMS features
- **Last Updated:** YYYY-MM-DD
- **Overall Status:** In Progress

> This document tracks the actual implementation status of Nalar. All statuses must be based on repository inspection and available verification results.

## 2. Feature Progress

| Feature | Status | Completion Criteria | Notes |
|---|---|---|---|
| Welcome Page | NEEDS VERIFICATION | Public landing page works and is responsive | Verify current implementation |
| Authentication | NEEDS VERIFICATION | Login, registration, logout, and access restrictions work | Verify current implementation |
| Dashboard | NEEDS VERIFICATION | Dashboard displays appropriate data and works responsively | Verify current implementation |
| Learning Materials | NOT STARTED | Users can create, view, edit, and manage learning materials as specified | |
| Categories | NOT STARTED | Categories can be managed and associated with learning materials | |
| Question Bank | NOT STARTED | Questions can be created and managed | |
| Quizzes | NOT STARTED | Users can take quizzes and see results | |
| AI Content Generation | NOT STARTED | AI-assisted generation works with appropriate validation and error handling | |
| Learning Progress | NOT STARTED | Learning progress is recorded and displayed accurately | |
| Responsive UI | NEEDS VERIFICATION | Core pages work across supported screen sizes | |
| Automated Testing | NEEDS VERIFICATION | Relevant tests exist and pass | |
| Deployment | NOT STARTED | Application is deployed and operational in the target environment | |

## 3. Recent Changes

### YYYY-MM-DD — Initial Progress Documentation
- Created or updated project progress tracking.
- Inspected the repository and recorded the findings.
- Validation: Record the actual checks performed.

## 4. Known Issues

- Add only verified unresolved issues.
- Include reproduction details when useful.
- Remove issues after they have been resolved and verified.

## 5. Next Steps

1. Verify the current dashboard implementation.
2. Resolve existing blockers before adding unrelated features.
3. Continue implementing the core LMS functionality.
4. Add and run relevant tests as features are completed.

## 6. Status Definitions

- **NOT STARTED:** Implementation has not started.
- **IN PROGRESS:** Implementation is underway or partially complete.
- **COMPLETED:** Defined requirements have been implemented and appropriately verified.
- **BLOCKED:** Work cannot proceed because of an identified blocker.
- **NEEDS VERIFICATION:** Implementation may exist, but required checks have not yet confirmed its status.

**Nalar** adalah platform pembelajaran berbasis web yang dirancang untuk membantu pengguna memahami materi secara lebih mendalam melalui materi pembelajaran, bantuan AI, dan kuis interaktif.

Fokus pengembangan Nalar adalah membangun pengalaman belajar yang sederhana, mudah digunakan, responsif, dan efisien. Fitur gamifikasi akan dikembangkan pada tahap lanjutan setelah fitur inti platform selesai.

---

# Nalar — Learning Management System

## 1. Informasi Proyek

| Informasi | Detail |
|---|---|
| Nama aplikasi | Nalar |
| Jenis aplikasi | Learning Management System (LMS) |
| Platform | Website |
| Backend | Laravel |
| Frontend | Laravel Blade |
| Styling | Tailwind CSS, jika tersedia pada instalasi proyek |
| Database | MySQL |
| Authentication | Laravel Fortify |
| Local development | Laravel Sail dan Docker |
| Bahasa antarmuka | Bahasa Indonesia |

## 2. Status Pengembangan

- [x] Membuat proyek Laravel Nalar
- [x] Menyiapkan lingkungan development menggunakan Laravel Sail
- [x] Menghubungkan aplikasi dengan MySQL
- [x] Menjalankan migrasi database awal
- [x] Mengonfigurasi Laravel Fortify
- [x] Mengaktifkan halaman login
- [x] Mengaktifkan halaman registrasi
- [x] Mengarahkan pengguna setelah login atau registrasi ke `/dashboard`
- [ ] Membuat welcome page publik
- [ ] Membangun fitur inti LMS
- [ ] Mengintegrasikan bantuan AI
- [ ] Membuat sistem kuis
- [ ] Menyelesaikan pengujian dan deployment

> Checklist ini merupakan status awal berdasarkan progres pengembangan yang sudah diketahui. Perbarui status sesuai implementasi dan hasil pengujian aktual.

### Keterangan status

- `[x]` Selesai dan telah diverifikasi.
- `[ ]` Belum selesai atau belum diverifikasi.
- Fitur yang sedang dikerjakan dapat diberi label **In Progress** pada bagian progres modul.

---

# 3. Roadmap Pengembangan

## Fase 1 — Fondasi Aplikasi

**Prioritas: P0 — Wajib**

- [x] Membuat proyek Laravel
- [x] Menyiapkan Docker dan Laravel Sail
- [x] Mengonfigurasi koneksi database MySQL
- [x] Menjalankan migrasi database
- [x] Memasang dan mengonfigurasi Laravel Fortify
- [x] Membuat alur login
- [x] Membuat alur registrasi
- [ ] Memastikan halaman dashboard hanya dapat diakses oleh pengguna yang login
- [ ] Memastikan pengguna yang belum login diarahkan ke halaman login ketika mengakses halaman privat
- [ ] Memastikan pengguna yang sudah login tidak perlu mengakses login atau registrasi kembali
- [ ] Memastikan logout berfungsi
- [ ] Memeriksa validasi input dan penanganan error
- [ ] Memastikan konfigurasi `.env` dan kredensial database tidak masuk ke Git
- [ ] Menyiapkan struktur folder dan konvensi penamaan kode

**Kriteria selesai:**
- Aplikasi dapat dijalankan secara lokal.
- Login, registrasi, dan logout bekerja.
- Route privat terlindungi middleware autentikasi.
- Database dapat diakses tanpa error.

## Fase 2 — Welcome Page dan Identitas Nalar

**Prioritas: P0 — Wajib**

### 2.1 Halaman welcome

- [ ] Membuat halaman pengenalan Nalar pada route `/`
- [ ] Membuat navbar yang responsif
- [ ] Menambahkan logo dan identitas Nalar
- [ ] Membuat hero section dengan headline dan deskripsi
- [ ] Menambahkan CTA “Mulai Belajar” dan “Masuk”
- [ ] Membuat section pengenalan Nalar
- [ ] Membuat section fitur utama
- [ ] Membuat section cara kerja platform
- [ ] Membuat section manfaat bantuan AI
- [ ] Menambahkan CTA akhir sebelum footer
- [ ] Membuat footer dengan navigasi yang relevan
- [ ] Menghubungkan tombol masuk ke `route('login')`
- [ ] Menghubungkan tombol daftar ke `route('register')`
- [ ] Menampilkan tombol dashboard untuk pengguna yang sudah login
- [ ] Memastikan halaman dapat diakses tanpa autentikasi

### 2.2 Identitas visual

Gunakan color palette berikut secara konsisten.

| Token | Warna | Penggunaan |
|---|---|---|
| Primary | `#EA580C` | Tombol utama, CTA, elemen penting |
| Accent | `#FB923C` | Aksen visual dan interaksi |
| Soft Orange | `#FFEDD5` | Latar section dan elemen pendukung |
| Background | `#FFFCF8` | Latar utama website |
| Text | `#292524` | Teks utama |
| Highlight | `#2563EB` | Informasi atau elemen yang membutuhkan penekanan berbeda |

- [ ] Membuat sistem warna yang konsisten
- [ ] Menggunakan tipografi yang mudah dibaca
- [ ] Menghindari penggunaan gradient
- [ ] Menghindari animasi berlebihan
- [ ] Membuat desain mobile, tablet, dan desktop
- [ ] Memastikan kontras warna dan keterbacaan
- [ ] Menambahkan hover dan focus state pada elemen interaktif

**Kriteria selesai:** Halaman `/` menjadi halaman publik yang memperkenalkan Nalar, memiliki CTA yang berfungsi, dan tidak mengganggu alur autentikasi yang sudah berjalan.

## Fase 3 — Dashboard Pengguna

**Prioritas: P0 — Wajib**

- [ ] Membuat layout utama dashboard
- [ ] Membuat navbar atau sidebar sesuai kebutuhan
- [ ] Menampilkan nama pengguna yang sedang login
- [ ] Membuat ringkasan aktivitas belajar
- [ ] Menampilkan materi yang tersedia
- [ ] Menampilkan materi yang terakhir dipelajari
- [ ] Menampilkan ringkasan hasil kuis
- [ ] Menambahkan navigasi ke materi, kuis, dan bantuan AI
- [ ] Membuat empty state ketika pengguna belum memiliki aktivitas
- [ ] Menambahkan loading dan error state jika diperlukan
- [ ] Memastikan data dashboard sesuai dengan pengguna yang login

**Kriteria selesai:** Pengguna dapat melihat halaman utama setelah login dan menavigasi ke fitur pembelajaran.

## Fase 4 — Sistem Materi Pembelajaran

**Prioritas: P0 — Wajib**

### 4.1 Pengelolaan materi

- [ ] Menentukan struktur data materi
- [ ] Membuat migration dan model untuk materi
- [ ] Menentukan relasi kategori dan materi jika diperlukan
- [ ] Membuat daftar materi
- [ ] Membuat halaman detail materi
- [ ] Menampilkan judul dan deskripsi materi
- [ ] Menampilkan konten pembelajaran
- [ ] Menambahkan kategori atau topik materi
- [ ] Membuat pencarian materi
- [ ] Membuat filter materi berdasarkan kategori
- [ ] Menambahkan pagination jika daftar materi panjang
- [ ] Menambahkan validasi dan penanganan materi yang tidak ditemukan

### 4.2 Pengalaman belajar

- [ ] Membuat tampilan membaca materi yang nyaman
- [ ] Membuat navigasi antarbagian materi jika diperlukan
- [ ] Menyediakan penanda materi yang sedang dipelajari
- [ ] Menyimpan progres belajar pengguna
- [ ] Menampilkan status belum dimulai, sedang dipelajari, atau selesai
- [ ] Menghubungkan progres belajar dengan dashboard
- [ ] Memastikan progres tersimpan di database

**Kriteria selesai:** Pengguna dapat menemukan materi, membuka konten, belajar, dan melihat progresnya kembali.

## Fase 5 — Bantuan AI

**Prioritas: P1 — Fitur inti setelah fondasi dan materi**

### 5.1 Integrasi AI

- [ ] Menentukan provider dan model AI yang akan digunakan
- [ ] Menyiapkan API key melalui environment variable
- [ ] Membuat service khusus untuk komunikasi dengan AI
- [ ] Membuat backend endpoint untuk permintaan AI
- [ ] Memvalidasi input pengguna
- [ ] Mengatur batas panjang input dan output
- [ ] Menangani timeout dan kegagalan provider
- [ ] Menambahkan loading state pada antarmuka
- [ ] Menangani respons kosong atau error
- [ ] Memastikan API key tidak pernah dikirim ke frontend

### 5.2 Antarmuka bantuan AI

- [ ] Membuat halaman atau panel bantuan AI
- [ ] Membuat input pertanyaan
- [ ] Menampilkan percakapan antara pengguna dan AI
- [ ] Menambahkan indikator proses ketika AI sedang menjawab
- [ ] Membuat tombol untuk mengirim pertanyaan
- [ ] Menambahkan tampilan respons yang mudah dibaca
- [ ] Menyediakan opsi untuk mengajukan pertanyaan lanjutan
- [ ] Menghubungkan bantuan AI dengan konteks materi yang sedang dipelajari
- [ ] Membatasi akses dan penggunaan sesuai kebijakan aplikasi

### 5.3 Kualitas respons AI

- [ ] Membuat instruksi sistem agar AI berperan sebagai asisten belajar
- [ ] Mengarahkan AI untuk menjelaskan konsep dengan bahasa sederhana
- [ ] Mendukung contoh dan penjelasan bertahap
- [ ] Mendorong pemahaman, bukan sekadar memberikan jawaban akhir
- [ ] Menguji respons untuk berbagai jenis pertanyaan
- [ ] Mengevaluasi biaya penggunaan dan latensi respons

**Kriteria selesai:** Pengguna dapat mengajukan pertanyaan dan memperoleh penjelasan AI melalui backend yang aman.

## Fase 6 — Sistem Kuis Interaktif

**Prioritas: P1 — Fitur inti**

Kuis tahap awal menggunakan konsep sederhana seperti Quizizz, dengan fokus pada pengerjaan soal dan hasil evaluasi. Fitur kompetisi dan gamifikasi lanjutan tidak menjadi persyaratan MVP.

### 6.1 Struktur kuis

- [ ] Menentukan model dan migration kuis
- [ ] Membuat model pertanyaan
- [ ] Membuat model pilihan jawaban
- [ ] Membuat relasi antara materi, kuis, dan pertanyaan jika diperlukan
- [ ] Menentukan jenis soal awal, yaitu pilihan ganda
- [ ] Menentukan aturan penilaian
- [ ] Menentukan batas waktu jika diperlukan

### 6.2 Pengalaman mengerjakan kuis

- [ ] Membuat daftar kuis
- [ ] Membuat halaman instruksi kuis
- [ ] Membuat tampilan soal dan pilihan jawaban
- [ ] Menambahkan navigasi antarsoal
- [ ] Menampilkan nomor soal dan progres pengerjaan
- [ ] Menyimpan jawaban pengguna
- [ ] Membuat tombol kirim atau selesaikan kuis
- [ ] Menangani konfirmasi sebelum kuis dikumpulkan
- [ ] Mencegah pengiriman ganda yang tidak disengaja
- [ ] Menghitung skor di backend
- [ ] Menampilkan hasil setelah kuis selesai

### 6.3 Evaluasi hasil

- [ ] Menampilkan skor akhir
- [ ] Menampilkan jumlah jawaban benar dan salah
- [ ] Menampilkan pembahasan atau jawaban yang benar jika tersedia
- [ ] Menyimpan riwayat pengerjaan kuis
- [ ] Menampilkan riwayat hasil kuis di dashboard
- [ ] Menguji perhitungan skor dan validasi jawaban
- [ ] Memastikan jawaban benar tidak dapat dimanipulasi melalui frontend

**Kriteria selesai:** Pengguna dapat mengerjakan kuis, mengirim jawaban, memperoleh skor yang dihitung oleh backend, dan melihat hasilnya kembali.

## Fase 7 — Progres dan Riwayat Belajar

**Prioritas: P1 — Setelah materi dan kuis tersedia**

- [ ] Membuat struktur data progres belajar
- [ ] Menyimpan status penyelesaian materi
- [ ] Menyimpan waktu atau tanggal aktivitas jika dibutuhkan
- [ ] Menyimpan riwayat hasil kuis
- [ ] Menghitung ringkasan aktivitas belajar
- [ ] Menampilkan materi yang telah diselesaikan
- [ ] Menampilkan hasil kuis sebelumnya
- [ ] Menghubungkan ringkasan dengan dashboard
- [ ] Menguji konsistensi data progres

**Kriteria selesai:** Aktivitas belajar tersimpan dan dapat ditampilkan kembali secara akurat.

## Fase 8 — Panel Admin dan Pengelolaan Konten

**Prioritas: P1 — Diperlukan jika konten dikelola melalui aplikasi**

- [ ] Menentukan role pengguna dan hak akses
- [ ] Membuat middleware atau policy untuk akses admin
- [ ] Membuat halaman dashboard admin
- [ ] Membuat CRUD materi pembelajaran
- [ ] Membuat CRUD kategori
- [ ] Membuat CRUD kuis
- [ ] Membuat CRUD pertanyaan dan pilihan jawaban
- [ ] Membuat validasi input pada setiap form
- [ ] Menambahkan konfirmasi penghapusan
- [ ] Membatasi akses pengelolaan konten kepada role yang berwenang
- [ ] Menguji akses langsung ke route admin tanpa izin

**Kriteria selesai:** Admin yang berwenang dapat mengelola konten tanpa memberikan akses pengelolaan kepada pengguna biasa.

## Fase 9 — Pengujian, Keamanan, dan Optimasi

**Prioritas: P0 — Sebelum rilis**

### Pengujian

- [ ] Menguji registrasi dan login
- [ ] Menguji logout
- [ ] Menguji perlindungan route privat
- [ ] Menguji halaman welcome pada kondisi guest dan authenticated
- [ ] Menguji CRUD materi
- [ ] Menguji pencarian dan filter
- [ ] Menguji penyimpanan progres belajar
- [ ] Menguji integrasi AI dan penanganan error
- [ ] Menguji pengerjaan kuis dan perhitungan skor
- [ ] Menguji hak akses admin
- [ ] Menambahkan automated test untuk alur penting
- [ ] Menjalankan test suite sebelum merge atau rilis

### Keamanan

- [ ] Memastikan validasi dilakukan di backend
- [ ] Memastikan route privat dilindungi middleware
- [ ] Memastikan data pengguna tidak dapat diakses pengguna lain tanpa izin
- [ ] Memastikan API key dan kredensial tidak terekspos
- [ ] Memeriksa proteksi CSRF dan validasi form
- [ ] Membatasi permintaan AI untuk mengurangi penyalahgunaan
- [ ] Meninjau penanganan error agar tidak membocorkan informasi sensitif

### Performa dan aksesibilitas

- [ ] Mengoptimalkan query database
- [ ] Menghindari query berulang yang tidak diperlukan
- [ ] Mengoptimalkan gambar dan aset frontend
- [ ] Mengurangi JavaScript yang tidak diperlukan
- [ ] Memastikan halaman responsif
- [ ] Menguji navigasi menggunakan keyboard
- [ ] Memastikan form memiliki label yang jelas
- [ ] Menguji tampilan pada browser dan ukuran layar berbeda

**Kriteria selesai:** Alur inti lulus pengujian, akses data terlindungi, dan website dapat digunakan dengan nyaman pada perangkat yang berbeda.

## Fase 10 — Deployment dan Rilis

**Prioritas: P0 — Sebelum digunakan secara publik**

- [ ] Memilih layanan hosting yang mendukung kebutuhan Laravel
- [ ] Menentukan domain dan konfigurasi DNS
- [ ] Menyiapkan database production
- [ ] Menyiapkan environment variable production
- [ ] Menonaktifkan debug pada production
- [ ] Mengonfigurasi HTTPS
- [ ] Menjalankan migrasi database production dengan prosedur yang aman
- [ ] Mengoptimalkan konfigurasi Laravel untuk production
- [ ] Memastikan file storage dan upload memiliki konfigurasi yang benar
- [ ] Mengonfigurasi logging dan pemantauan error
- [ ] Menyiapkan backup database
- [ ] Menguji alur login, materi, AI, dan kuis pada server production
- [ ] Menyusun panduan instalasi dan deployment
- [ ] Menyiapkan prosedur pemulihan jika deployment gagal

**Kriteria selesai:** Nalar dapat diakses melalui domain publik, menggunakan konfigurasi production yang aman, dan fitur inti telah diverifikasi.

---

# 4. Fitur Tambahan — Setelah MVP

Fitur berikut tidak menjadi penghambat rilis pertama.

## Gamifikasi

- [ ] Sistem poin pengalaman (XP)
- [ ] Badge pencapaian
- [ ] Streak belajar
- [ ] Level pengguna
- [ ] Tantangan belajar
- [ ] Leaderboard
- [ ] Animasi pencapaian
- [ ] Riwayat penghargaan

## Pengembangan lanjutan

- [ ] Rekomendasi materi berdasarkan progres
- [ ] Kuis yang dibantu AI
- [ ] Pembahasan jawaban yang dipersonalisasi
- [ ] Bookmark materi
- [ ] Fitur catatan belajar
- [ ] Notifikasi aktivitas belajar
- [ ] Ekspor hasil belajar jika dibutuhkan

Fitur tambahan hanya dikerjakan setelah fitur inti stabil dan kebutuhan pengguna telah dievaluasi.

---

# 5. Prioritas Pengembangan

| Prioritas | Modul | Target |
|---|---|---|
| P0 | Fondasi, autentikasi, welcome page | Fondasi aplikasi siap |
| P0 | Dashboard dan materi pembelajaran | Pengguna dapat mulai belajar |
| P1 | Bantuan AI | Pengguna dapat meminta penjelasan |
| P1 | Kuis interaktif | Pengguna dapat menguji pemahaman |
| P1 | Progres belajar | Aktivitas pengguna tersimpan |
| P1 | Panel admin | Konten dapat dikelola dengan aman |
| P0 sebelum rilis | Testing, keamanan, deployment | MVP siap digunakan |
| P2 | Gamifikasi dan fitur tambahan | Pengembangan pasca-MVP |

**Catatan:** P0 sebelum rilis berarti wajib dipenuhi sebelum aplikasi dipublikasikan. Urutan P1 dapat disesuaikan berdasarkan ketergantungan teknis.

---

# 6. Target MVP

Versi pertama Nalar dianggap memenuhi MVP apabila:

- [ ] Pengguna dapat mengakses welcome page tanpa login.
- [ ] Pengguna dapat mendaftar, login, dan logout.
- [ ] Pengguna dapat mengakses dashboard setelah login.
- [ ] Pengguna dapat menemukan dan membaca materi.
- [ ] Pengguna dapat meminta penjelasan melalui bantuan AI.
- [ ] Pengguna dapat mengerjakan kuis pilihan ganda.
- [ ] Sistem menghitung dan menyimpan hasil kuis.
- [ ] Progres belajar dapat ditampilkan kembali.
- [ ] Hak akses dan data pengguna terlindungi.
- [ ] Fitur inti telah diuji dan aplikasi dapat di-deploy.

Gamifikasi tidak termasuk syarat MVP.

---

# 7. Aturan Pengembangan

1. Gunakan Laravel Blade sebagai pendekatan utama frontend agar arsitektur tetap sederhana.
2. Pertahankan Laravel Fortify untuk autentikasi yang sudah berjalan.
3. Gunakan Laravel Sail untuk konsistensi lingkungan development.
4. Gunakan migration dan model untuk mengelola struktur database.
5. Terapkan validasi pada backend, bukan hanya frontend.
6. Pisahkan tanggung jawab route, controller, model, service, dan view sesuai kebutuhan.
7. Hindari dependensi baru jika fitur dapat dibuat dengan teknologi yang sudah tersedia.
8. Jangan menampilkan fitur sebagai fitur aktif apabila belum benar-benar diimplementasikan.
9. Perbarui checklist README setelah setiap fitur selesai dan diverifikasi.
10. Jalankan pengujian sebelum menyatakan modul selesai.

---

# 8. Catatan Progres Terakhir

**Sudah selesai**
- Proyek Laravel Nalar telah dibuat.
- Lingkungan lokal dengan Laravel Sail dan MySQL telah disiapkan.
- Laravel Fortify telah dikonfigurasi.
- Login dan registrasi sudah berfungsi.
- Setelah login atau registrasi, pengguna diarahkan ke `/dashboard`.

**Pekerjaan berikutnya**
1. Menyelesaikan welcome page pada route `/`.
2. Memastikan dashboard terlindungi autentikasi.
3. Menyiapkan struktur dan desain dashboard.
4. Merancang database dan alur materi pembelajaran.
5. Mengimplementasikan fitur inti secara bertahap.

README ini menjadi acuan progres pengembangan Nalar dan dapat diperbarui setiap kali ada modul yang selesai, diuji, atau mengalami perubahan ruang lingkup.
