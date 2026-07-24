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

### Sesi 4: Implementasi Fitur Chart Modal Global (Kemiskinan, Hotel Occupancy, Gini Ratio)
*Tanggal: 23 Juli 2026*

### 1. Pengembangan Komponen Global Modal
- **Chart Modal Component**: Menciptakan komponen reusable `<x-chart-modal />` untuk membuka grafik ke dalam tampilan *full-screen* guna memberikan pengalaman analitik data yang lebih baik.
- **Deep Cloning ECharts**: Mencegah *bug* grafis yang berantakan saat modal diperbesar dengan menerapkan teknik duplikasi (cloning) opsi dan data *dataset* ECharts dari kanvas asli menuju kanvas baru di dalam modal.
- **Deteksi Fleksibel**: Menyempurnakan pemindai elemen grafik (`chart-modal.js`) agar secara dinamis mampu mendeteksi elemen grafik ber-ID khusus maupun ber-class `.chart-container`, serta mengambil judul dari berbagai tag header (`<h4>`, `<h5>`, `<h6>`).

### 2. Deep Linking & Share Feature
- **Share Button**: Membuat komponen tombol bagikan (`<x-chart-share-button />`) yang mampu menyalin tautan khusus menuju halaman dan langsung membuka spesifik grafik yang dibagikan.
- **URL Slug & History API**: Menyematkan parameter dinamis (contoh: `?chart=tren-garis-kemiskinan`) ketika modal dibuka atau tautan dibagikan. Memanfaatkan *History API* `pushState` agar tautan otomatis bersih/terhapus tanpa perlu me-reload halaman ketika modal ditutup.
- **Auto-Open**: Apabila pengunjung mengakses halaman melalui tautan berparameter `?chart=...`, maka sistem akan otomatis menyorot dan membuka *modal fullscreen* untuk grafik yang bersangkutan segera sesudah peramban selesai merender halaman.

### 3. Perlindungan Fitur Unduh
- Mengamankan tombol unduhan (*Download Excel/PNG*) di dalam modal untuk tetap mematuhi otentikasi. Jika *user* bukan anggota terdaftar (anonim), fitur unduh akan memicu sistem login standar.

**Daftar File yang Dibuat & Dimodifikasi pada Sesi 4:**
- `resources/views/components/chart-modal.blade.php` (Baru)
- `resources/views/components/chart-share-button.blade.php` (Baru)
- `resources/js/dashboard/chart-modal.js` (Baru)
- `resources/views/dashboard/indikator/kemiskinan.blade.php` (Modifikasi)
- `resources/views/dashboard/indikator/hotel_occupancy.blade.php` (Modifikasi)
- `resources/views/dashboard/indikator/gini_ratio.blade.php` (Modifikasi)
- `vite.config.js` (Mendaftarkan aset baru)

### Sesi 5: Penerapan Masif Chart Modal ke Seluruh Indikator
*Tanggal: 23 Juli 2026*

### 1. Injeksi Komponen & Aset Global
Melakukan injeksi skrip `chart-modal.js` pada konfigurasi `@vite` dan menempatkan kerangka komponen statis `<x-chart-modal />` pada 13 berkas halaman *Blade* yang tersisa:
- **Tab IPM**: Halaman Utama dan 7 Sub-Halaman IPM (HLS, UHH, Pengeluaran, dsb).
- **Tab Ekonomi & Demografi**: Halaman Inflasi, Ketenagakerjaan, dan Kependudukan.
- **Tab PDRB**: Halaman PDRB Pengeluaran dan PDRB Lapangan Usaha.

### 2. Pengujian & Penjaminan Kualitas (QA)
- Melakukan kompilasi aset massal *(mass-compilation)* menggunakan `npm run build` yang sukses mengeksekusi 77 modul.
- *Browser Automated Testing*: Melakukan skenario uji klik kartu (*card click event*), *URL binding* (*pushState* & *replaceState*), serta integrasi *Share button*, dimana seluruh grafik sukses dikloning (*cloned*) ke dimensi penuh tanpa gangguan galat kode ganda (*double-code collision*).

Dengan ini, **100% halaman visualisasi indikator** di *Dashboard* Astabaya telah terstandarisasi dengan fitur *Global Chart Modal*.

### Sesi 6: Audit, Debugging & Optimisasi Modul Publikasi (Clean Code)
*Tanggal: 23 Juli 2026*

### 1. Code Review & Bug Fixing
Melakukan investigasi komprehensif pada Modul Publikasi dan menyelesaikan sejumlah *bug* kritikal:
- **Ghost Backdrop Fixed**: Memperbaiki masalah layar hitam (*backdrop*) yang macet setelah menutup modal publikasi akibat instalasi ganda *Bootstrap Modal Instance*.
- **Unsafe Redirect Prevented**: Menambahkan lapisan keamanan dengan fungsi *sanitize URL* pada endpoint unduhan untuk memastikan *relative path* dikembalikan secara benar dan aman (*URL parsing*).
- **Empty Src Fetch Optimization**: Mencegah pemanggilan *Network 404* pada skrip *Lazy Loading (IntersectionObserver)* saat atribut sumber gambar kosong.
- **Data Mismatch Share Link Resolved**: Sinkronisasi *query id* dan *pub_id* pada PublicationController di metode show dan getDownloadUrl sehingga *link share* dapat langsung terbuka tanpa respons HTTP 404.
- **API Fetch Path Modernization**: Menghilangkan *absolute root path* (*hardcoded path*) di setiap *fetch API* Javascript dan menyisipkan fungsi interpolasi PHP (Base URL) agar kompatibel berjalan dalam sub-direktori (*reverse proxy safe*).
- **Deprecated Global Event Addressed**: Menambahkan pewarisan eksplisit pada objek event di dalam fungsi refreshData() guna mencegah *Crash* di peramban *strict-mode* seperti Firefox/Safari.

### 2. Ekstraksi Aset (Asset Decoupling)
Merapikan publications.blade.php dengan cara mencabut ribuan baris <style> dan <script> yang menumpuk di file tampilan (*inline*).
- **CSS Extraction**: Mengekspor *styling* kustom publikasi ke dalam berkas `resources/css/dashboard/publications.css`.
- **JS Extraction**: Mengekspor logika *Event Listener, Pagination*, dan *Modal Control* ke dalam berkas `resources/js/dashboard/publications.js`. Mentranslasikan direktif *Blade* @auth dan variabel *route* menggunakan penyisipan konfigurasi *window scope* (window.ASTABAYA).
- **Vite Integration**: Mengonfigurasi `vite.config.js` untuk secara otomatis mengemas dan mengoptimasi berkas *CSS* & *JS* publikasi tersebut, memberikan peningkatan kinerja (*page load speed*) pada modul ini.

**Daftar File yang Terpengaruh pada Sesi 6:**
- `app/Http/Controllers/API/PublicationController.php` (Bug Fixing & Optimisasi Query)
- `resources/views/dashboard/publications.blade.php` (Pemisahan Script/Style & Rute Unduh)
- `resources/css/dashboard/publications.css` (Baru)
- `resources/js/dashboard/publications.js` (Baru)
- `vite.config.js` (Modifikasi *input array*)

### Hotfix (Sesi 6 Lanjutan)
- **Global Function Export (Vite Isolation Fix)**: Mengekspor fungsi JavaScript (*showModal*, *handlePublicationBookmark*, *performSearch*, dll) ke objek window agar bisa dipanggil oleh atribut event HTML *inline* (onclick, onkeypress), mengatasi masalah *Uncaught ReferenceError* yang muncul akibat enkapsulasi modul ES oleh sistem kompilasi Vite.
- **Accessibility (ARIA) Fix**: Menghilangkan atribut `aria-hidden="true"` pada elemen <textarea> pembantu di fitur salin tautan *(clipboard fallback)* dan mencabut implementasi interseptor penutup Modal *(Universal Modal Close Handler)* yang menghalangi Bootstrap untuk mengembalikan nilai fokus elemen (*focus trap warning*). Mengatasi deretan *warning* W3C/ARIA di konsol browser.

### Sesi 7: Implementasi Proxy API untuk Rincian Publikasi BPS
*Tanggal: 23 Juli 2026*

**Fitur Baru (Lazy Loading Abstract):**
- Mengimplementasikan pola *Proxy API* pada BPSPublicationService untuk mengambil data *abstract* secara dinamis tanpa menyimpannya ke tabel *database* lokal.
- Mengubah PublicationController@show untuk mengambil detail publikasi dari *Cache* dan *Endpoint* API BPS.
- Menambahkan *Fallback System*: Jika server BPS lambat atau tumbang, sistem akan memprioritaskan data dari *Database* lokal sehingga UI tetap responsif.
- Menambahkan animasi *Loading Spinner* di modal publikasi (*Frontend*) selama menunggu respon API BPS.

**Perbaikan Bug (Bug Fix):**
- **Data Abstract Terpotong/Kosong:** Memperbaiki galat *regex* Compilation failed: UTF-8 error pada fungsi cleanAbstract di BPSPublicationService.php dengan mengganti pola lawas /[\x00-\x1F\x7F-\x9F]/u menjadi pola yang sesuai standar PCRE UTF-8 modern /\p{Cc}+/u.
- **Fix Unduh Publikasi:** Memperbaiki galat pada logika tombol "Unduh PDF" di dalam Modal yang sebelumnya salah membaca tag sintaks *Blade* dari berkas *Javascript*.
- **Optimasi Kecepatan Dasbor Indikator:** Menerapkan sistem *Frontend SessionStorage Caching* pada seluruh *tab* indikator (Kemiskinan, Ketenagakerjaan, Gini Ratio, Hotel Occupancy, IPM, Inflasi, Kependudukan, PDRB) untuk menghilangkan waktu tunggu pemuatan jaringan (*network delay*) pada kunjungan ulang, menghasilkan *rendering* grafik ECharts secara instan.

### Sesi 8: Audit Modul Dashboard & Refactoring API Redundancy
*Tanggal: 24 Juli 2026*

**Perbaikan Keamanan & Bug (Dashboard):**
- **XSS Mitigation**: Menerapkan fungsi utilitas escapeHTML() pada *Javascript* untuk mencegah celah *DOM-based Cross-Site Scripting (XSS)* saat merender judul dari respons API ke dalam *carousel*.
- **Infinite Loop Fix**: Memperbaiki *memory leak* dan *infinite loop* pada *fallback image loading* dengan mengubah penulisan *event handler* gambar rusak menjadi onerror="this.onerror=null; this.src='...'".

**Pemisahan Aset (Asset Decoupling):**
- **CSS Extraction**: Mengekstrak ratusan baris *inline styling* dari dashboard.blade.php ke dalam berkas `resources/css/dashboard/dashboard.css`.
- **JS Extraction**: Mengekstrak logika *Javascript* kompleks dari dashboard.blade.php ke dalam berkas `resources/js/dashboard/dashboard.js`. Mengubah skema {{ route() }} menjadi window.DASHBOARD_CONFIG.
- **Vite Integration**: Mengonfigurasi `vite.config.js` untuk secara otomatis mengemas dan mengoptimasi dashboard.css & dashboard.js. Memuat aset pada dashboard.blade.php menggunakan direktif @vite.

**Optimasi Kinerja Backend For Frontend (BFF):**
- **API Aggregation**: Mengatasi masalah *API Redundancy Bottleneck* yang memaksa browser menembakkan 16 koneksi HTTP secara bersamaan saat memuat Dashboard.
- Membuat DashboardApiController.php yang secara internal di level server mengeksekusi metode dari 16 layanan indikator yang berbeda, merakit datanya, dan mengembalikannya ke browser hanya melalui **1 respons JSON tunggal**.
- Mengurangi beban server (Server Load Spike), mempercepat waktu rendering *(Time-to-Interactive)*, dan merampingkan skrip dashboard.js.

**Daftar File yang Terpengaruh pada Sesi 8:**
- `app/Http/Controllers/API/DashboardApiController.php` (Baru - Aggregator BFF)
- `routes/api.php` (Menambahkan Endpoint /dashboard-summary)
- `resources/views/dashboard/dashboard.blade.php` (Pemisahan Script/Style)
- `resources/css/dashboard/dashboard.css` (Baru)
- `resources/js/dashboard/dashboard.js` (Baru & Implementasi Single Fetch BFF)
- `vite.config.js` (Modifikasi *input array*)

### Sesi 9: Ekstraksi Komponen Modal pada Dashboard
*Tanggal: 24 Juli 2026*

**Perubahan dan Perbaikan:**
- **Pemisahan Modal (Component Extraction):** Mengekstrak kerangka HTML Modal Berita, Publikasi, dan Infografis dari masing-masing halaman utamanya menjadi komponen Blade terpisah agar dapat dipanggil (@include) secara modular di halaman mana pun, termasuk Dashboard.
- **Implementasi Fetch API Dinamis di Dashboard:** Memperbarui dashboard.js agar interaksi klik pada *card* atau *carousel* tidak lagi mengalihkan pengguna ke halaman lain, melainkan menembakkan API *request* (menggunakan *fetch* ke /api/news/{id}, /api/publications/{id}, /api/infographics/{id}) lalu memunculkan modal langsung di atas Dashboard.
- **Penyelarasan UX:** Mengamankan konsistensi antarmuka antara modal yang terbuka di halaman utama vs di Dashboard. Seluruh fitur modal seperti tangkapan gambar, tanggal, pembagian tautan (*share*), unduhan dokumen, dan markah (*bookmark*) berfungsi penuh.

**Daftar File yang Dibuat & Dimodifikasi:**
- `resources/views/components/news-modal.blade.php` (Baru)
- `resources/views/components/publication-modal.blade.php` (Baru)
- `resources/views/components/infographic-modal.blade.php` (Baru)
- `resources/views/dashboard/dashboard.blade.php` (Menyematkan Modal & Menghapus *redirect*)
- `resources/views/dashboard/news.blade.php` (Menggunakan Komponen)
- `resources/views/dashboard/publications.blade.php` (Menggunakan Komponen)
- `resources/views/dashboard/infographics.blade.php` (Menggunakan Komponen)
- `resources/js/dashboard/dashboard.js` (Menambah fungsi *fetch* API untuk Modal)

### Sesi 10: Standarisasi Fitur Unduh & QA Ekstrem
*Tanggal: 25 Juli 2026*

**Perubahan dan Perbaikan:**
- **Standarisasi Penamaan Unduhan:** Merefaktor seluruh fitur *Download* di 16 halaman indikator agar format nama *file* (Excel/PNG) mencerminkan judul aslinya secara dinamis.
- **Konsistensi Autentikasi Lintas Arsitektur:** Memperbarui fungsi pengunduhan global (`checkAuthBeforeDownload`) agar tangguh mendeteksi sesi *login* secara dinamis (mampu membaca variabel baru `ASTABAYA` dan variabel lama `APP_CONFIG`), sehingga menghindarkan sistem dari benturan arsitektur.
- **Uji Kualitas Menyeluruh (QA):** Memastikan ketahanan aplikasi dari kelemahan tingkat produksi seperti pencegahan *Cross-Site Scripting (XSS)*, *API Null Data Handling* yang elegan, hingga mekanisme penangkis serangan cepat (*Race Condition Debounce*) di fitur *Bookmark*.

### Sesi 11: Transformasi Arsitektur SPA & Pencegahan Kebocoran Memori
*Tanggal: 25 Juli 2026*

**Perubahan dan Perbaikan:**
- **Injeksi Mesin Turbo (SPA):** Merombak *website* tradisional menjadi *Single Page Application* tanpa jeda *flicker/blink*. Transisi perpindahan halaman navigasi *Dashboard* kini sekilat dan seringan aplikasi perangkat seluler.
- **Refaktor *Event Listener* Serentak:** Melakukan penyuntingan massal secara *bulk* untuk menggantikan fungsi lawas `DOMContentLoaded` menjadi `turbo:load` di seluruh file *JavaScript* indikator agar grafik tetap tergambar mulus walau halaman didatangkan secara asinkron.
- **Sistem *Garbage Collector* Terpusat:** Menanamkan pendeteksi *event* pelindung di `app.js` yang akan memicu `turbo:before-cache`. Sistem ini bertugas menghancurkan paksa *(dispose)* sisa-sisa elemen *ECharts* dan *Chart.js* di layar sebelum berpindah tab. Mengeliminasi ancaman *Memory Leak* (Bocor RAM) hingga ke akarnya.

### Sesi 12: Penjadwalan Cron Job Tahan Banting (API Rate Limit Safe)
*Tanggal: 25 Juli 2026*

**Perubahan dan Perbaikan:**
- **Restrukturisasi *Laravel Scheduler*:** Menata ulang urutan eksekusi tugas sinkronisasi *backend* (Tugas BPS dan Google Sheets) pada `app/Console/Kernel.php`. 
- **Penerapan Jeda Strategis (*Staggering*):** Menguraikan tumpukan 10+ layanan sinkronisasi menjadi 3 kloter blok jam (02:00, 03:00, 03:20). Memberikan rentang waktu *(jitter)* jeda yang logis dan spesifik (sekitar 2 hingga 5 menit) antarservis untuk mencegah beban lonjakan CPU di server Hostinger.
- **Menghindari Pemblokiran API:** Mekanisme antrean cerdas ini (*ditambah fungsi `withoutOverlapping()`*) secara ampuh melindungi server Hostinger Anda dari risiko terblokir *Error 429 Too Many Requests* oleh pertahanan BPS maupun Google Sheets API.
