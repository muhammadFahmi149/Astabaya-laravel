# Solusi Optimasi Performa Navigasi (Mencegah Jeda Render Halaman)

Dokumen ini merangkum opsi-opsi yang tersedia untuk menyelesaikan masalah jeda (*delay*) visual saat perpindahan antar tab indikator di Dashboard Astabaya. Jeda ini terjadi karena arsitektur aplikasi saat ini berbasis *Multi-Page Application* (MPA), sehingga browser melakukan *full page reload* (penguraian HTML dan eksekusi ulang *script* ApexCharts) setiap kali link tab ditekan.

Berikut adalah dua solusi arsitektural yang dapat dipertimbangkan di masa depan setelah seluruh fitur dasar rampung:

## Solusi 1: Mengubah ke Arsitektur SPA Ringan (Turbo / Turbolinks / Swup)
Menggunakan *library* pendorong modern (seperti Hotwire Turbo) untuk mencegat klik link dan memuat halaman secara asinkron tanpa memuat ulang CSS/JS di `<head>`.

### Kelebihan:
- **Navigasi Super Cepat (Instan):** Karena CSS dan Javascript tidak dieksekusi ulang dari nol, layar tidak akan berkedip. Transisi terasa mulus seperti aplikasi ponsel atau *Single Page Application* (React/Vue).
- **Pengalaman Premium:** Mengurangi kelelahan mata pengguna karena tidak ada layar putih sebentar saat pindah halaman.

### Kekurangan / Konsekuensi:
- **Refaktor Javascript Masif:** Event listener bawaan browser seperti `DOMContentLoaded` tidak akan terpicu pada navigasi Turbo. Seluruh inisialisasi bagan (ApexCharts) di semua file indikator (`kemiskinan.js`, `inflasi.js`, dsb.) harus dibungkus ulang dengan event `turbo:load`.
- **Manajemen Memori:** Kita harus mengatur pembersihan instance grafik *(destroy chart)* lama sebelum halaman terganti untuk menghindari *memory leak* (kebocoran RAM di sisi klien).

---

## Solusi 2: Preloading Pintar (*Hover-to-Load*) dengan `instant.page`
Menggunakan *script* ringan `instant.page` (cukup disuntikkan ke dalam `main.blade.php`). Ketika kursor pengguna mengarah *(hover)* ke sebuah tautan tab, *script* akan mulai mengunduh halaman tujuan secara diam-diam (sekitar 60-100 milidetik sebelum klik sungguhan terjadi).

### Kelebihan:
- **Sangat Mudah Diterapkan:** Hanya menambah satu baris kode di tata letak utama.
- **Minim Risiko:** Tidak merusak alur hidup (*lifecycle*) event Javascript yang sudah ada (`DOMContentLoaded` tetap berjalan normal). Tidak perlu merombak ulang grafik ApexCharts.
- Menghilangkan latensi jaringan sepenuhnya sebelum pengguna benar-benar menekan tautan.

### Kekurangan:
- **Tidak 100% Instan secara Visual:** Browser tetap akan mengurai ulang HTML dari nol setelah tombol ditekan, sehingga sedikit kedipan layar (*flicker*) atau waktu proses perenderan bagan masih akan terasa dibandingkan dengan Solusi 1.
