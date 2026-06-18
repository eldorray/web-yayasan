# Website Yayasan Pendidikan Daarul Hikmah Al Madani — Design Spec

**Tanggal:** 2026-06-18
**Project:** `web-yayasan` (Laravel 13 + Livewire 4 + Tailwind 4)
**Berdasar:** Laravel Blank Livewire Starter Kit

---

## 1. Tujuan

Membangun **website publik portal yayasan** untuk Yayasan Pendidikan Daarul Hikmah Al Madani. Yayasan membawa beberapa sekolah yang masing-masing **sudah punya website sendiri** — website ini berfungsi sebagai pusat yang menghubungkan semuanya, plus menampilkan profil yayasan, berita, galeri, dan info PPDB.

**Bukan scope:** sistem multi-tenant per-sekolah, dashboard keuangan, PPDB online penuh.

### Pengguna & success criteria

- **Pengunjung publik:** dapat melihat profil yayasan, daftar & profil sekolah binaan, berita, galeri, dan info PPDB. Tombol PPDB/sekolah mengarah ke website sekolah terkait.
- **Admin yayasan:** dapat login dan mengelola seluruh konten (sekolah, berita, galeri, identitas yayasan) melalui panel admin.

---

## 2. Keputusan desain (ringkasan)

| Aspek | Keputusan |
|---|---|
| Tipe | Website publik + area admin (memakai auth Livewire starter kit) |
| Nama yayasan | Yayasan Pendidikan Daarul Hikmah Al Madani |
| Sekolah | Tiap sekolah punya website sendiri; yayasan = portal + profil sekolah |
| Fitur publik | Profil yayasan, daftar & profil sekolah, berita & galeri, info PPDB |
| PPDB | Info + link ke website sekolah (tanpa form di website yayasan) |
| Manajemen konten | Admin panel CRUD |
| Visual | Hijau Islami + aksen emas |
| Routing | Publik di root, admin di `/admin/*` |
| Urutan bangun | Pondasi → publik → admin |
| Rich text | Trix (ringan) |

---

## 3. Arsitektur & Routing

Dua "wajah" yang dipisahkan middleware:

```
PUBLIK (root URL)                       ADMIN (auth + admin middleware)
┌────────────────────────────────┐      ┌──────────────────────────────────┐
│ Layout: public.navbar +         │      │ Layout: admin.sidebar +           │
│ public.footer (baru)            │      │ admin.header (sudah ada)          │
│                                 │      │                                   │
│ /            beranda            │      │ /admin            dashboard       │
│ /tentang     profil yayasan     │      │ /admin/sekolah    CRUD sekolah    │
│ /sekolah     daftar sekolah     │      │ /admin/berita     CRUD berita     │
│ /sekolah/{slug} profil sekolah  │      │ /admin/galeri     CRUD galeri     │
│ /berita      daftar berita      │      │ /admin/pengaturan identitas yayasan│
│ /berita/{slug} artikel          │      │                                   │
│ /galeri      galeri foto        │      │                                   │
│ /ppdb        info PPDB          │      │                                   │
└────────────────────────────────┘      └──────────────────────────────────┘
```

### Komponen kunci

- **Layout publik baru** `resources/views/components/layouts/public.blade.php`: navbar (menu + CTA PPDB) dan footer (kontak, link sekolah, media sosial).
- **Komponen publik** `resources/views/components/public/`: `navbar.blade.php`, `footer.blade.php`, `hero.blade.php`, `school-card.blade.php`, `news-card.blade.php`, dll. (sesuai kebutuhan).
- **Middleware `admin`** baru di `app/Http/Middleware/IsAdmin.php`: cek `auth` + `is_admin`. Mendaftar alias `admin` di `bootstrap/app.php`.
- **Role admin**: kolom `is_admin` boolean di tabel users (default false). Seeder menandai user pertama sebagai admin.
- **Dashboard admin** yang ada (`Admin\Dashboard`) direvisi dari mock finansial → statistik yayasan (jumlah sekolah aktif, jumlah berita terbit, galeri terbaru, status PPDB per sekolah).

### Bahasa & locale

- `APP_LOCALE=id` (Bahasa Indonesia). Teks UI berbahasa Indonesia.
- `APP_FAKER_LOCALE=id_ID` untuk data seeder.

---

## 4. Data Model

5 model baru + modifikasi User. Semua konten dikelola via admin.

### 4.1 Modifikasi: `User` + `is_admin`

```php
// migration: add_is_admin_to_users_table
$table->boolean('is_admin')->default(false)->after('color_theme');
```

### 4.2 `School` (sekolah)

| Field | Tipe | Keterangan |
|---|---|---|
| `name` | string | Nama sekolah |
| `slug` | string, unique | URL-friendly, otomatis dari nama |
| `level` | enum/string | `TK`, `SD`, `SMP`, `SMA`, `SMK` |
| `description` | text | Rich text (HTML) |
| `address` | string | |
| `map_lat` | decimal(10,7), nullable | Koordinat peta |
| `map_lng` | decimal(10,7), nullable | |
| `phone` | string, nullable | |
| `email` | string, nullable | |
| `logo` | string, nullable | Path di disk `public` |
| `cover_image` | string, nullable | Path di disk `public` |
| `website_url` | string, nullable | URL eksternal ke website sekolah |
| `established_year` | year/integer, nullable | |
| `is_active` | boolean, default true | Tampil/sembunyi di publik |
| `sort_order` | integer, default 0 | Urutan tampil |

**Relasi:** hasMany `News`, `GalleryImage`, `PpdbInfo`.

### 4.3 `News` (berita)

| Field | Tipe | Keterangan |
|---|---|---|
| `title` | string | |
| `slug` | string, unique | |
| `excerpt` | string, nullable | Ringkasan (untuk kartu/daftar) |
| `body` | longText | Rich text (HTML) via Trix |
| `cover_image` | string, nullable | |
| `category` | enum | `yayasan`, `sekolah` |
| `school_id` | foreignId, nullable | Nullable: berita yayasan umum |
| `author_id` | foreignId → users | Penulis (admin) |
| `published_at` | timestamp, nullable | Null = draft |
| `is_published` | boolean | |

**Relasi:** belongsTo `School` (nullable), `User` (author).

### 4.4 `GalleryImage` (galeri)

| Field | Tipe | Keterangan |
|---|---|---|
| `title` | string, nullable | |
| `image` | string | Path di disk `public` |
| `caption` | string, nullable | |
| `school_id` | foreignId, nullable | Nullable: galeri yayasan |
| `sort_order` | integer, default 0 | |

**Relasi:** belongsTo `School` (nullable).

### 4.5 `PpdbInfo` (PPDB per sekolah)

| Field | Tipe | Keterangan |
|---|---|---|
| `school_id` | foreignId → schools | Satu info per tahun per sekolah |
| `academic_year` | string | Mis. `2026/2027` |
| `open_date` | date, nullable | |
| `close_date` | date, nullable | |
| `requirements` | text, nullable | Syarat (rich text/HTML) |
| `fees` | string, nullable | Biaya (teks bebas) |
| `registration_url` | string, nullable | Link ke website sekolah |
| `is_open` | boolean | Status dibuka |

**Relasi:** belongsTo `School`. **Unique** `(school_id, academic_year)`.

### 4.6 `SiteSetting` (identitas yayasan, singleton)

Satu baris data (id=1) menyimpan identitas yayasan. Diakses via helper atau service (mis. `Settings::get()`).

| Field | Tipe | Keterangan |
|---|---|---|
| `name` | string | Nama yayasan |
| `tagline` | string, nullable | |
| `vision` | text, nullable | Visi |
| `mission` | text, nullable | Misi |
| `history` | text, nullable | Sejarah (rich text/HTML) |
| `address` | string, nullable | |
| `phone` | string, nullable | |
| `email` | string, nullable | |
| `logo` | string, nullable | |
| `established_year` | year/integer, nullable | Tahun berdiri yayasan (untuk stat beranda) |
| `students_count` | integer, nullable | Jumlah siswa total (manual, untuk stat beranda) |
| `socials` | json, nullable | `{facebook, instagram, youtube, ...}` |

---

## 5. Halaman Publik

Palet: hijau Islami (`#0f4c3a`, `#0a7d5c`) + aksen emas (`#f4d35e`). Tipografi: serif untuk judul headline, sans-serif (Inter) untuk body. Tailwind 4 via custom theme tokens.

### 5.1 Beranda `/`

Layout **Hero split + grid padat**:
- Hero terbagi dua: kiri (teks + CTA "Lihat Sekolah"), kanan (logo/lambang yayasan dengan aksen emas)
- Grid konten: kartu sekolah (3 pertama) + kartu PPDB aktif + kartu berita terbaru
- Stats singkat (jumlah sekolah dari tabel `schools` di mana `is_active`, jumlah siswa dari `SiteSetting.students_count`, tahun berdiri dari `SiteSetting.established_year`)

### 5.2 Tentang `/tentang`

Profil yayasan: visi-misi, sejarah, struktur (bila ada), dari `SiteSetting`.

### 5.3 Daftar Sekolah `/sekolah`

- Header hijau
- Filter chips per jenjang (Semua/TK/SD/SMP/SMA/SMK) — Livewire, reaktif
- Kartu sekolah: logo, nama, badge jenjang, lokasi + tahun berdiri, deskripsi singkat → link ke `/sekolah/{slug}`
- Hanya `is_active = true`

### 5.4 Profil Sekolah `/sekolah/{slug}`

- Cover hijau + badge jenjang emas
- Header: nama, kontak, tombol "Kunjungi Website" (ke `website_url`)
- Tab: Tentang / PPDB / Galeri (semua untuk sekolah ini)
  - **Tentang:** deskripsi (rich text), peta lokasi (bila ada koordinat), info kontak
  - **PPDB:** info `PpdbInfo` aktif + tombol ke `registration_url`
  - **Galeri:** `GalleryImage` milik sekolah ini
- Breadcrumb kembali ke `/sekolah`

### 5.5 Berita `/berita`

- Header hijau
- Filter kategori (Semua/Yayasan/Sekolah)
- 1 artikel unggulan (terbaru) besar → grid artikel → paginasi "Muat lebih banyak"
- Hanya `is_published = true` & `published_at <= now`

### 5.6 Artikel `/berita/{slug}`

Cover, judul, meta (tanggal, kategori, penulis, sekolah terkait), body (rich text), breadcrumb kembali.

### 5.7 Galeri `/galeri`

- Grid foto masonry (campuran yayasan + semua sekolah)
- Filter opsional per sekolah
- Klik foto → lightbox + caption

### 5.8 PPDB `/ppdb`

- Hero PPDB (tahun ajaran dari PpdbInfo aktif terbaru, atau teks manual di SiteSetting)
- Daftar semua sekolah dengan status `PpdbInfo`: badge Dibuka/Tutup, periode, tombol "Daftar di website sekolah" (ke `registration_url`)
- Link ke profil sekolah terkait

---

## 6. Admin Panel

Memakai layout admin yang sudah ada (`components/layouts/app.blade.php` + `admin.sidebar` + `admin.header`). Menu baru ditambah ke array `$nav` di sidebar.

### 6.1 Menu sidebar (baru ditandai 🆕)

- 🏠 Dashboard `/admin` (direvisi: statistik yayasan)
- 🏫 Sekolah 🆕 `/admin/sekolah`
- 📰 Berita 🆕 `/admin/berita`
- 🖼️ Galeri 🆕 `/admin/galeri`
- ⚙️ Pengaturan Yayasan 🆕 `/admin/pengaturan`
- (profil/tema pribadi user tetap di `/settings`)

### 6.2 Dashboard admin `/admin`

Ganti mock finansial. Tampilkan: jumlah sekolah aktif, jumlah berita terbit, jumlah foto galeri, status PPDB per sekolah (ringkasan), berita terbaru (5).

### 6.3 Pola CRUD (seragam untuk Sekolah, Berita, Galeri)

Tiap entitas punya 2 Livewire component:
- **Index** `app/Livewire/Admin/{Entity}/Index.php`: tabel + tombol Tambah/Edit/Hapus (konfirmasi modal). Paginasi.
- **Form** `app/Livewire/Admin/{Entity}/Form.php`: create/edit dalam satu component (deteksi param `?school=...`). Validasi Livewire, `slug` otomatis dari nama. Upload gambar ke disk `public`.

### 6.4 Pengaturan Yayasan `/admin/pengaturan`

Form tunggal mengedit baris `SiteSetting` (id=1): nama, tagline, visi-misi, sejarah, kontak, logo, media sosial. Disimpan dengan Livewire.

### 6.5 Rich text — Trix

- Trix editor untuk `body` (News), `description` (School), `history`/`vision`/`mission` (SiteSetting).
- Integrasi: input hidden + Trix div via Livewire wire:model, asset di `resources/js`.
- Simpan output HTML; sanitize dasar saat penyimpanan (strip script tag).

### 6.6 Upload gambar

- Disimpan di `storage/app/public/` (disk `public`). Jalankan `php artisan storage:link` (bagian setup).
- Field path string di DB. Helper accessor `->url` di model (mirip pola `User::avatarUrl`).
- Untuk permulaan tanpa library optimasi; resize manual bila perlu.

---

## 7. Branding & Rebranding

Starter kit masih menampilkan identitas finansial (logo ".I", `APP_NAME=Laravel`, dashboard finansial, teks Inggris). Rebranding:

- **`config/app.php` / `.env`:** `APP_NAME="Yayasan Pendidikan Daarul Hikmah Al Madani"`, `APP_LOCALE=id`.
- **Sidebar logo:** ganti `.I` → lambang/monogram yayasan (mis. "Dh" dengan latar hijau + teks emas), `config('app.name')` singkat di label.
- **Tema warna:** definisikan token Tailwind baru (`green-primary`, `gold`, dll.) atau ganti nilai `brand-*` yang ada menjadi palet hijau-emas. Konsisten antara publik & admin.
- **Aset:** welcome.blade.php (starter kit default) dihapus/diganti; semua teks UI dialihkan ke Bahasa Indonesia.

---

## 8. Fase Implementasi (urutan bangun)

### Fase 1 — Pondasi & rebranding
1. Inisialisasi git, cleanup (`.DS_Store`, `starter-kit-new/`), `.gitignore` + `.superpowers/`.
2. Rebranding: `APP_NAME`, locale, bahasa UI, ganti logo sidebar.
3. Tema warna: token Tailwind hijau-emas (atau ganti `brand-*`).
4. Middleware `admin` + kolom `is_admin`. Seeder admin user.
5. Migrasi: tabel `schools`, `news`, `gallery_images`, `ppdb_infos`, `site_settings`.
6. Model + factory + seeder data contoh (2-3 sekolah, beberapa berita, galeri).

### Fase 2 — Website publik
7. Layout publik (`components/layouts/public.blade.php`) + komponen navbar/footer.
8. Beranda (hero split + grid padat).
9. Tentang (profil yayasan).
10. Daftar sekolah + filter jenjang (Livewire).
11. Profil sekolah (tab Tentang/PPDB/Galeri).
12. Berita (daftar + artikel + paginasi).
13. Galeri (grid masonry + lightbox).
14. PPDB (ringkasan semua sekolah).
15. Sitemap dasar / SEO meta (judul, deskripsi per halaman).

### Fase 3 — Admin panel
16. Dashboard admin baru (statistik yayasan).
17. CRUD Sekolah (+ bagian PPDB di form).
18. CRUD Berita (dengan Trix).
19. CRUD Galeri.
20. Pengaturan Yayasan (form SiteSetting).
21. Validasi & sanitasi konten.

---

## 9. Testing

Memakai Pest (sudah di starter kit). Prioritas:

- **Feature test:** rute publik menampilkan data (`/sekolah`, `/berita/{slug}`); rute admin terlindungi (guest redirect, non-admin 403); CRUD dasar (create/edit/delete) untuk tiap entitas.
- **Unit test:** slug generation, accessor URL gambar, scope `is_published`.
- Ganti `ExampleTest` & `smoke-test` starter dengan tes nyata.

---

## 10. Catatan & risiko

- **SEO:** rute publik server-rendered (Livewire full-page component) — baik untuk SEO. Tambah meta tag per halanan.
- **Peta lokasi:** `map_lat`/`map_lng` disimpan; rendering peta butuh provider (Leaflet/Google Maps) — minimal untuk permulaan, bisa ditangguhkan.
- **Multi-bahasa:** permulaan hanya Bahasa Indonesia. Struktur siap jika nanti butuh Inggris.
- **Storage:** pastikan `php artisan storage:link` dijalankan & didokumentasikan (README).
- **Backup data:** dokumentasikan export DB (SQLite file) untuk konten yang dikelola admin.
