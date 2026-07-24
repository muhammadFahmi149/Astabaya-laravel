<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo">
</p>

# 📊 ASTABAYA (Aplikasi Statistik Surabaya)

**ASTABAYA** adalah portal dasbor statistik pintar tingkat *Enterprise* yang dirancang khusus untuk memvisualisasikan data BPS (Badan Pusat Statistik) Kota Surabaya. Aplikasi ini memadukan arsitektur *Backend* berbasis Laravel dan *Frontend* modern berbasis *Single Page Application* (SPA) untuk memberikan pengalaman interaktif yang secepat kilat tanpa *refresh* halaman.

Aplikasi ini menyajikan visualisasi memukau (grafik interaktif ECharts & Chart.js) yang menarik datanya secara otomatis dari **API BPS Pusat** dan **Google Sheets API**.

---

## 🚀 Fitur Unggulan (Core Features)

### 1. Arsitektur SPA (Single Page Application) - Hotwire Turbo
Astabaya menggunakan **Hotwire Turbo Drive** untuk mengubah navigasi web tradisional menjadi SPA. Perpindahan antardasbor, grafik, dan halaman publikasi terjadi seketika (*flicker-free*) layaknya aplikasi seluler modern.

### 2. BFF (Backend For Frontend) API Aggregator
Alih-alih memaksa peramban (*browser*) pengguna untuk melakukan belasan *HTTP Requests* untuk memuat dasbor utama, Astabaya memiliki *Aggregator Controller* di server. Server akan merakit 16 sumber data yang berbeda di latar belakang dan mengirimkannya kembali ke pengguna hanya dalam **1 respons JSON tunggal**. Ini memangkas *Server Load Spike* dan mempercepat *Time-to-Interactive*.

### 3. API Versioning (v1)
Infrastruktur backend dilengkapi dengan *API Versioning* (saat ini aktif di jalur `/api/v1/`). Hal ini dirancang agar aplikasi atau *deploy* lawas yang mengakses `/api/...` tidak rusak *(Backward Compatible)* saat server di-*update*, memberikan standar 0% *Downtime*.

### 4. Enterprise-Grade Cron Scheduler (Anti Rate-Limit)
Sinkronisasi harian dengan API BPS dan Google Sheets tidak dilakukan secara serentak yang berisiko DDoS. Astabaya menggunakan penjadwalan cerdas *(Staggered Cron Job)*.
- **Blok Jam 02:00:** Publikasi, Berita, Infografis (API BPS)
- **Blok Jam 03:00:** Gini Ratio, IPM, Kemiskinan, Kependudukan (Google Sheets)
- **Blok Jam 03:20:** Inflasi, PDRB (Data Berat)
*(Terdapat jeda micro-delay 2-5 menit antarservis untuk mencegah pemblokiran Rate-Limit 429).*

### 5. Google OAuth 2.0 & Keamanan Anti-Spam
- Terintegrasi dengan SSO (Single Sign-On) Google untuk pendaftaran dan *login* instan.
- **Debounce & Race-Condition Protection:** Fitur aksi pengguna seperti *Bookmark* dilindungi oleh lapisan *debounce JS* untuk menahan *spam click* yang membebani database.

### 6. Memory Leak Protection (Garbage Collector)
Menanamkan *Garbage Collector* otomatis di `app.js` yang memanfaatkan pemicu `turbo:before-cache` untuk menghancurkan (dispose) semua sisa pemrosesan ECharts/Chart.js sebelum berpindah halaman. Hal ini memastikan RAM *browser* pengguna tidak bocor saat menjelajahi web berjam-jam.

---

## 🛠️ Stack Teknologi

- **Backend:** Laravel 10/11 (PHP 8.2+)
- **Frontend SPA Engine:** Hotwire Turbo (`@hotwired/turbo`)
- **UI & Styling:** Blade Templates, Bootstrap/Custom CSS, Vite Asset Bundler
- **Data Visualization:** Apache ECharts, Chart.js
- **Database:** MySQL / MariaDB
- **Third-Party APIs:** API Web BPS Resmi & Google Sheets (GCP Service Account)

---

## ⚙️ Panduan Instalasi (Development Setup)

1. **Kloning Repositori & Instalasi Dependensi**
   ```bash
   git clone https://github.com/muhammadFahmi149/Astabaya-laravel.git
   cd astabaya
   composer install
   npm install
   ```

2. **Konfigurasi Environment**
   Salin `.env.example` menjadi `.env` lalu sesuaikan konfigurasi *Database* dan Google API Anda:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   **Wajib Diisi (Google Services):**
   - `GOOGLE_CLIENT_ID` & `GOOGLE_CLIENT_SECRET` (Untuk *Login* SSO)
   - *File Service Account* di `storage/app/google/credentials.json` (Untuk Google Sheets API)

3. **Migrasi Database**
   ```bash
   php artisan migrate
   ```

4. **Jalankan Server Lokal**
   Buka 2 terminal terpisah dan jalankan:
   ```bash
   php artisan serve
   ```
   ```bash
   npm run dev
   ```

## 📅 Penjadwalan Tugas Server (Production Cron Job)

Jika di-*deploy* di *Production* (misal: Hostinger, cPanel, atau VPS), Anda hanya perlu menjalankan satu buah *Cron Job* setiap menit:
```bash
* * * * * cd /path-ke-folder-aplikasi && php artisan schedule:run >> /dev/null 2>&1
```
*Scheduler* Laravel akan otomatis menangani penundaan (*staggering*) dan sinkronisasi harian sesuai `app/Console/Kernel.php`.

---
*Dikembangkan secara eksklusif untuk BPS Kota Surabaya.*
