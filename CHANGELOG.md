# Catatan Perubahan Bug Fix (Changelog)

File ini digunakan untuk merekam jejak semua perbaikan bug dan refaktor kode pada proyek migrasi Django ke Laravel ini. File ini akan terus diperbarui seiring dengan perbaikan-perbaikan selanjutnya.

## Sesi 1: Perbaikan Dashboard Inflasi (`/inflasi`)
*Tanggal: 15 Juli 2026*

### 1. Visual & UI
- **Perbaikan Chart YoY**: Menambahkan *empty state handling* (penanganan data kosong) pada grafik YoY. Sekarang, apabila semua data YoY bernilai `null` (tidak ada data untuk rentang yang dipilih), halaman akan menampilkan pesan "Tidak ada data YoY untuk periode komoditas yang dipilih" secara rapi, bukan menampilkan grafik kosong yang membingungkan.
- **Filter Dropdown**: Mengubah logika *inline style* menjadi *class-based toggle* pada dropdown "Tahun" dan "Komoditas". Menambahkan aturan CSS spesifik `.show { display: block !important; }` agar sistem dropdown berjalan lebih mulus tanpa saling tumpang tindih.

### 2. JavaScript & Performa
- **Optimasi N+1 Queries**: Melakukan *refactor* pada fungsi `loadKomoditasExplanation()`. Logika pengambilan komoditas umum kini menggunakan satu buah *endpoint API* terdedikasi untuk memuat komoditas dan sub-komoditas sekaligus, sehingga mencegah banyaknya koneksi berulang dari *frontend* ke *backend*.
- **Pembersihan Event Listener**: Menghapus baris `e.stopPropagation()` pada elemen *placeholder* komoditas agar klik dari pengguna langsung menular secara natural ke kontainer *dropdown*.
- **Pengecekan Aman (Safe Checks)**:
  - Menambahkan *null check* pada inisialisasi tombol penutup *banner* (`#bannerClose`) di `public/assets/js/dashboard.js`.
  - Menambahkan validasi keberadaan plugin DataTable sebelum inisialisasi (`if ($.fn.DataTable)`) di `dashboard.js`.

### 3. Syntax Error Fatal (Blocking Bugs)
- Memperbaiki duplikasi blok `try-catch` acak (sebesar ~5 KB kode *corrupt*) pada *global script* untuk tombol Share di `resources/views/layouts/main.blade.php` yang selama ini menghasilkan `Uncaught SyntaxError: Unexpected token 'catch'`.
- Memperbaiki salah ketik *(typo)* pemanggilan fungsi pada `public/js/share-utils.js` (dari `this.shareToPlat form` menjadi `this.shareToPlatform`).
- Kesalahan-kesalahan di atas adalah alasan utama mengapa fitur-fitur seperti navigasi klik dropdown sering kali berhenti di tengah jalan (karena *browser* menghentikan eksekusi ketika ada *Syntax Error*).

## Sesi 2: Perbaikan Kependudukan (`/kependudukan`) dan Kemiskinan (`/kemiskinan`)
*Tanggal: 16 Juli 2026*

### 1. Backend & Performa Query (Kependudukan)
- **Mengatasi N+1 Query**: Mengganti pengambilan data *looping* (yang menghasilkan 40+ query) dengan fungsi agregrasi tunggal (satu query) di KependudukanController pada `getSummary`, `getTrend`, `getPyramid`, dan metode lainnya. Waktu pemrosesan data (backend response) menjadi jauh lebih tangkas.
- **Perbaikan Algoritma Sorting Kelompok Umur**: Memperbaiki fungsi `orderBy` pada rentang kelompok umur (misal "5-9", "10-14") dari yang sebelumnya secara alfabetis murni (menyebabkan grafik acak) menjadi numerik (`orderByRaw("CAST(SUBSTRING_INDEX(age_group, '-', 1) AS UNSIGNED)")`) sehingga grafik Piramida dan Distribusi tampil runtut secara kronologis.
- **Rasio Jenis Kelamin**: Memperbaiki formula string *output* backend dari `"102.50:100"` menjadi murni numerik `"102.50"` guna menyesuaikan *template blade* yang sudah memuat deskripsi teks statis "Laki-laki per 100 Perempuan", menuntaskan ambiguitas antarmuka pengguna.

### 2. Standarisasi Vite, Pemisahan JS/CSS, & Arsitektur
- **Refactor `kependudukan.blade.php`**: Mencopot +1.000 baris kode eksekusi *inline Javascript* dan kustom *inline CSS*, lalu membundelnya ke dalam berkas modular terdedikasi (`resources/js/dashboard/kependudukan.js` dan `resources/css/dashboard/kependudukan.css`). Terhubung secara aman lewat `@vite`.
- **Refactor `utilities.js`**: Menggabungkan berbagai duplikasi fungsi utilitas konversi format dari *controller* kependudukan (seperti `formatPopulation` untuk teks 'ribu/juta' dan `formatRupiah`) menjadi penugasan terpusat di `resources/js/utilities.js` sebagai global helper `window`.
- **Global Loading State ECharts**: Membuat rekayasa modifikasi (`monkey-patching`) pada fungsi internal ECharts di `utilities.js` agar seluruh *chart* yang diinisialisasi otomatis mendemonstrasikan status efek visual *loading canvas* dan mencopot efek tersebut tatkala data mendarat (`setOption`). Hal ini diimplementasikan serentak ke semua modul grafik kependudukan, inflasi, maupun kemiskinan saat halaman tab pertama kali diakses.

### 3. Pembersihan Bug & Sisa File Sampah
- **Highcharts 429 Too Many Requests**: Menghapus pemuatan statis `highcharts.js` usang yang tersisa di *layout template* utama (`main.blade.php`) dan terbukti menjadi penyebab membludaknya permintaan hampa dari sisi *browser* (429 Request Blocked). Aplikasi murni berbasis ECharts.
- Membersihkan jejak file *Node/PHP Script* sementara (temp scripts) yang digunakan selama automasi modifikasi di *working directory* `astabaya/`.

**Daftar File yang Diperbarui:**
- `app/Http/Controllers/API/KependudukanController.php`
- `resources/views/dashboard/indikator/kependudukan.blade.php`
- `resources/views/layouts/main.blade.php`
- `resources/js/utilities.js`
- `resources/js/dashboard/kependudukan.js` (Baru / Modifikasi)
- `resources/css/dashboard/kependudukan.css` (Baru)
- `resources/js/dashboard/kemiskinan.js`
- `vite.config.js`

### 4. File-File yang Dimodifikasi
- `resources/views/dashboard/indikator/inflasi.blade.php`
- `resources/js/dashboard/inflasi.js`
- `resources/views/layouts/main.blade.php`
- `public/assets/js/dashboard.js`
- `public/js/share-utils.js`

---
*Siap dilanjutkan untuk audit halaman kemiskinan dan indikator lainnya.*

### Sesi 3: Standarisasi Vite Global & Pemulihan Indikator (IPM, PDRB, Ketenagakerjaan, Gini, Hotel)
*Tanggal: 21 Juli 2026*

### 1. Migrasi Aset ke Vite (Pemisahan JS/CSS Global)
- **Refactoring Ekstensif**: Melakukan pemisahan *inline script* dan *style* dari lebih dari 10 file Blade indikator ke dalam struktur file modular di `resources/js/dashboard/` dan `resources/css/dashboard/`.
- **Konfigurasi Vite (`vite.config.js`)**: Mendaftarkan seluruh file aset indikator baru ke dalam *entry point* Vite untuk di-*bundle* menjadi satu kesatuan *(compiled assets)*, meningkatkan kecepatan *load* dan performa *caching* aplikasi.
- **Injeksi `window.APP_CONFIG`**: Memastikan setiap modul JS dapat menerima variabel lingkungan dari Laravel (seperti URL API dan status *auth*) dengan menginjeksi blok konfigurasi secara dinamis via `@push('scripts')`.

### 2. Perbaikan Spesifik per Indikator
- **IPM (Indeks Pembangunan Manusia)**: 
  - Memisahkan 7 sub-tab IPM (IPM HLS, UHH, Pengeluaran, dll) agar menggunakan satu sumber *style* (`indikator-ipm.css`) dan logika data (`indikator-ipm.js`).
  - Mengubah presisi angka di seluruh *Summary Card* IPM agar tidak dibulatkan dan menampilkan nilai desimal aslinya menggunakan tanda koma (misalnya `10.500,25`), lewat modifikasi utilitas `formatNumber`.
  - Mengubah label Y-Axis dari 'Juta Rupiah' menjadi 'Juta' pada Pengeluaran Per Kapita.
- **Ketenagakerjaan & PDRB Lapangan Usaha**: Memindahkan secara paksa file `.js` dan `.css` bawaan yang sempat 'nyasar' di dalam folder `public/` menuju direktori sumber `resources/` lalu menghubungkannya kembali dengan `@vite`.

### 3. Perbaikan Bug Lanjutan (PDRB & PDRB Lapangan Usaha)
- **Format Rupiah PDRB**: Memperbaiki format tampilan *summary card* pada PDRB Pengeluaran dan PDRB Lapangan Usaha untuk mempertahankan dua angka desimal (presisi `.toFixed(2)`) tanpa pembulatan ekstrem, lalu membungkusnya secara rapi di dalam `window.formatRupiah()`. 
- **Perbaikan Enkoding (Icon Arrow)**: Memperbaiki insiden *icon* anak panah (arrow) yang sempat *corrupt* akibat galat enkoding saat modifikasi file, dan mengembalikannya menggunakan *class* font-awesome murni (`<i class="fas fa-arrow-up"></i>`).
- **Syntax Error EOF**: Memperbaiki bug di mana dua modul javascript PDRB mengalami kegagalan eksekusi (hilangnya kurung tutup `});` di baris terbawah) karena terpotong, yang sebelumnya membuat keseluruhan grafik gagal *render*.

### 4. Pemulihan Versioning (Git Fixes)
- Memulihkan insiden hilangnya konfigurasi *Vite* dari tab-tab sebelumnya (Inflasi, Kemiskinan, PDRB Pengeluaran) yang disebabkan oleh *checkout* paksa versi lawas dari Git. Pemulihan dilakukan dengan skrip *python injection* untuk menyematkan ulang `@vite(...)` tanpa merusak logika tampilan saat ini.
- Membersihkan puluhan skrip sementara *(temporary AI scripts)* `.cjs`, `.py`, dan berkas pendukung *(screenshot)* dari repositori guna menjaga kerapian kode proyek.
- Menambahkan aturan di `.gitignore` untuk mencegah berkas sampah AI ikut ke-*commit* di masa depan.

**Daftar File yang Terpengaruh pada Sesi 3:**
- `resources/js/dashboard/pdrb-pengeluaran.js` (Pemulihan & Format)
- `resources/js/utilities.js` (Globalisasi window.formatRupiah)
- `resources/views/dashboard/indikator/ipm_*.blade.php` (7 sub-file IPM)
- `resources/views/dashboard/indikator/pdrb_pengeluaran.blade.php`
- `resources/views/dashboard/indikator/gini_ratio.blade.php`
- `resources/views/dashboard/indikator/hotel_occupancy.blade.php`
- `resources/views/dashboard/indikator/ketenagakerjaan.blade.php`
- `resources/views/dashboard/indikator/pdrb_lapangan_usaha.blade.php`
- `resources/views/dashboard/indikator/inflasi.blade.php`
- `resources/views/dashboard/indikator/kemiskinan.blade.php`
- `resources/js/dashboard/indikator-ipm.js` (Baru / Modifikasi)
- `resources/css/dashboard/indikator-ipm.css` (Baru / Modifikasi)
- `resources/js/dashboard/ketenagakerjaan.js` (Pindahan dari public)
- `resources/js/dashboard/pdrb-lapangan-usaha.js` (Pindahan dari public)
- `vite.config.js`
- `.gitignore`
