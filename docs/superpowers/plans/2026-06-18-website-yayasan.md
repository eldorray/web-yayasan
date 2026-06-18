# Website Yayasan Daarul Hikmah Al Madani — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun website publik portal yayasan (profil yayasan, daftar/profil sekolah, berita, galeri, info PPDB) + admin panel CRUD di atas starter kit Laravel Livewire.

**Architecture:** Dua "wajah" dipisah middleware: rute publik di root (server-rendered Livewire full-page component, SEO-friendly) dan rute admin di `/admin/*` terproteksi middleware `admin`. Konten disimpan di 5 tabel baru + `SiteSetting` singleton. Memakai sistem tema CSS variable yang sudah ada di starter kit (ganti default ke `emerald` + aksen emas).

**Tech Stack:** Laravel 13, Livewire 4, Tailwind 4 (CSS-variable theming), Alpine.js, Trix editor, SQLite, Pest.

**Spec:** `docs/superpowers/specs/2026-06-18-website-yayasan-design.md`

---

## Catatan global sebelum mulai

- **Bahasa:** semua teks UI & teks pengujian dalam Bahasa Indonesia.
- **Palet:** starter kit punya tema via `[data-theme]` (lihat `resources/css/app.css`). Default sekarang `orange`. Kita ganti default ke `emerald` (hijau) + tambah token emas `--color-gold-*`. **Jangan** hapus tema lain.
- **Konvensi starter kit:** komponen Livewire di `app/Livewire/`, view di `resources/views/livewire/`, komponen Blade di `resources/views/components/`. Ikuti pola yang ada.
- **Commit sering** — setelah tiap task yang berjalan hijau.
- **Jalankan di root project:** `/Users/elfahmie/Documents/Coding/web-yayasan`.

---

# FASE 1 — Pondasi & Rebranding

## Task 1.1: Inisialisasi git & cleanup

**Files:**
- Modify: `.gitignore`
- Delete: `starter-kit-new/`, semua `.DS_Store`

- [x] **Step 1: Inisialisasi repo git**

```bash
cd /Users/elfahmie/Documents/Coding/web-yayasan
git init
git branch -M main
```

- [x] **Step 2: Perbarui `.gitignore`**

Tambahkan baris berikut ke `.gitignore` (pertahankan isi yang sudah ada — vendor/, node_modules/, dll):

```
.DS_Store
.superpowers/
refdesign/
```

- [x] **Step 3: Hapus file sampah**

```bash
find . -name '.DS_Store' -not -path './node_modules/*' -delete
rm -rf starter-kit-new
```

- [x] **Step 4: Commit awal**

```bash
git add -A
git commit -m "chore: initialize git & cleanup starter kit artifacts"
```

---

## Task 1.2: Rebranding identitas & locale

**Files:**
- Modify: `.env`, `.env.example`, `config/app.php` (bila perlu)

- [x] **Step 1: Edit `.env`**

Ubah baris berikut di `.env`:

```
APP_NAME="Yayasan Pendidikan Daarul Hikmah Al Madani"
APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID
```

- [x] **Step 2: Edit `.env.example` dengan nilai yang sama** (kecuali `APP_KEY` tetap kosong)

- [x] **Step 3: Verifikasi**

```bash
php artisan config:clear
php artisan tinker --execute="echo config('app.name');"
```

Expected: `Yayasan Pendidikan Daarul Hikmah Al Madani`

- [x] **Step 4: Commit**

```bash
git add .env.example
git commit -m "chore: rebrand to Yayasan Daarul Hikmah Al Madani, set locale id"
```

(Catatan: `.env` tidak di-commit karena di-gitignore; update lokal saja.)

---

## Task 1.3: Tema warna hijau-emas

**Files:**
- Modify: `resources/css/app.css`
- Modify: `resources/views/components/layouts/app.blade.php` (set data-theme default)
- Modify: `app/Livewire/Settings/Theme.php` & `resources/views/livewire/settings/theme.blade.php` (default `emerald`)

- [x] **Step 1: Tambah token emas ke `resources/css/app.css`**

Di dalam blok `@theme { ... }`, setelah `--color-dark-cocoa: #422a18;`, tambah:

```css
    /* Gold accent scale (untuk yayasan Islamic) */
    --color-gold-50: #fffbeb;
    --color-gold-100: #fef3c7;
    --color-gold-200: #fde68a;
    --color-gold-300: #fcd34d;
    --color-gold-400: #fbbf24;
    --color-gold-500: #f4d35e;
    --color-gold-600: #d4a017;
    --color-gold-700: #a87b0e;
    --color-gold-800: #7c5a0a;
    --color-gold-900: #523d07;
```

- [x] **Step 2: Jadikan `emerald` tema default**

Di `resources/css/app.css`, ubah blok tema pertama dari:

```css
/* Orange (default) */
:root,
[data-theme='orange'] {
```

menjadi:

```css
/* Emerald (default) */
:root,
[data-theme='emerald'] {
```

(Alamat CSS variable `--brand-*` di dalam blok itu kini ambil nilai dari blok `[data-theme='emerald']` di bawah. Agar tidak duplikat, **pindahkan** seluruh isi blok `[data-theme='emerald']` yang sudah ada ke blok `:root` ini, lalu hapus blok `[data-theme='emerald']` yang lama. Tetap simpan blok `[data-theme='orange']` sebagai opsi non-default.)

Hasil akhir: `:root` berisi skala emerald; `[data-theme='orange']` menjadi override eksplisit.

- [x] **Step 3: Build & verifikasi visual**

```bash
npm run build
```

Buka dashboard admin di browser (setelah login) — warna aksen harus hijau, bukan oranye.

- [x] **Step 4: Set default `color_theme` user ke `emerald`**

Ubah migrasi `database/migrations/2026_05_13_000000_add_avatar_appearance_theme_to_users_table.php` baris:

```php
$table->string('color_theme', 32)->default('orange')->after('appearance');
```

menjadi:

```php
$table->string('color_theme', 32)->default('emerald')->after('appearance');
```

Lalu jalankan fresh migrate untuk dev (SQLite, aman):

```bash
php artisan migrate:fresh --seed
```

- [x] **Step 5: Commit**

```bash
git add resources/css/app.css database/migrations/2026_05_13_000000_add_avatar_appearance_theme_to_users_table.php
git commit -m "feat: green-gold theme as default (emerald + gold accent)"
```

---

## Task 1.4: Middleware `admin` + kolom `is_admin`

**Files:**
- Create: `app/Http/Middleware/IsAdmin.php`
- Modify: `bootstrap/app.php`
- Create: `database/migrations/2026_06_18_000000_add_is_admin_to_users_table.php`
- Modify: `app/Models/User.php`
- Modify: `database/seeders/DatabaseSeeder.php`
- Test: `tests/Feature/AdminAccessTest.php`

- [x] **Step 1: Tulis test gagal**

Buat `tests/Feature/AdminAccessTest.php`:

```php
<?php

use App\Models\User;
use App\Livewire\Admin\Dashboard;

use function Pest\Laravel\{actingAs, get};

it('redirects guest from admin routes to login', function () {
    get(route('admin.dashboard'))->assertRedirect(route('login'));
});

it('forbids non-admin user from admin dashboard', function () {
    $user = User::factory()->create(['is_admin' => false]);
    actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
});

it('allows admin user to access admin dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    actingAs($admin)->get(route('admin.dashboard'))->assertOk();
});
```

- [x] **Step 2: Jalankan test, verifikasi gagal**

```bash
php artisan test --filter=AdminAccessTest
```

Expected: FAIL (route `admin.dashboard` belum ada, kolom `is_admin` belum ada).

- [x] **Step 3: Buat migrasi `is_admin`**

Jalankan:

```bash
php artisan make:migration add_is_admin_to_users_table
```

Edit file yang dihasilkan (`database/migrations/*_add_is_admin_to_users_table.php`):

```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('is_admin')->default(false)->after('color_theme');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('is_admin');
    });
}
```

- [x] **Step 4: Tambah `is_admin` ke User model**

Di `app/Models/User.php`, perbarui attribute `#[Fillable(...)]` di baris 16:

```php
#[Fillable(['name', 'email', 'password', 'avatar', 'appearance', 'color_theme', 'is_admin'])]
```

Tambah method:

```php
public function isAdmin(): bool
{
    return (bool) $this->is_admin;
}
```

- [x] **Step 5: Buat middleware `IsAdmin`**

Jalankan:

```bash
php artisan make:middleware IsAdmin
```

Edit `app/Http/Middleware/IsAdmin.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            abort(403, 'Akses khusus administrator.');
        }

        return $next($request);
    }
}
```

- [x] **Step 6: Daftarkan alias middleware di `bootstrap/app.php`**

Ubah `withMiddleware` menjadi:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'admin' => \App\Http\Middleware\IsAdmin::class,
    ]);
})
```

- [x] **Step 7: Perbarui seeder — admin user jadi `is_admin`**

Ganti isi `database/seeders/DatabaseSeeder.php` method `run()`:

```php
public function run(): void
{
    User::updateOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'Administrator Yayasan',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => true,
            'color_theme' => 'emerald',
        ],
    );
}
```

- [x] **Step 8: Tambah route placeholder `admin.dashboard`**

Sementara arahkan ke `Admin\Dashboard` yang sudah ada. Di `routes/web.php`, tambah di dalam grup `auth`:

```php
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
});
```

(Dashboard akan direvisi di Task 1.8; untuk sekarang cukup agar route ada.)

- [x] **Step 9: Migrate & seed**

```bash
php artisan migrate:fresh --seed
```

- [x] **Step 10: Jalankan test, verifikasi lulus**

```bash
php artisan test --filter=AdminAccessTest
```

Expected: PASS (3 tes)

- [x] **Step 11: Commit**

```bash
git add -A
git commit -m "feat: admin middleware + is_admin role with tests"
```

---

## Task 1.5: Migrasi tabel konten (schools, news, gallery, ppdb, site_settings)

**Files:**
- Create: `database/migrations/2026_06_18_000001_create_schools_table.php`
- Create: `database/migrations/2026_06_18_000002_create_news_table.php`
- Create: `database/migrations/2026_06_18_000003_create_gallery_images_table.php`
- Create: `database/migrations/2026_06_18_000004_create_ppdb_infos_table.php`
- Create: `database/migrations/2026_06_18_000005_create_site_settings_table.php`

- [x] **Step 1: Buat 5 file migrasi sekaligus**

```bash
php artisan make:migration create_schools_table
php artisan make:migration create_news_table
php artisan make:migration create_gallery_images_table
php artisan make:migration create_ppdb_infos_table
php artisan make:migration create_site_settings_table
```

- [x] **Step 2: Isi migrasi `create_schools_table`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('level', 8); // TK, SD, SMP, SMA, SMK
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->decimal('map_lat', 10, 7)->nullable();
            $table->decimal('map_lng', 10, 7)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('website_url')->nullable();
            $table->year('established_year')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
```

- [x] **Step 3: Isi migrasi `create_news_table`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('category', 16)->default('yayasan'); // yayasan | sekolah
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->index(['is_published', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
```

- [x] **Step 4: Isi migrasi `create_gallery_images_table`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('image');
            $table->string('caption')->nullable();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_images');
    }
};
```

- [x] **Step 5: Isi migrasi `create_ppdb_infos_table`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ppdb_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('academic_year', 12); // mis. 2026/2027
            $table->date('open_date')->nullable();
            $table->date('close_date')->nullable();
            $table->text('requirements')->nullable();
            $table->string('fees')->nullable();
            $table->string('registration_url')->nullable();
            $table->boolean('is_open')->default(true);
            $table->timestamps();
            $table->unique(['school_id', 'academic_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_infos');
    }
};
```

- [x] **Step 6: Isi migrasi `create_site_settings_table`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Yayasan Pendidikan Daarul Hikmah Al Madani');
            $table->string('tagline')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->text('history')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->year('established_year')->nullable();
            $table->integer('students_count')->nullable();
            $table->json('socials')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
```

- [x] **Step 7: Jalankan migrasi**

```bash
php artisan migrate
```

Expected: 5 tabel baru dibuat tanpa error.

- [x] **Step 8: Commit**

```bash
git add database/migrations/
git commit -m "feat: content tables (schools, news, gallery, ppdb, site_settings)"
```

---

## Task 1.6: Model Eloquent + aksesors

**Files:**
- Create: `app/Models/School.php`
- Create: `app/Models/News.php`
- Create: `app/Models/GalleryImage.php`
- Create: `app/Models/PpdbInfo.php`
- Create: `app/Models/SiteSetting.php`
- Test: `tests/Feature/ModelsTest.php`

- [x] **Step 1: Tulis test gagal**

Buat `tests/Feature/ModelsTest.php`:

```php
<?php

use App\Models\News;
use App\Models\School;
use App\Models\SiteSetting;
use App\Models\User;

it('generates slug from school name', function () {
    $school = School::create(['name' => 'SD Islam Daarul Hikmah', 'level' => 'SD']);
    expect($school->slug)->toBe('sd-islam-daarul-hikmah');
});

it('computes school logo url', function () {
    $school = School::create(['name' => 'SMP Al Madani', 'level' => 'SMP', 'logo' => 'logos/smp.png']);
    expect($school->logo_url)->toContain('/storage/logos/smp.png');
});

it('returns null logo url when no logo', function () {
    $school = School::create(['name' => 'SMA 1', 'level' => 'SMA']);
    expect($school->logo_url)->toBeNull();
});

it('scopes news to published only', function () {
    $author = User::factory()->create();
    News::create(['title' => 'Draft', 'author_id' => $author->id, 'is_published' => false]);
    News::create(['title' => 'Live', 'author_id' => $author->id, 'is_published' => true, 'published_at' => now()]);
    expect(News::published()->count())->toBe(1);
});

it('returns singleton site setting', function () {
    $settings = SiteSetting::current();
    expect($settings)->toBeInstanceOf(SiteSetting::class)
        ->and($settings->name)->toBe('Yayasan Pendidikan Daarul Hikmah Al Madani');
});
```

- [x] **Step 2: Jalankan test, verifikasi gagal**

```bash
php artisan test --filter=ModelsTest
```

Expected: FAIL (model belum ada).

- [x] **Step 3: Buat `app/Models/School.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'level', 'description', 'address',
        'map_lat', 'map_lng', 'phone', 'email', 'logo',
        'cover_image', 'website_url', 'established_year',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'map_lat' => 'decimal:7',
        'map_lng' => 'decimal:7',
        'established_year' => 'integer',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (School $school) {
            $school->slug ??= Str::slug($school->name);
        });

        static::updating(function (School $school) {
            if ($school->isDirty('name') && ! $school->isDirty('slug')) {
                $school->slug = Str::slug($school->name);
            }
        });
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class);
    }

    public function ppdbInfos(): HasMany
    {
        return $this->hasMany(PpdbInfo::class);
    }

    public function activePpdb(): ?PpdbInfo
    {
        return $this->ppdbInfos()->where('is_open', true)->latest('academic_year')->first();
    }

    protected function logoUrl(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(function () {
            if (! $this->logo) {
                return null;
            }

            if (str_starts_with($this->logo, ['http://', 'https://'])) {
                return $this->logo;
            }

            return Storage::disk('public')->url($this->logo);
        });
    }

    protected function coverImageUrl(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(function () {
            if (! $this->cover_image) {
                return null;
            }

            return Storage::disk('public')->url($this->cover_image);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
```

- [x] **Step 4: Buat `app/Models/News.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'body', 'cover_image',
        'category', 'school_id', 'author_id', 'published_at', 'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (News $news) {
            $news->slug ??= Str::slug($news->title);
        });
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    protected function coverImageUrl(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(function () {
            if (! $this->cover_image) {
                return null;
            }

            return Storage::disk('public')->url($this->cover_image);
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function getReadingTimeAttribute(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->body ?? '')) / 200));
    }
}
```

- [x] **Step 5: Buat `app/Models/GalleryImage.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'image', 'caption', 'school_id', 'sort_order',
    ];

    protected $casts = ['sort_order' => 'integer'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    protected function imageUrl(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(function () {
            return Storage::disk('public')->url($this->image);
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->latest();
    }
}
```

- [x] **Step 6: Buat `app/Models/PpdbInfo.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PpdbInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id', 'academic_year', 'open_date', 'close_date',
        'requirements', 'fees', 'registration_url', 'is_open',
    ];

    protected $casts = [
        'open_date' => 'date',
        'close_date' => 'date',
        'is_open' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
```

- [x] **Step 7: Buat `app/Models/SiteSetting.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name', 'tagline', 'vision', 'mission', 'history',
        'address', 'phone', 'email', 'logo', 'established_year',
        'students_count', 'socials',
    ];

    protected $casts = [
        'socials' => 'array',
        'established_year' => 'integer',
        'students_count' => 'integer',
    ];

    public static function current(): static
    {
        return static::firstOrCreate(['id' => 1], [
            'name' => 'Yayasan Pendidikan Daarul Hikmah Al Madani',
        ]);
    }
}
```

- [x] **Step 8: Jalankan test, verifikasi lulus**

```bash
php artisan test --filter=ModelsTest
```

Expected: PASS (5 tes)

- [x] **Step 9: Commit**

```bash
git add app/Models/ tests/Feature/ModelsTest.php
git commit -m "feat: content models (School, News, GalleryImage, PpdbInfo, SiteSetting)"
```

---

## Task 1.7: Factory + seeder data contoh

**Files:**
- Create: `database/factories/SchoolFactory.php`
- Create: `database/factories/NewsFactory.php`
- Create: `database/factories/GalleryImageFactory.php`
- Create: `database/factories/PpdbInfoFactory.php`
- Modify: `database/seeders/DatabaseSeeder.php`
- Create: `database/seeders/ContentSeeder.php`

- [x] **Step 1: Buat factories**

Jalankan:

```bash
php artisan make:factory SchoolFactory
php artisan make:factory NewsFactory
php artisan make:factory GalleryImageFactory
php artisan make:factory PpdbInfoFactory
```

- [x] **Step 2: Isi `database/factories/SchoolFactory.php`**

```php
<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    public function definition(): array
    {
        $levels = ['TK', 'SD', 'SMP', 'SMA', 'SMK'];
        $name = $this->faker->company().' Islam';

        return [
            'name' => $name,
            'level' => $this->faker->randomElement($levels),
            'description' => $this->faker->paragraph(3),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'website_url' => $this->faker->optional()->url(),
            'established_year' => $this->faker->year(),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
```

(Catatan: `slug` diisi otomatis oleh boot hook di model.)

- [x] **Step 3: Isi `database/factories/NewsFactory.php`**

```php
<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'excerpt' => $this->faker->paragraph(2),
            'body' => '<p>'.$this->faker->paragraph(5).'</p>',
            'category' => $this->faker->randomElement(['yayasan', 'sekolah']),
            'school_id' => null,
            'author_id' => User::factory(),
            'published_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'is_published' => true,
        ];
    }
}
```

- [x] **Step 4: Isi `database/factories/GalleryImageFactory.php`**

```php
<?php

namespace Database\Factories;

use App\Models\GalleryImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class GalleryImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'image' => 'gallery/sample.jpg',
            'caption' => $this->faker->sentence(),
            'school_id' => null,
            'sort_order' => 0,
        ];
    }
}
```

- [x] **Step 5: Isi `database/factories/PpdbInfoFactory.php`**

```php
<?php

namespace Database\Factories;

use App\Models\PpdbInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class PpdbInfoFactory extends Factory
{
    public function definition(): array
    {
        $year = now()->year;

        return [
            'academic_year' => $year.'/'.($year + 1),
            'open_date' => now()->startOfYear(),
            'close_date' => now()->startOfYear()->addMonths(3),
            'requirements' => '<p>Fotokopi KK, Akta Kelahiran, Pas Foto</p>',
            'fees' => 'Rp 500.000',
            'registration_url' => $this->faker->url(),
            'is_open' => true,
        ];
    }
}
```

- [x] **Step 6: Buat `database/seeders/ContentSeeder.php`**

Jalankan:

```bash
php artisan make:seeder ContentSeeder
```

Isi:

```php
<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use App\Models\News;
use App\Models\PpdbInfo;
use App\Models\School;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        $sekolahData = [
            ['name' => 'TK Islam Daarul Hikmah', 'level' => 'TK', 'established_year' => 1998, 'address' => 'Jl. Pesantren No.1, Jakarta'],
            ['name' => 'SD Islam Daarul Hikmah', 'level' => 'SD', 'established_year' => 2000, 'address' => 'Jl. Pesantren No.2, Jakarta'],
            ['name' => 'SMP Islam Al Madani', 'level' => 'SMP', 'established_year' => 2005, 'address' => 'Jl. Madani No.5, Bogor'],
        ];

        foreach ($sekolahData as $data) {
            $school = School::create(array_merge($data, [
                'description' => 'Sekolah unggulan yang memadukan kurikulum nasional dengan pendidikan agama Islam.',
                'phone' => '021-555-0100',
                'email' => strtolower(str_replace(' ', '', $data['name'])).'@dhm.id',
                'website_url' => 'https://'.strtolower(str_replace(' ', '-', $data['name'])).'.sch.id',
                'is_active' => true,
                'sort_order' => array_search($data['level'], ['TK', 'SD', 'SMP', 'SMA', 'SMK']),
            ]));

            PpdbInfo::create([
                'school_id' => $school->id,
                'academic_year' => '2026/2027',
                'open_date' => '2026-01-01',
                'close_date' => '2026-03-31',
                'requirements' => '<p>Fotokopi KK, Akta Kelahiran, Pas Foto 3x4, Ijazah/SKL</p>',
                'fees' => 'Rp 500.000',
                'registration_url' => $school->website_url.'/ppdb',
                'is_open' => $school->level !== 'SMP',
            ]);
        }

        News::factory()->count(6)->create([
            'author_id' => $admin->id,
        ]);

        News::factory()->count(3)->sequence(
            ['category' => 'yayasan', 'school_id' => null],
            ['category' => 'yayasan', 'school_id' => null],
            ['category' => 'yayasan', 'school_id' => null],
        )->create(['author_id' => $admin->id, 'title' => 'Wisuda Hafidz Angkatan 2026']);

        GalleryImage::factory()->count(8)->create();
        GalleryImage::factory()->count(4)->sequence(fn () => ['school_id' => School::inRandomOrder()->first()->id])->create();

        SiteSetting::updateOrCreate(['id' => 1], [
            'name' => 'Yayasan Pendidikan Daarul Hikmah Al Madani',
            'tagline' => 'Membina generasi Qur\'ani & berakhlak mulia.',
            'vision' => 'Menjadi pusat pendidikan Islam unggulan yang melahirkan generasi beriman, berilmu, dan berakhlak mulia.',
            'mission' => 'Menyelenggarakan pendidikan berbasis Al-Qur\'an dan Sunnah dengan kurikulum nasional berkualitas.',
            'address' => 'Jl. Pesantren No.1, Jakarta',
            'phone' => '021-555-0100',
            'email' => 'info@dhm.id',
            'established_year' => 1995,
            'students_count' => 2000,
            'socials' => [
                'facebook' => 'https://facebook.com/daarulhikmah',
                'instagram' => 'https://instagram.com/daarulhikmah',
                'youtube' => 'https://youtube.com/@daarulhikmah',
            ],
        ]);
    }
}
```

- [x] **Step 7: Panggil ContentSeeder dari DatabaseSeeder**

Update method `run()` di `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    User::updateOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'Administrator Yayasan',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => true,
            'color_theme' => 'emerald',
        ],
    );

    $this->call([
        ContentSeeder::class,
    ]);
}
```

- [x] **Step 8: Migrate fresh + seed, verifikasi**

```bash
php artisan migrate:fresh --seed
```

Lalu cek via tinker:

```bash
php artisan tinker --execute="echo \App\Models\School::count().' sekolah, '.\App\Models\News::count().' berita, '.\App\Models\GalleryImage::count().' foto galeri';"
```

Expected: `3 sekolah, 9 berita, 12 foto galeri`

- [x] **Step 9: Commit**

```bash
git add database/factories/ database/seeders/
git commit -m "feat: factories + content seeder (3 sekolah, berita, galeri contoh)"
```

---

## Task 1.8: Revisi logo sidebar & nama app

**Files:**
- Modify: `resources/views/components/admin/sidebar.blade.php`

- [x] **Step 1: Ganti logo & nama di sidebar**

Di `resources/views/components/admin/sidebar.blade.php`, ubah blok logo (baris ~16-20):

```blade
<a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
    <span
        class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-lg leading-none shrink-0"
        style="background-color: var(--brand-800); color: var(--color-gold-500);">
        Dh
    </span>
    <span class="font-bold text-ink-900 tracking-tight lg:hidden">Daarul Hikmah</span>
</a>
```

- [x] **Step 2: Verifikasi visual**

```bash
npm run build
```

Buka `/admin` setelah login — logo harus "Dh" dengan latar hijau gelap & teks emas.

- [x] **Step 3: Commit**

```bash
git add resources/views/components/admin/sidebar.blade.php
git commit -m "feat: yayasan logo monogram (Dh) in sidebar"
```

---

# FASE 2 — Website Publik

## Task 2.1: Layout publik + komponen navbar/footer

**Files:**
- Create: `resources/views/components/layouts/public.blade.php`
- Create: `resources/views/components/public/navbar.blade.php`
- Create: `resources/views/components/public/footer.blade.php`
- Create: `app/Livewire/Public/Home.php` (placeholder)

- [x] **Step 1: Buat komponen navbar**

`resources/views/components/public/navbar.blade.php`:

```blade
@php
    $navItems = [
        ['route' => 'public.home', 'label' => 'Beranda'],
        ['route' => 'public.about', 'label' => 'Tentang'],
        ['route' => 'public.schools.index', 'label' => 'Sekolah'],
        ['route' => 'public.news.index', 'label' => 'Berita'],
        ['route' => 'public.gallery.index', 'label' => 'Galeri'],
    ];
    $settings = \App\Models\SiteSetting::current();
@endphp

<header class="sticky top-0 z-40 backdrop-blur-md bg-white/80 border-b border-ink-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('public.home') }}" class="flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-lg flex items-center justify-center font-bold text-base leading-none"
                      style="background-color: var(--brand-800); color: var(--color-gold-500);">Dh</span>
                <span class="font-bold text-ink-900 tracking-tight hidden sm:block">{{ \Illuminate\Support\Str::limit($settings->name, 28) }}</span>
            </a>

            <nav class="hidden md:flex items-center gap-1">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="px-3 py-2 rounded-full text-sm font-medium transition-colors
                              {{ request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*')
                                  ? 'text-brand-800 bg-brand-50'
                                  : 'text-ink-600 hover:text-ink-900 hover:bg-ink-50' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('public.ppdb') }}"
                   class="inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-semibold text-white transition-colors"
                   style="background-color: var(--brand-700);">
                    PPDB
                </a>
            </div>
        </div>
    </div>
</header>
```

- [x] **Step 2: Buat komponen footer**

`resources/views/components/public/footer.blade.php`:

```blade
@php $settings = \App\Models\SiteSetting::current(); @endphp

<footer class="mt-20 text-white" style="background-color: var(--brand-900);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2.5 mb-3">
                    <span class="w-9 h-9 rounded-lg flex items-center justify-center font-bold"
                          style="background-color: var(--color-gold-500); color: var(--brand-900);">Dh</span>
                    <span class="font-bold">{{ $settings->name }}</span>
                </div>
                <p class="text-sm text-white/80">{{ $settings->tagline }}</p>
            </div>

            <div>
                <h4 class="font-semibold mb-3 text-sm" style="color: var(--color-gold-500);">Hubungi Kami</h4>
                <ul class="space-y-2 text-sm text-white/80">
                    @if ($settings->address)<li>{{ $settings->address }}</li>@endif
                    @if ($settings->phone)<li>{{ $settings->phone }}</li>@endif
                    @if ($settings->email)<li>{{ $settings->email }}</li>@endif
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-3 text-sm" style="color: var(--color-gold-500);">Tautan</h4>
                <ul class="space-y-2 text-sm text-white/80">
                    <li><a href="{{ route('public.schools.index') }}" class="hover:text-white">Sekolah Binaan</a></li>
                    <li><a href="{{ route('public.news.index') }}" class="hover:text-white">Berita</a></li>
                    <li><a href="{{ route('public.ppdb') }}" class="hover:text-white">PPDB</a></li>
                </ul>
                @if (! empty($settings->socials))
                    <div class="flex gap-3 mt-4">
                        @foreach ($settings->socials as $platform => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener" class="text-white/80 hover:text-white capitalize text-sm">{{ $platform }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="border-t border-white/10 mt-8 pt-6 text-center text-xs text-white/60">
            &copy; {{ date('Y') }} {{ $settings->name }}. Hak cipta dilindungi.
        </div>
    </div>
</footer>
```

- [x] **Step 3: Buat layout publik**

`resources/views/components/layouts/public.blade.php`:

```blade
<!DOCTYPE html>
<html lang="id" data-theme="emerald">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col bg-surface-soft">
    <x-public.navbar />

    <main class="flex-1">
        {{ $slot }}
    </main>

    <x-public.footer />
    @livewireScripts
</body>
</html>
```

- [x] **Step 4: Buat placeholder Home component + route untuk verifikasi layout**

`app/Livewire/Public/Home.php`:

```php
<?php

namespace App\Livewire\Public;

use App\Models\News;
use App\Models\School;
use App\Models\SiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.public')]
#[Title('Beranda')]
class Home extends Component
{
    public function render()
    {
        return view('livewire.public.home', [
            'settings' => SiteSetting::current(),
            'schools' => School::active()->ordered()->limit(3)->get(),
            'news' => News::published()->latest()->limit(3)->get(),
        ]);
    }
}
```

`resources/views/livewire/public/home.blade.php` (placeholder, akan diisi di Task 2.2):

```blade
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-ink-900">{{ $settings->name }}</h1>
    <p class="text-ink-600 mt-2">{{ $settings->tagline }}</p>
</div>
```

- [x] **Step 5: Tambah rute publik placeholder**

Di `routes/web.php`, **ganti** route `/` yang lama:

```php
use App\Livewire\Public\Home;

// Rute publik
Route::get('/', Home::class)->name('public.home');
```

Hapus redirect lama (`Route::get('/', function () { return redirect()... })`).

Tambah rute placeholder untuk navbar/footer (akan diisi tugas berikutnya):

```php
Route::name('public.')->group(function () {
    Route::get('/', Home::class)->name('home');
    // placeholder sementara — akan diganti tugas berikutnya
    Route::get('/tentang', Home::class)->name('about');
    Route::get('/sekolah', Home::class)->name('schools.index');
    Route::get('/berita', Home::class)->name('news.index');
    Route::get('/galeri', Home::class)->name('gallery.index');
    Route::get('/ppdb', Home::class)->name('ppdb');
});
```

(Catatan: hapus baris `Route::get('/', Home::class)->name('public.home');` yang barusan ditambah, karena sudah ada di dalam grup.)

- [x] **Step 6: Build & verifikasi**

```bash
npm run build
php artisan route:clear
```

Buka `/` di browser — harus tampil navbar (logo Dh + menu) + judul yayasan + footer, dengan warna hijau-emas.

- [x] **Step 7: Commit**

```bash
git add -A
git commit -m "feat: public layout + navbar/footer with green-gold theme"
```

---

## Task 2.2: Beranda (hero split + grid padat)

**Files:**
- Modify: `resources/views/livewire/public/home.blade.php`
- Create: `resources/views/components/public/school-card.blade.php`
- Create: `resources/views/components/public/news-card.blade.php`

- [x] **Step 1: Buat komponen kartu sekolah**

`resources/views/components/public/school-card.blade.php`:

```blade
@props(['school'])

<a href="{{ route('public.schools.show', $school->slug) }}"
   class="group block bg-white rounded-2xl border border-ink-100 overflow-hidden shadow-soft hover:shadow-md transition-shadow">
    <div class="h-32 flex items-center justify-center" style="background: linear-gradient(135deg, var(--brand-800), var(--brand-600));">
        @if ($school->logo_url)
            <img src="{{ $school->logo_url }}" alt="{{ $school->name }}" class="w-16 h-16 object-contain">
        @else
            <span class="text-3xl font-bold" style="color: var(--color-gold-500);">{{ \Illuminate\Support\Str::limit($school->level, 2, '') }}</span>
        @endif
    </div>
    <div class="p-4">
        <div class="flex items-center justify-between mb-1">
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background-color: var(--color-gold-100); color: var(--color-gold-800);">{{ $school->level }}</span>
            @if ($school->established_year)
                <span class="text-xs text-ink-500">Sejak {{ $school->established_year }}</span>
            @endif
        </div>
        <h3 class="font-semibold text-ink-900 group-hover:text-brand-700 transition-colors">{{ $school->name }}</h3>
        @if ($school->address)<p class="text-xs text-ink-500 mt-1 line-clamp-1">{{ $school->address }}</p>@endif
    </div>
</a>
```

- [x] **Step 2: Buat komponen kartu berita**

`resources/views/components/public/news-card.blade.php`:

```blade
@props(['news'])

<a href="{{ route('public.news.show', $news->slug) }}"
   class="group block bg-white rounded-2xl border border-ink-100 overflow-hidden shadow-soft hover:shadow-md transition-shadow">
    <div class="h-36 flex items-center justify-center" style="background: linear-gradient(135deg, var(--brand-600), var(--brand-800));">
        @if ($news->cover_image_url)
            <img src="{{ $news->cover_image_url }}" alt="{{ $news->title }}" class="w-full h-full object-cover">
        @else
            <span class="text-3xl font-bold text-white/40">📰</span>
        @endif
    </div>
    <div class="p-4">
        <span class="text-xs font-semibold px-2 py-0.5 rounded-full uppercase" style="background-color: var(--color-gold-100); color: var(--color-gold-800);">{{ $news->category }}</span>
        <h3 class="font-semibold text-ink-900 mt-2 group-hover:text-brand-700 line-clamp-2">{{ $news->title }}</h3>
        <p class="text-xs text-ink-500 mt-2">{{ $news->published_at?->format('d M Y') }} · {{ $news->reading_time }} min baca</p>
    </div>
</a>
```

- [x] **Step 3: Isi beranda**

Ganti `resources/views/livewire/public/home.blade.php`:

```blade
<div>
    {{-- Hero split --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center rounded-3xl overflow-hidden shadow-soft"
             style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
            <div class="p-8 md:p-12 text-white">
                <p class="text-sm font-semibold tracking-wider uppercase mb-3" style="color: var(--color-gold-500);">Yayasan Pendidikan</p>
                <h1 class="text-3xl md:text-4xl font-bold leading-tight">{{ $settings->name }}</h1>
                <p class="mt-4 text-white/85">{{ $settings->tagline }}</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('public.schools.index') }}" class="inline-flex items-center gap-1.5 rounded-full px-5 py-2.5 text-sm font-semibold transition-colors" style="background-color: var(--color-gold-500); color: var(--brand-900);">
                        Lihat Sekolah →
                    </a>
                    <a href="{{ route('public.about') }}" class="inline-flex items-center rounded-full px-5 py-2.5 text-sm font-semibold border border-white/30 text-white hover:bg-white/10">
                        Tentang Kami
                    </a>
                </div>
            </div>
            <div class="h-full min-h-[240px] flex items-center justify-center p-8" style="background: linear-gradient(160deg, var(--brand-600), var(--brand-800));">
                <div class="w-32 h-32 rounded-full border-4 flex items-center justify-center text-5xl font-bold" style="border-color: var(--color-gold-500); color: var(--color-gold-500);">Dh</div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    @if ($settings->established_year || $settings->students_count)
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-6 text-center shadow-soft">
                <p class="text-3xl font-bold" style="color: var(--brand-700);">{{ $schoolsCount ?? \App\Models\School::active()->count() }}</p>
                <p class="text-xs text-ink-500 mt-1">Sekolah Binaan</p>
            </div>
            @if ($settings->students_count)
            <div class="bg-white rounded-2xl p-6 text-center shadow-soft">
                <p class="text-3xl font-bold" style="color: var(--brand-700);">{{ number_format($settings->students_count, 0, ',', '.') }}+</p>
                <p class="text-xs text-ink-500 mt-1">Siswa</p>
            </div>
            @endif
            @if ($settings->established_year)
            <div class="bg-white rounded-2xl p-6 text-center shadow-soft">
                <p class="text-3xl font-bold" style="color: var(--brand-700);">{{ $settings->established_year }}</p>
                <p class="text-xs text-ink-500 mt-1">Berdiri Sejak</p>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Sekolah --}}
    @if ($schools->isNotEmpty())
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-ink-900">Sekolah Binaan</h2>
                <p class="text-ink-500 text-sm">Pendidikan Islam berkualitas di setiap jenjang.</p>
            </div>
            <a href="{{ route('public.schools.index') }}" class="text-sm font-semibold hover:underline" style="color: var(--brand-700);">Lihat semua →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($schools as $school)
                <x-public.school-card :school="$school" />
            @endforeach
        </div>
    </section>
    @endif

    {{-- PPDB + Berita grid --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <a href="{{ route('public.ppdb') }}" class="lg:col-span-1 rounded-2xl p-8 text-white shadow-soft hover:shadow-md transition-shadow flex flex-col justify-between min-h-[200px]" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
            <div>
                <p class="text-xs font-semibold tracking-wider uppercase mb-2" style="color: var(--color-gold-500);">Tahun Ajaran 2026/2027</p>
                <h3 class="text-xl font-bold">PPDB Dibuka</h3>
            </div>
            <span class="text-sm font-semibold mt-4" style="color: var(--color-gold-500);">Info pendaftaran →</span>
        </a>

        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-ink-900">Berita Terbaru</h2>
                <a href="{{ route('public.news.index') }}" class="text-sm font-semibold hover:underline" style="color: var(--brand-700);">Lihat semua →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach ($news as $item)
                    <x-public.news-card :news="$item" />
                @endforeach
            </div>
        </div>
    </section>
</div>
```

- [x] **Step 4: Build & verifikasi**

```bash
npm run build
```

Buka `/` — harus tampil: hero split (hijau dengan lingkaran Dh emas), stats, 3 kartu sekolah, kartu PPDB + 3 kartu berita.

- [x] **Step 5: Commit**

```bash
git add -A
git commit -m "feat: public homepage with hero split, stats, school/news grid"
```

---

## Task 2.3: Halaman Tentang

**Files:**
- Create: `app/Livewire/Public/About.php`
- Create: `resources/views/livewire/public/about.blade.php`
- Modify: `routes/web.php`

- [x] **Step 1: Buat component About**

`app/Livewire/Public/About.php`:

```php
<?php

namespace App\Livewire\Public;

use App\Models\SiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.public')]
#[Title('Tentang Kami')]
class About extends Component
{
    public function render()
    {
        return view('livewire.public.about', [
            'settings' => SiteSetting::current(),
        ]);
    }
}
```

- [x] **Step 2: Buat view About**

`resources/views/livewire/public/about.blade.php`:

```blade
<div>
    {{-- Page header --}}
    <section class="text-white py-16" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold tracking-wider uppercase mb-3" style="color: var(--color-gold-500);">Tentang Kami</p>
            <h1 class="text-3xl md:text-4xl font-bold">{{ $settings->name }}</h1>
            <p class="mt-4 text-white/85 max-w-2xl mx-auto">{{ $settings->tagline }}</p>
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
        {{-- Visi & Misi --}}
        @if ($settings->vision || $settings->mission)
        <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @if ($settings->vision)
            <div class="bg-white rounded-2xl p-8 shadow-soft border-t-4" style="border-color: var(--brand-600);">
                <h2 class="text-xl font-bold text-ink-900 mb-3">Visi</h2>
                <p class="text-ink-700 leading-relaxed">{{ $settings->vision }}</p>
            </div>
            @endif
            @if ($settings->mission)
            <div class="bg-white rounded-2xl p-8 shadow-soft border-t-4" style="border-color: var(--color-gold-500);">
                <h2 class="text-xl font-bold text-ink-900 mb-3">Misi</h2>
                <p class="text-ink-700 leading-relaxed">{{ $settings->mission }}</p>
            </div>
            @endif
        </section>
        @endif

        {{-- Sejarah --}}
        @if ($settings->history)
        <section>
            <h2 class="text-2xl font-bold text-ink-900 mb-4">Sejarah</h2>
            <div class="prose prose-ink max-w-none bg-white rounded-2xl p-8 shadow-soft">
                {!! $settings->history !!}
            </div>
        </section>
        @endif
    </div>
</div>
```

- [x] **Step 3: Update rute**

Di `routes/web.php`, ganti placeholder about dengan:

```php
use App\Livewire\Public\About;

Route::get('/tentang', About::class)->name('about');
```

- [x] **Step 4: Build & verifikasi**

```bash
npm run build
php artisan route:clear
```

Buka `/tentang` — harus tampil header hijau + kartu Visi/Misi + Sejarah.

- [x] **Step 5: Commit**

```bash
git add -A
git commit -m "feat: public about page (vision, mission, history)"
```

---

## Task 2.4: Daftar Sekolah + filter jenjang

**Files:**
- Create: `app/Livewire/Public/Schools/Index.php`
- Create: `resources/views/livewire/public/schools/index.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/PublicSchoolsTest.php`

- [x] **Step 1: Tulis test gagal**

`tests/Feature/PublicSchoolsTest.php`:

```php
<?php

use App\Models\School;

use function Pest\Laravel\{get};

it('shows school list page', function () {
    School::factory()->create(['name' => 'SD Uji Coba', 'level' => 'SD', 'is_active' => true]);
    get(route('public.schools.index'))->assertOk()->assertSee('SD Uji Coba');
});

it('hides inactive schools', function () {
    School::factory()->create(['name' => 'Sekolah Nonaktif', 'is_active' => false]);
    get(route('public.schools.index'))->assertDontSee('Sekolah Nonaktif');
});
```

- [x] **Step 2: Jalankan test, verifikasi gagal**

```bash
php artisan test --filter=PublicSchoolsTest
```

Expected: FAIL (route/component belum ada).

- [x] **Step 3: Buat component Index**

`app/Livewire/Public/Schools/Index.php`:

```php
<?php

namespace App\Livewire\Public\Schools;

use App\Models\School;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.public')]
#[Title('Sekolah Binaan')]
class Index extends Component
{
    public ?string $level = null;

    public function setLevel(?string $level): void
    {
        $this->level = $level === '' || $level === 'all' ? null : $level;
    }

    #[Computed]
    public function levels(): array
    {
        return ['all', 'TK', 'SD', 'SMP', 'SMA', 'SMK'];
    }

    #[Computed]
    public function schools()
    {
        return School::active()
            ->ordered()
            ->when($this->level, fn ($q) => $q->where('level', $this->level))
            ->get();
    }

    public function render()
    {
        return view('livewire.public.schools.index');
    }
}
```

- [x] **Step 4: Buat view Index**

`resources/views/livewire/public/schools/index.blade.php`:

```blade
<div>
    <section class="text-white py-12" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold">Sekolah Binaan</h1>
            <p class="text-white/85 mt-2">Pendidikan Islam berkualitas di setiap jenjang.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Filter chips --}}
        <div class="flex flex-wrap gap-2 mb-8">
            @foreach ($this->levels as $level)
                @php $active = ($level === 'all') ? is_null($this->level) : ($this->level === $level); @endphp
                <button type="button" wire:click="setLevel('{{ $level }}')"
                        class="px-4 py-2 rounded-full text-sm font-semibold transition-colors"
                        style="{{ $active ? 'background-color: var(--brand-700); color: white;' : 'background-color: white; color: var(--ink-600); border: 1px solid var(--ink-200);' }}">
                    {{ $level === 'all' ? 'Semua' : $level }}
                </button>
            @endforeach
        </div>

        @if ($this->schools->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($this->schools as $school)
                    <x-public.school-card :school="$school" />
                @endforeach
            </div>
        @else
            <p class="text-center text-ink-500 py-12">Belum ada sekolah pada jenjang ini.</p>
        @endif
    </div>
</div>
```

- [x] **Step 5: Update rute**

Di `routes/web.php`, ganti placeholder:

```php
use App\Livewire\Public\Schools\Index as SchoolsIndex;

Route::get('/sekolah', SchoolsIndex::class)->name('schools.index');
```

- [x] **Step 6: Jalankan test, verifikasi lulus**

```bash
php artisan test --filter=PublicSchoolsTest
```

Expected: PASS (2 tes)

- [x] **Step 7: Build & verifikasi manual**

```bash
npm run build
php artisan route:clear
```

Buka `/sekolah` — daftar 3 sekolah + filter chips. Klik filter → daftar berubah (Livewire).

- [x] **Step 8: Commit**

```bash
git add -A
git commit -m "feat: public schools index with level filter"
```

---

## Task 2.5: Profil Sekolah (tab Tentang/PPDB/Galeri)

**Files:**
- Create: `app/Livewire/Public/Schools/Show.php`
- Create: `resources/views/livewire/public/schools/show.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/PublicSchoolsTest.php` (tambah)

- [x] **Step 1: Tambah test**

Tambah ke `tests/Feature/PublicSchoolsTest.php`:

```php
it('shows school profile by slug', function () {
    $school = School::factory()->create(['name' => 'SMA Tunas Bangsa', 'level' => 'SMA']);
    get(route('public.schools.show', $school->slug))->assertOk()->assertSee('SMA Tunas Bangsa');
});

it('returns 404 for unknown slug', function () {
    get(route('public.schools.show', 'tidak-ada'))->assertNotFound();
});
```

- [x] **Step 2: Jalankan test, verifikasi gagal**

```bash
php artisan test --filter=PublicSchoolsTest
```

Expected: 2 tes baru FAIL.

- [x] **Step 3: Buat component Show**

`app/Livewire/Public/Schools/Show.php`:

```php
<?php

namespace App\Livewire\Public\Schools;

use App\Models\School;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class Show extends Component
{
    public School $school;

    public string $tab = 'about';

    public function mount(School $school): void
    {
        abort_unless($school->is_active, 404);
        $this->school = $school;
    }

    public function setTab(string $tab): void
    {
        if (in_array($tab, ['about', 'ppdb', 'gallery'], true)) {
            $this->tab = $tab;
        }
    }

    #[Computed]
    public function ppdb()
    {
        return $this->school->activePpdb();
    }

    #[Computed]
    public function gallery()
    {
        return $this->school->galleryImages()->ordered()->get();
    }

    public function render()
    {
        return view('livewire.public.schools.show', ['title' => $this->school->name]);
    }
}
```

- [x] **Step 4: Buat view Show**

`resources/views/livewire/public/schools/show.blade.php`:

```blade
<div>
    {{-- Cover --}}
    <div class="h-48 relative" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-600));">
        <div class="absolute -bottom-8 left-6 sm:left-10">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-2xl font-bold shadow-soft" style="background-color: var(--color-gold-500); color: var(--brand-900);">
                {{ \Illuminate\Support\Str::limit($school->level, 2, '') }}
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-12">
        {{-- Breadcrumb --}}
        <a href="{{ route('public.schools.index') }}" class="text-sm hover:underline" style="color: var(--brand-700);">← Semua Sekolah</a>

        {{-- Header --}}
        <div class="mt-4">
            <div class="flex flex-wrap items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-ink-900">{{ $school->name }}</h1>
                <span class="text-xs font-semibold px-2 py-1 rounded-full" style="background-color: var(--color-gold-100); color: var(--color-gold-800);">{{ $school->level }}</span>
            </div>
            <div class="text-sm text-ink-500 space-y-1">
                @if ($school->address)<p>📍 {{ $school->address }}</p>@endif
                @if ($school->phone || $school->email)
                    <p>@if ($school->phone)☎ {{ $school->phone }}@endif @if ($school->phone && $school->email)· @endif @if ($school->email)✉ {{ $school->email }}@endif</p>
                @endif
                @if ($school->established_year)<p>Berdiri sejak {{ $school->established_year }}</p>@endif
            </div>
            @if ($school->website_url)
                <a href="{{ $school->website_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 rounded-full px-5 py-2.5 text-sm font-semibold mt-4 text-white" style="background-color: var(--brand-700);">
                    🌐 Kunjungi Website →
                </a>
            @endif
        </div>

        {{-- Tabs --}}
        <div class="flex gap-6 border-b border-ink-200 mt-8">
            @foreach (['about' => 'Tentang', 'ppdb' => 'PPDB', 'gallery' => 'Galeri'] as $key => $label)
                <button type="button" wire:click="setTab('{{ $key }}')"
                        class="pb-3 text-sm font-semibold border-b-2 transition-colors {{ $tab === $key ? 'text-brand-700 border-current' : 'text-ink-500 border-transparent hover:text-ink-800' }}"
                        @style(['border-color' => $tab === $key ? 'var(--brand-700)' : 'transparent'])
                        style="color: {{ $tab === $key ? 'var(--brand-700)' : 'inherit' }};">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Tab content --}}
        <div class="mt-8">
            @if ($tab === 'about')
                <div class="prose prose-ink max-w-none">
                    {!! $school->description ?? '<p class="text-ink-500">Deskripsi belum tersedia.</p>' !!}
                </div>
            @elseif ($tab === 'ppdb')
                @if ($this->ppdb)
                    <div class="bg-white rounded-2xl p-6 shadow-soft">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-ink-900">PPDB {{ $this->ppdb->academic_year }}</h3>
                            <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $this->ppdb->is_open ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                ● {{ $this->ppdb->is_open ? 'Dibuka' : 'Ditutup' }}
                            </span>
                        </div>
                        @if ($this->ppdb->open_date || $this->ppdb->close_date)
                            <p class="text-sm text-ink-600 mb-3">📅 {{ $this->ppdb->open_date?->format('d M Y') }} – {{ $this->ppdb->close_date?->format('d M Y') }}</p>
                        @endif
                        @if ($this->ppdb->requirements)<div class="text-sm text-ink-700 mb-3">{!! $this->ppdb->requirements !!}</div>@endif
                        @if ($this->ppdb->fees)<p class="text-sm text-ink-700 mb-4">Biaya: <strong>{{ $this->ppdb->fees }}</strong></p>@endif
                        @if ($this->ppdb->registration_url)
                            <a href="{{ $this->ppdb->registration_url }}" target="_blank" rel="noopener" class="inline-flex items-center rounded-full px-5 py-2.5 text-sm font-semibold text-white" style="background-color: var(--brand-700);">
                                Daftar di website sekolah →
                            </a>
                        @endif
                    </div>
                @else
                    <p class="text-ink-500">Info PPDB belum tersedia untuk sekolah ini.</p>
                @endif
            @elseif ($tab === 'gallery')
                @if ($this->gallery->isNotEmpty())
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($this->gallery as $image)
                            <div class="aspect-square rounded-xl overflow-hidden shadow-soft">
                                <img src="{{ $image->image_url }}" alt="{{ $image->title ?? '' }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-ink-500">Belum ada foto untuk sekolah ini.</p>
                @endif
            @endif
        </div>
    </div>
</div>
```

- [x] **Step 5: Update rute**

Di `routes/web.php`:

```php
use App\Livewire\Public\Schools\Show as SchoolsShow;

Route::get('/sekolah/{school:slug}', SchoolsShow::class)->name('schools.show');
```

- [x] **Step 6: Jalankan test, verifikasi lulus**

```bash
php artisan test --filter=PublicSchoolsTest
```

Expected: PASS (4 tes)

- [x] **Step 7: Build & verifikasi manual**

```bash
npm run build
php artisan route:clear
```

Buka `/sekolah/sd-islam-daarul-hikmah` — profil sekolah dengan 3 tab. Klik tab → konten berubah.

- [x] **Step 8: Commit**

```bash
git add -A
git commit -m "feat: public school profile with tabs (about/ppdb/gallery)"
```

---

## Task 2.6: Berita (daftar + artikel)

**Files:**
- Create: `app/Livewire/Public/News/Index.php`
- Create: `app/Livewire/Public/News/Show.php`
- Create: `resources/views/livewire/public/news/index.blade.php`
- Create: `resources/views/livewire/public/news/show.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/PublicNewsTest.php`

- [x] **Step 1: Tulis test gagal**

`tests/Feature/PublicNewsTest.php`:

```php
<?php

use App\Models\News;
use App\Models\User;

use function Pest\Laravel\{get};

it('shows published news list', function () {
    $author = User::factory()->create();
    $news = News::factory()->create(['title' => 'Berita Uji Coba', 'is_published' => true, 'published_at' => now(), 'author_id' => $author->id]);
    get(route('public.news.index'))->assertOk()->assertSee('Berita Uji Coba');
});

it('hides draft news', function () {
    $news = News::factory()->create(['title' => 'Draft Rahasia', 'is_published' => false]);
    get(route('public.news.index'))->assertDontSee('Draft Rahasia');
});

it('shows news article by slug', function () {
    $news = News::factory()->create(['title' => 'Artikel Lengkap', 'is_published' => true, 'published_at' => now()]);
    get(route('public.news.show', $news->slug))->assertOk()->assertSee('Artikel Lengkap');
});
```

- [x] **Step 2: Jalankan test, verifikasi gagal**

```bash
php artisan test --filter=PublicNewsTest
```

Expected: FAIL.

- [x] **Step 3: Buat component Index**

`app/Livewire/Public/News/Index.php`:

```php
<?php

namespace App\Livewire\Public\News;

use App\Models\News;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\Component;

#[Layout('components.layouts.public')]
#[Title('Berita')]
class Index extends Component
{
    use WithPagination;

    public ?string $category = null;

    public function setCategory(?string $category): void
    {
        $this->category = ($category === '' || $category === 'all') ? null : $category;
        $this->resetPage();
    }

    #[Computed]
    public function categories(): array
    {
        return ['all', 'yayasan', 'sekolah'];
    }

    public function render()
    {
        $news = News::published()
            ->when($this->category, fn ($q) => $q->where('category', $this->category))
            ->latest('published_at')
            ->paginate(9);

        $featured = $this->category ? null : News::published()->latest('published_at')->first();

        return view('livewire.public.news.index', compact('news', 'featured'));
    }
}
```

- [x] **Step 4: Buat view Index**

`resources/views/livewire/public/news/index.blade.php`:

```blade
<div>
    <section class="text-white py-12" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold">Berita &amp; Kegiatan</h1>
            <p class="text-white/85 mt-2">Kabar terbaru dari yayasan &amp; sekolah.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Category filter --}}
        <div class="flex flex-wrap gap-2 mb-8">
            @foreach ($this->categories as $cat)
                @php $active = ($cat === 'all') ? is_null($this->category) : ($this->category === $cat); @endphp
                <button type="button" wire:click="setCategory('{{ $cat }}')"
                        class="px-4 py-2 rounded-full text-sm font-semibold capitalize transition-colors"
                        style="{{ $active ? 'background-color: var(--brand-700); color: white;' : 'background-color: white; color: var(--ink-600); border: 1px solid var(--ink-200);' }}">
                    {{ $cat === 'all' ? 'Semua' : $cat }}
                </button>
            @endforeach
        </div>

        {{-- Featured --}}
        @if ($featured && ! $this->category)
            <div class="mb-10">
                <x-public.news-card :news="$featured" />
            </div>
        @endif

        {{-- Grid --}}
        @if ($news->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($news as $item)
                    @if (! ($featured && $item->id === $featured->id && ! $this->category))
                        <x-public.news-card :news="$item" />
                    @endif
                @endforeach
            </div>
            <div class="mt-10">
                {{ $news->links() }}
            </div>
        @else
            <p class="text-center text-ink-500 py-12">Belum ada berita pada kategori ini.</p>
        @endif
    </div>
</div>
```

- [x] **Step 5: Buat component Show**

`app/Livewire/Public/News/Show.php`:

```php
<?php

namespace App\Livewire\Public\News;

use App\Models\News;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class Show extends Component
{
    public News $news;

    public function mount(News $news): void
    {
        abort_unless($news->is_published && ($news->published_at === null || $news->published_at <= now()), 404);
        $this->news = $news;
    }

    public function render()
    {
        return view('livewire.public.news.show', ['title' => $this->news->title]);
    }
}
```

- [x] **Step 6: Buat view Show**

`resources/views/livewire/public/news/show.blade.php`:

```blade
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('public.news.index') }}" class="text-sm hover:underline" style="color: var(--brand-700);">← Semua Berita</a>

    <article class="mt-4">
        <span class="text-xs font-semibold px-2 py-1 rounded-full uppercase" style="background-color: var(--color-gold-100); color: var(--color-gold-800);">{{ $news->category }}</span>
        <h1 class="text-3xl font-bold text-ink-900 mt-3">{{ $news->title }}</h1>
        <p class="text-sm text-ink-500 mt-2">
            {{ $news->published_at?->format('d M Y') }} · {{ $news->reading_time }} min baca
            @if ($news->author) · {{ $news->author->name }} @endif
            @if ($news->school) · <a href="{{ route('public.schools.show', $news->school->slug) }}" class="hover:underline">{{ $news->school->name }}</a> @endif
        </p>

        @if ($news->cover_image_url)
            <img src="{{ $news->cover_image_url }}" alt="{{ $news->title }}" class="w-full h-64 object-cover rounded-2xl mt-6 shadow-soft">
        @endif

        @if ($news->excerpt)
            <p class="text-lg text-ink-700 mt-6 font-medium leading-relaxed">{{ $news->excerpt }}</p>
        @endif

        <div class="prose prose-ink max-w-none mt-6">
            {!! $news->body !!}
        </div>
    </article>
</div>
```

- [x] **Step 7: Update rute**

Di `routes/web.php`:

```php
use App\Livewire\Public\News\Index as NewsIndex;
use App\Livewire\Public\News\Show as NewsShow;

Route::get('/berita', NewsIndex::class)->name('news.index');
Route::get('/berita/{news:slug}', NewsShow::class)->name('news.show');
```

- [x] **Step 8: Jalankan test, verifikasi lulus**

```bash
php artisan test --filter=PublicNewsTest
```

Expected: PASS (3 tes)

- [x] **Step 9: Build & verifikasi manual**

```bash
npm run build
php artisan route:clear
```

Buka `/berita` → daftar + filter + paginasi. Klik artikel → halaman detail.

- [x] **Step 10: Commit**

```bash
git add -A
git commit -m "feat: public news (list with filter/pagination + article detail)"
```

---

## Task 2.7: Galeri (grid masonry + lightbox)

**Files:**
- Create: `app/Livewire/Public/Gallery/Index.php`
- Create: `resources/views/livewire/public/gallery/index.blade.php`
- Modify: `routes/web.php`

- [x] **Step 1: Buat component Index**

`app/Livewire/Public/Gallery/Index.php`:

```php
<?php

namespace App\Livewire\Public\Gallery;

use App\Models\GalleryImage;
use App\Models\School;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.public')]
#[Title('Galeri')]
class Index extends Component
{
    public ?int $schoolId = null;

    public function setSchool(?int $schoolId): void
    {
        $this->schoolId = $schoolId ?: null;
    }

    #[Computed]
    public function schoolsWithImages()
    {
        return School::whereHas('galleryImages')->ordered()->get(['id', 'name']);
    }

    #[Computed]
    public function images()
    {
        return GalleryImage::with('school')
            ->when($this->schoolId, fn ($q) => $q->where('school_id', $this->schoolId))
            ->ordered()
            ->get();
    }

    public function render()
    {
        return view('livewire.public.gallery.index');
    }
}
```

- [x] **Step 2: Buat view Index (dengan lightbox Alpine)**

`resources/views/livewire/public/gallery/index.blade.php`:

```blade
<div x-data="{ lightbox: null, open(url, caption) { this.lightbox = { url, caption }; document.body.style.overflow = 'hidden'; }, close() { this.lightbox = null; document.body.style.overflow = ''; } }" @keydown.escape.window="close()">
    <section class="text-white py-12" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold">Galeri Foto</h1>
            <p class="text-white/85 mt-2">Momen kegiatan yayasan &amp; sekolah.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- School filter --}}
        @if ($this->schoolsWithImages->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-8">
                <button type="button" wire:click="setSchool(null)"
                        class="px-4 py-2 rounded-full text-sm font-semibold transition-colors"
                        style="{{ is_null($schoolId) ? 'background-color: var(--brand-700); color: white;' : 'background-color: white; color: var(--ink-600); border: 1px solid var(--ink-200);' }}">
                    Semua
                </button>
                @foreach ($this->schoolsWithImages as $school)
                    <button type="button" wire:click="setSchool({{ $school->id }})"
                            class="px-4 py-2 rounded-full text-sm font-semibold transition-colors"
                            style="{{ $schoolId === $school->id ? 'background-color: var(--brand-700); color: white;' : 'background-color: white; color: var(--ink-600); border: 1px solid var(--ink-200);' }}">
                        {{ $school->name }}
                    </button>
                @endforeach
            </div>
        @endif

        {{-- Masonry-style grid (columns) --}}
        @if ($this->images->isNotEmpty())
            <div class="columns-2 sm:columns-3 lg:columns-4 gap-4 [&>*]:mb-4">
                @foreach ($this->images as $image)
                    <button type="button" @click="open('{{ $image->image_url }}', '{{ addslashes($image->caption ?? $image->title ?? '') }}')"
                            class="block w-full break-inside-avoid rounded-xl overflow-hidden shadow-soft hover:opacity-90 transition-opacity">
                        <img src="{{ $image->image_url }}" alt="{{ $image->title ?? '' }}" class="w-full h-auto">
                    </button>
                @endforeach
            </div>
        @else
            <p class="text-center text-ink-500 py-12">Belum ada foto di galeri.</p>
        @endif
    </div>

    {{-- Lightbox --}}
    <template x-if="lightbox">
        <div class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" @click="close()">
            <div class="max-w-4xl max-h-full" @click.stop>
                <img :src="lightbox?.url" :alt="lightbox?.caption" class="max-w-full max-h-[80vh] rounded-xl">
                <p x-text="lightbox?.caption" class="text-white/90 text-center mt-3 text-sm" x-show="lightbox?.caption"></p>
                <button type="button" @click="close()" class="absolute top-4 right-4 text-white text-3xl">×</button>
            </div>
        </div>
    </template>
</div>
```

- [x] **Step 3: Update rute**

Di `routes/web.php`:

```php
use App\Livewire\Public\Gallery\Index as GalleryIndex;

Route::get('/galeri', GalleryIndex::class)->name('gallery.index');
```

- [x] **Step 4: Build & verifikasi**

```bash
npm run build
php artisan route:clear
```

Buka `/galeri` → grid foto masonry. Klik foto → lightbox.

- [x] **Step 5: Commit**

```bash
git add -A
git commit -m "feat: public gallery with masonry grid + lightbox"
```

---

## Task 2.8: Halaman PPDB

**Files:**
- Create: `app/Livewire/Public/Ppdb.php`
- Create: `resources/views/livewire/public/ppdb.blade.php`
- Modify: `routes/web.php`

- [x] **Step 1: Buat component Ppdb**

`app/Livewire/Public/Ppdb.php`:

```php
<?php

namespace App\Livewire\Public;

use App\Models\PpdbInfo;
use App\Models\SiteSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.public')]
#[Title('PPDB')]
class Ppdb extends Component
{
    #[Computed]
    public function ppdbInfos()
    {
        return PpdbInfo::with('school')
            ->whereHas('school', fn ($q) => $q->where('is_active', true))
            ->latest('academic_year')
            ->get()
            ->groupBy('academic_year');
    }

    public function render()
    {
        return view('livewire.public.ppdb', [
            'settings' => SiteSetting::current(),
        ]);
    }
}
```

- [x] **Step 2: Buat view Ppdb**

`resources/views/livewire/public/ppdb.blade.php`:

```blade
<div>
    {{-- Hero --}}
    <section class="py-16 text-white text-center" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-sm font-semibold tracking-wider uppercase mb-3" style="color: var(--color-gold-500);">Penerimaan Peserta Didik Baru</p>
            <h1 class="text-3xl md:text-4xl font-bold">PPDB Yayasan Daarul Hikmah Al Madani</h1>
            <p class="mt-4 text-white/85">Pendaftaran dilakukan di website masing-masing sekolah. Pilih sekolah tujuan di bawah.</p>
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
        @forelse ($this->ppdbInfos as $year => $infos)
            <section>
                <h2 class="text-2xl font-bold text-ink-900 mb-6">Tahun Ajaran {{ $year }}</h2>
                <div class="grid gap-4">
                    @foreach ($infos as $info)
                        <div class="bg-white rounded-2xl p-6 shadow-soft flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-bold text-ink-900">{{ $info->school->name }}</h3>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background-color: var(--color-gold-100); color: var(--color-gold-800);">{{ $info->school->level }}</span>
                                </div>
                                <p class="text-sm text-ink-500 mt-1">
                                    @if ($info->open_date || $info->close_date)
                                        📅 {{ $info->open_date?->format('d M Y') }} – {{ $info->close_date?->format('d M Y') }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $info->is_open ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                    ● {{ $info->is_open ? 'Dibuka' : 'Ditutup' }}
                                </span>
                                <a href="{{ route('public.schools.show', $info->school->slug) }}" class="text-sm font-semibold hover:underline" style="color: var(--brand-700);">Detail sekolah</a>
                                @if ($info->registration_url && $info->is_open)
                                    <a href="{{ $info->registration_url }}" target="_blank" rel="noopener" class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold text-white" style="background-color: var(--brand-700);">Daftar →</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <p class="text-center text-ink-500 py-12">Info PPDB belum tersedia. Silakan hubungi yayasan.</p>
        @endforelse

        {{-- Kontak --}}
        <div class="rounded-2xl p-8 text-center text-white" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
            <h3 class="text-xl font-bold mb-2">Butuh bantuan?</h3>
            <p class="text-white/85 mb-4">Hubungi kami untuk informasi lebih lanjut.</p>
            @if ($settings->phone || $settings->email)
                <div class="flex justify-center gap-6 text-sm">
                    @if ($settings->phone)<span>☎ {{ $settings->phone }}</span>@endif
                    @if ($settings->email)<span>✉ {{ $settings->email }}</span>@endif
                </div>
            @endif
        </div>
    </div>
</div>
```

- [x] **Step 3: Update rute**

Di `routes/web.php`:

```php
use App\Livewire\Public\Ppdb;

Route::get('/ppdb', Ppdb::class)->name('ppdb');
```

- [x] **Step 4: Build & verifikasi**

```bash
npm run build
php artisan route:clear
```

Buka `/ppdb` → daftar PPDB per tahun ajaran + status + kontak.

- [x] **Step 5: Commit**

```bash
git add -A
git commit -m "feat: public PPDB page (per-school status + registration links)"
```

---

## Task 2.9: Storage link & finalisasi Fase 2

**Files:**
- Create: `README.md`

- [x] **Step 1: Buat storage symlink**

```bash
php artisan storage:link
```

- [x] **Step 2: Jalankan semua tes publik**

```bash
php artisan test --filter=Public
```

Expected: semua PASS (9 tes dari Task 2.4, 2.5, 2.6).

- [x] **Step 3: Tulis README**

`README.md`:

````markdown
# Website Yayasan Pendidikan Daarul Hikmah Al Madani

Website publik portal yayasan dengan admin panel.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate:fresh --seed
npm install && npm run build
php artisan serve
```

Login admin: `admin@example.com` / `password`

## Pengembangan

```bash
composer dev   # server + queue + logs + vite
```
````

- [x] **Step 4: Commit**

```bash
git add -A
git commit -m "docs: README + storage link setup"
```

---

# FASE 3 — Admin Panel

## Task 3.1: Dashboard admin baru (statistik yayasan)

**Files:**
- Modify: `app/Livewire/Admin/Dashboard.php`
- Modify: `resources/views/livewire/admin/dashboard.blade.php`
- Test: `tests/Feature/AdminAccessTest.php` (tambah)

- [x] **Step 1: Ganti isi `app/Livewire/Admin/Dashboard.php`**

```php
<?php

namespace App\Livewire\Admin;

use App\Models\GalleryImage;
use App\Models\News;
use App\Models\School;
use App\Models\SiteSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Dashboard Admin')]
class Dashboard extends Component
{
    #[Computed]
    public function stats(): array
    {
        return [
            'schools' => School::count(),
            'active_schools' => School::active()->count(),
            'news' => News::count(),
            'published_news' => News::published()->count(),
            'gallery' => GalleryImage::count(),
        ];
    }

    #[Computed]
    public function recentNews()
    {
        return News::with('school')->latest()->limit(5)->get();
    }

    #[Computed]
    public function settings()
    {
        return SiteSetting::current();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
```

- [x] **Step 2: Ganti isi `resources/views/livewire/admin/dashboard.blade.php`**

```blade
<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-ink-900 mb-1">Dashboard</h2>
            <p class="text-ink-500 text-sm">Ringkasan konten Yayasan Daarul Hikmah Al Madani.</p>
        </div>
        <span class="text-sm text-ink-500">Selamat datang, {{ auth()->user()->name }}</span>
    </div>

    {{-- Stats grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-soft">
            <p class="text-xs text-ink-500 uppercase tracking-wide">Sekolah</p>
            <p class="text-3xl font-bold mt-2" style="color: var(--brand-700);">{{ $this->stats['schools'] }}</p>
            <p class="text-xs text-ink-500 mt-1">{{ $this->stats['active_schools'] }} aktif</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-soft">
            <p class="text-xs text-ink-500 uppercase tracking-wide">Berita</p>
            <p class="text-3xl font-bold mt-2" style="color: var(--brand-700);">{{ $this->stats['news'] }}</p>
            <p class="text-xs text-ink-500 mt-1">{{ $this->stats['published_news'] }} terbit</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-soft">
            <p class="text-xs text-ink-500 uppercase tracking-wide">Galeri</p>
            <p class="text-3xl font-bold mt-2" style="color: var(--brand-700);">{{ $this->stats['gallery'] }}</p>
            <p class="text-xs text-ink-500 mt-1">foto</p>
        </div>
        <div class="rounded-2xl p-6 text-white" style="background: linear-gradient(135deg, var(--brand-700), var(--brand-900));">
            <p class="text-xs uppercase tracking-wide" style="color: var(--color-gold-500);">Yayasan</p>
            <p class="text-sm font-bold mt-2 leading-tight">{{ $this->settings->name }}</p>
        </div>
    </div>

    {{-- Recent news --}}
    <div class="bg-white rounded-2xl p-6 shadow-soft">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-ink-900">Berita Terbaru</h3>
            <a href="{{ route('admin.news.index') }}" class="text-sm font-semibold hover:underline" style="color: var(--brand-700);">Kelola →</a>
        </div>
        @if ($this->recentNews->isNotEmpty())
            <ul class="divide-y divide-ink-100">
                @foreach ($this->recentNews as $news)
                    <li class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-ink-800">{{ $news->title }}</p>
                            <p class="text-xs text-ink-500">{{ $news->published_at?->format('d M Y') ?? 'Draft' }} · {{ $news->school?->name ?? 'Yayasan' }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $news->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-ink-50 text-ink-500' }}">
                            {{ $news->is_published ? 'Terbit' : 'Draft' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-ink-500 text-sm">Belum ada berita.</p>
        @endif
    </div>
</div>
```

- [x] **Step 3: Tambah test**

Tambah ke `tests/Feature/AdminAccessTest.php`:

```php
it('admin dashboard shows school count', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    \App\Models\School::factory()->count(2)->create();
    actingAs($admin)->get(route('admin.dashboard'))->assertOk()->assertSee('Sekolah');
});
```

- [x] **Step 4: Jalankan test**

```bash
php artisan test --filter=AdminAccessTest
```

Expected: PASS (4 tes).

- [x] **Step 5: Build & verifikasi**

```bash
npm run build
```

Buka `/admin` (login sebagai admin) → dashboard yayasan dengan stats + berita terbaru.

- [x] **Step 6: Commit**

```bash
git add -A
git commit -m "feat: admin dashboard with yayasan stats"
```

---

## Task 3.2: Tambah menu sidebar admin

**Files:**
- Modify: `resources/views/components/admin/sidebar.blade.php`
- Modify: `resources/views/components/admin/icon.blade.php`

- [x] **Step 1: Tambah ikon baru di komponen icon**

Komponen `resources/views/components/admin/icon.blade.php` memakai pola `@switch($name)` → `@case('...')` → `<svg>...</svg>` → `@break` (verifikasi struktur ini sebelum mengedit). Tambah 4 case baru **sebelum** `@default`, ikuti pola yang persis sama (tag `<svg>` lengkap dengan `class="{{ $class }}"` dan `{!! $svgAttrs !!}`):

```blade
    @case('school')
        <svg class="{{ $class }}" {!! $svgAttrs !!}>
            <path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4" />
            <path d="M9 9v.01M9 13v.01M9 17v.01" />
        </svg>
    @break

    @case('news')
        <svg class="{{ $class }}" {!! $svgAttrs !!}>
            <rect x="3" y="4" width="18" height="16" rx="2" />
            <path d="M7 8h10M7 12h10M7 16h6" />
        </svg>
    @break

    @case('gallery')
        <svg class="{{ $class }}" {!! $svgAttrs !!}>
            <rect x="3" y="3" width="18" height="18" rx="2" />
            <circle cx="9" cy="9" r="2" />
            <path d="M21 15l-5-5L5 21" />
        </svg>
    @break

    @case('site')
        <svg class="{{ $class }}" {!! $svgAttrs !!}>
            <circle cx="12" cy="12" r="3" />
            <path d="M12 1v6M12 17v6M4.22 4.22l4.24 4.24M15.54 15.54l4.24 4.24M1 12h6M17 12h6M4.22 19.78l4.24-4.24M15.54 8.46l4.24-4.24" />
        </svg>
    @break
```

- [x] **Step 2: Update menu sidebar**

Di `resources/views/components/admin/sidebar.blade.php`, ubah array `$nav`:

```blade
@php
    $user = auth()->user();
    $nav = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
        ['route' => 'admin.schools.index', 'label' => 'Sekolah', 'icon' => 'school'],
        ['route' => 'admin.news.index', 'label' => 'Berita', 'icon' => 'news'],
        ['route' => 'admin.gallery.index', 'label' => 'Galeri', 'icon' => 'gallery'],
        ['route' => 'admin.settings.index', 'label' => 'Pengaturan', 'icon' => 'site'],
    ];
@endphp
```

(Simpan link ke profil/tema user & logout di bawah seperti asal.)

- [x] **Step 3: Build & verifikasi**

```bash
npm run build
```

Buka `/admin` → sidebar harus tampilkan 5 ikon (Dashboard, Sekolah, Berita, Galeri, Pengaturan).

- [x] **Step 4: Commit**

```bash
git add resources/views/components/admin/
git commit -m "feat: admin sidebar menu for content management"
```

---

## Task 3.3: CRUD Sekolah

**Files:**
- Create: `app/Livewire/Admin/Schools/Index.php`
- Create: `app/Livewire/Admin/Schools/Form.php`
- Create: `resources/views/livewire/admin/schools/index.blade.php`
- Create: `resources/views/livewire/admin/schools/form.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/AdminSchoolsTest.php`

- [x] **Step 1: Tulis test gagal**

`tests/Feature/AdminSchoolsTest.php`:

```php
<?php

use App\Livewire\Admin\Schools\Form;
use App\Models\School;
use App\Models\User;

use function Pest\Laravel\{actingAs, get};

beforeEach(fn () => $this->admin = User::factory()->create(['is_admin' => true]));

it('shows school index to admin', function () {
    actingAs($this->admin)->get(route('admin.schools.index'))->assertOk();
});

it('creates a school via livewire', function () {
    Livewire::actingAs($this->admin)->test(Form::class)
        ->set('name', 'SMA Baru')
        ->set('level', 'SMA')
        ->set('address', 'Jl. Test')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.schools.index'));

    expect(School::where('name', 'SMA Baru')->exists())->toBeTrue();
});

it('validates required fields', function () {
    Livewire::actingAs($this->admin)->test(Form::class)
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name']);
});
```

- [x] **Step 2: Jalankan test, verifikasi gagal**

```bash
php artisan test --filter=AdminSchoolsTest
```

Expected: FAIL.

- [x] **Step 3: Buat Index component**

`app/Livewire/Admin/Schools/Index.php`:

```php
<?php

namespace App\Livewire\Admin\Schools;

use App\Models\School;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Kelola Sekolah')]
class Index extends Component
{
    use WithPagination;

    public ?string $search = null;
    public ?int $deleteId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
    }

    public function delete(): void
    {
        School::findOrFail($this->deleteId)?->delete();
        $this->deleteId = null;
        $this->dispatch('school-deleted');
    }

    #[Computed]
    public function schools()
    {
        return School::where('name', 'like', '%'.$this->search.'%')
            ->ordered()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.schools.index');
    }
}
```

- [x] **Step 4: Buat Index view**

`resources/views/livewire/admin/schools/index.blade.php`:

```blade
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-ink-900">Kelola Sekolah</h2>
            <p class="text-ink-500 text-sm">{{ $this->schools->total() }} sekolah</p>
        </div>
        <a href="{{ route('admin.schools.create') }}" class="btn-primary">+ Tambah Sekolah</a>
    </div>

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari sekolah..." class="chip-input mb-6 max-w-sm">

    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <table class="w-full text-left">
            <thead class="text-xs text-ink-400 border-b border-ink-100 uppercase">
                <tr>
                    <th class="font-medium px-4 py-3">Nama</th>
                    <th class="font-medium px-4 py-3">Jenjang</th>
                    <th class="font-medium px-4 py-3">Status</th>
                    <th class="font-medium px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-ink-800">
                @forelse ($this->schools as $school)
                    <tr class="border-b border-ink-50 last:border-0">
                        <td class="px-4 py-3 font-medium">{{ $school->name }}</td>
                        <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background-color: var(--color-gold-100); color: var(--color-gold-800);">{{ $school->level }}</span></td>
                        <td class="px-4 py-3">
                            <span class="text-xs {{ $school->is_active ? 'text-emerald-600' : 'text-ink-400' }}">{{ $school->is_active ? '● Aktif' : '○ Nonaktif' }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.schools.edit', $school) }}" class="text-sm font-medium hover:underline" style="color: var(--brand-700);">Edit</a>
                            <button type="button" wire:click="confirmDelete({{ $school->id }})" class="text-sm font-medium text-red-600 hover:underline ml-3">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-ink-500">Belum ada sekolah.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $this->schools->links() }}</div>

    {{-- Delete confirm modal --}}
    @if ($deleteId)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" wire:click="$set('deleteId', null)">
            <div class="bg-white rounded-2xl p-6 max-w-sm w-full" @click.stop>
                <h3 class="font-bold text-ink-900 mb-2">Hapus sekolah?</h3>
                <p class="text-sm text-ink-500 mb-6">Tindakan ini tidak bisa dibatalkan. Berita & galeri terkait juga akan terhapus.</p>
                <div class="flex gap-3 justify-end">
                    <button type="button" wire:click="$set('deleteId', null)" class="btn-ghost">Batal</button>
                    <button type="button" wire:click="delete" class="inline-flex items-center rounded-full px-5 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700">Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>
```

- [x] **Step 5: Buat Form component**

`app/Livewire/Admin/Schools/Form.php`:

```php
<?php

namespace App\Livewire\Admin\Schools;

use App\Models\School;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Form Sekolah')]
class Form extends Component
{
    use WithFileUploads;

    public ?School $school = null;
    public string $name = '';
    public string $level = 'SD';
    public ?string $description = null;
    public ?string $address = null;
    public ?string $phone = null;
    public ?string $email = null;
    public ?string $website_url = null;
    public ?int $established_year = null;
    public bool $is_active = true;
    public $logo = null;       // temporary upload
    public $cover_image = null;
    public int $sort_order = 0;

    public function mount(?School $school = null): void
    {
        if ($school->exists) {
            $this->school = $school;
            $this->fill($school->only(['name', 'level', 'description', 'address', 'phone', 'email', 'website_url', 'established_year', 'is_active', 'sort_order']));
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|in:TK,SD,SMP,SMA,SMK',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:32',
            'email' => 'nullable|email|max:255',
            'website_url' => 'nullable|url|max:255',
            'established_year' => 'nullable|integer|min:1900|max:'.(date('Y') + 1),
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'logo' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:4096',
        ]);

        if ($this->logo) {
            $validated['logo'] = $this->logo->store('schools', 'public');
        }
        if ($this->cover_image) {
            $validated['cover_image'] = $this->cover_image->store('schools/covers', 'public');
        }

        if ($this->school && $this->school->exists) {
            $this->school->update($validated);
            flash('Sekolah diperbarui.', 'success');
        } else {
            School::create($validated);
            flash('Sekolah dibuat.', 'success');
        }

        $this->redirect(route('admin.schools.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.schools.form');
    }
}
```

- [x] **Step 6: Buat Form view**

`resources/views/livewire/admin/schools/form.blade.php`:

```blade
<div>
    <div class="mb-6">
        <a href="{{ route('admin.schools.index') }}" class="text-sm hover:underline" style="color: var(--brand-700);">← Kembali</a>
        <h2 class="text-2xl font-bold text-ink-900 mt-2">{{ $school && $school->exists ? 'Edit Sekolah' : 'Tambah Sekolah' }}</h2>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl shadow-soft p-6 space-y-5 max-w-2xl">
        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Nama Sekolah *</label>
            <input type="text" wire:model="name" class="chip-input" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Jenjang *</label>
                <select wire:model="level" class="chip-input">
                    <option value="TK">TK</option>
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SMA">SMA</option>
                    <option value="SMK">SMK</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Tahun Berdiri</label>
                <input type="number" wire:model="established_year" min="1900" max="{{ date('Y') + 1 }}" class="chip-input">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Deskripsi</label>
            <textarea wire:model="description" rows="4" class="chip-input" placeholder="Deskripsi sekolah..."></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Alamat</label>
            <input type="text" wire:model="address" class="chip-input">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Telepon</label>
                <input type="text" wire:model="phone" class="chip-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Email</label>
                <input type="email" wire:model="email" class="chip-input">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">URL Website Sekolah</label>
            <input type="url" wire:model="website_url" class="chip-input" placeholder="https://">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Logo</label>
                <input type="file" wire:model="logo" accept="image/*" class="text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Cover</label>
                <input type="file" wire:model="cover_image" accept="image/*" class="text-sm">
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" wire:model="is_active" id="active" class="rounded">
            <label for="active" class="text-sm text-ink-700">Sekolah aktif (tampil di publik)</label>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-ink-100">
            <a href="{{ route('admin.schools.index') }}" class="btn-ghost">Batal</a>
            <button type="submit" class="btn-primary">Simpan</button>
        </div>
    </form>
</div>
```

- [x] **Step 7: Tambah rute admin sekolah**

Di `routes/web.php`, perbarui grup admin:

```php
use App\Livewire\Admin\Schools\Index as AdminSchoolsIndex;
use App\Livewire\Admin\Schools\Form as AdminSchoolsForm;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('/sekolah', AdminSchoolsIndex::class)->name('schools.index');
    Route::get('/sekolah/baru', AdminSchoolsForm::class)->name('schools.create');
    Route::get('/sekolah/{school}/edit', AdminSchoolsForm::class)->name('schools.edit');
});
```

(Ganti blok grup `admin` yang lama dengan blok di atas yang sudah memakai middleware array.)

- [x] **Step 8: Jalankan test, verifikasi lulus**

```bash
php artisan test --filter=AdminSchoolsTest
```

Expected: PASS (3 tes).

- [x] **Step 9: Build & verifikasi manual**

```bash
npm run build
php artisan route:clear
```

Login admin → klik "Sekolah" di sidebar → daftar sekolah. Klik "+ Tambah Sekolah" → isi form → simpan. Klik Edit/Hapus.

- [x] **Step 10: Commit**

```bash
git add -A
git commit -m "feat: admin CRUD for schools"
```

---

## Task 3.4: CRUD Berita (dengan Trix)

**Files:**
- Modify: `package.json` (tambah trix), `resources/js/app.js`
- Create: `app/Livewire/Admin/News/Index.php`
- Create: `app/Livewire/Admin/News/Form.php`
- Create: `resources/views/livewire/admin/news/index.blade.php`
- Create: `resources/views/livewire/admin/news/form.blade.php`
- Create: `resources/views/components/trix-editor.blade.php`
- Modify: `routes/web.php`

- [x] **Step 1: Install Trix**

```bash
npm install trix
```

- [x] **Step 2: Inisialisasi Trix di `resources/js/app.js`**

```js
import 'trix';
import 'trix/dist/trix.css';
```

- [x] **Step 3: Buat komponen Trix reusable**

`resources/views/components/trix-editor.blade.php`:

```blade
@props(['model' => null, 'id' => null, 'placeholder' => ''])

<div {{ $attributes->merge(['class' => '']) }}>
    <input
        type="hidden"
        wire:model="{{ $model }}"
    >
    <trix-editor
        input="{{ $id ?? 'trix-input' }}"
        placeholder="{{ $placeholder }}"
        class="trix-content rounded-2xl border border-ink-200 bg-white"
    ></trix-editor>
</div>

@push('scripts')
    <script>
        document.addEventListener('trix-change', function (e) {
            const inputId = e.target.inputElement?.id || 'trix-input';
            const hidden = document.querySelector(`input[wire\\:model]#${inputId}`) || document.querySelector('input[wire\\:model]');
            if (hidden) {
                hidden.value = e.target.innerHTML;
                hidden.dispatchEvent(new Event('input'));
            }
        });
    </script>
@endpush
```

- [x] **Step 4: Buat News Index component + view**

(Pola identik dengan Schools, ganti field `level` → `category`, `name` → `title`.)

`app/Livewire/Admin/News/Index.php`:

```php
<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Kelola Berita')]
class Index extends Component
{
    use WithPagination;

    public ?string $search = null;
    public ?int $deleteId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
    }

    public function delete(): void
    {
        News::findOrFail($this->deleteId)?->delete();
        $this->deleteId = null;
        $this->dispatch('news-deleted');
    }

    #[Computed]
    public function news()
    {
        return News::with('school')
            ->where('title', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.news.index');
    }
}
```

`resources/views/livewire/admin/news/index.blade.php`:

```blade
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-ink-900">Kelola Berita</h2>
            <p class="text-ink-500 text-sm">{{ $this->news->total() }} berita</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="btn-primary">+ Tambah Berita</a>
    </div>

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berita..." class="chip-input mb-6 max-w-sm">

    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <table class="w-full text-left">
            <thead class="text-xs text-ink-400 border-b border-ink-100 uppercase">
                <tr>
                    <th class="font-medium px-4 py-3">Judul</th>
                    <th class="font-medium px-4 py-3">Kategori</th>
                    <th class="font-medium px-4 py-3">Status</th>
                    <th class="font-medium px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-ink-800">
                @forelse ($this->news as $item)
                    <tr class="border-b border-ink-50 last:border-0">
                        <td class="px-4 py-3 font-medium">{{ $item->title }}</td>
                        <td class="px-4 py-3 capitalize">{{ $item->category }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $item->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-ink-50 text-ink-500' }}">{{ $item->is_published ? 'Terbit' : 'Draft' }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.news.edit', $item) }}" class="text-sm font-medium hover:underline" style="color: var(--brand-700);">Edit</a>
                            <button type="button" wire:click="confirmDelete({{ $item->id }})" class="text-sm font-medium text-red-600 hover:underline ml-3">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-ink-500">Belum ada berita.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $this->news->links() }}</div>

    @if ($deleteId)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" wire:click="$set('deleteId', null)">
            <div class="bg-white rounded-2xl p-6 max-w-sm w-full" @click.stop>
                <h3 class="font-bold text-ink-900 mb-2">Hapus berita?</h3>
                <p class="text-sm text-ink-500 mb-6">Tindakan ini tidak bisa dibatalkan.</p>
                <div class="flex gap-3 justify-end">
                    <button type="button" wire:click="$set('deleteId', null)" class="btn-ghost">Batal</button>
                    <button type="button" wire:click="delete" class="inline-flex items-center rounded-full px-5 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700">Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>
```

- [x] **Step 5: Buat News Form component**

`app/Livewire/Admin/News/Form.php`:

```php
<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use App\Models\School;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Form Berita')]
class Form extends Component
{
    use WithFileUploads;

    public ?News $news = null;
    public string $title = '';
    public ?string $excerpt = null;
    public ?string $body = null;
    public string $category = 'yayasan';
    public ?int $school_id = null;
    public ?string $published_at = null;
    public bool $is_published = false;
    public $cover_image = null;

    public function mount(?News $news = null): void
    {
        if ($news->exists) {
            $this->news = $news;
            $this->fill($news->only(['title', 'excerpt', 'body', 'category', 'school_id', 'is_published']));
            $this->published_at = $news->published_at?->format('Y-m-d\TH:i');
        }
    }

    #[Computed]
    public function schools()
    {
        return School::active()->ordered()->get(['id', 'name']);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'body' => 'nullable|string',
            'category' => 'required|in:yayasan,sekolah',
            'school_id' => 'nullable|exists:schools,id',
            'published_at' => 'nullable|date',
            'is_published' => 'boolean',
            'cover_image' => 'nullable|image|max:4096',
        ]);

        if ($this->cover_image) {
            $validated['cover_image'] = $this->cover_image->store('news', 'public');
        }
        $validated['published_at'] = $validated['published_at'] ?: null;

        if ($this->news && $this->news->exists) {
            $this->news->update($validated);
        } else {
            $validated['author_id'] = auth()->id();
            News::create($validated);
        }

        $this->redirect(route('admin.news.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.news.form');
    }
}
```

- [x] **Step 6: Buat News Form view (dengan Trix)**

`resources/views/livewire/admin/news/form.blade.php`:

```blade
<div>
    <div class="mb-6">
        <a href="{{ route('admin.news.index') }}" class="text-sm hover:underline" style="color: var(--brand-700);">← Kembali</a>
        <h2 class="text-2xl font-bold text-ink-900 mt-2">{{ $news && $news->exists ? 'Edit Berita' : 'Tambah Berita' }}</h2>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl shadow-soft p-6 space-y-5 max-w-3xl">
        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Judul *</label>
            <input type="text" wire:model="title" class="chip-input" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Kategori</label>
                <select wire:model="category" class="chip-input">
                    <option value="yayasan">Yayasan</option>
                    <option value="sekolah">Sekolah</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Sekolah (opsional)</label>
                <select wire:model="school_id" class="chip-input">
                    <option value="">— Tidak terikat sekolah —</option>
                    @foreach ($this->schools as $school)
                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Ringkasan</label>
            <textarea wire:model="excerpt" rows="2" class="chip-input"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Isi Berita</label>
            <x-trix-editor model="body" id="body" placeholder="Tulis isi berita..." />
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Cover</label>
            <input type="file" wire:model="cover_image" accept="image/*" class="text-sm">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Tanggal Terbit</label>
                <input type="datetime-local" wire:model="published_at" class="chip-input">
            </div>
            <div class="flex items-end pb-2">
                <label class="flex items-center gap-2 text-sm text-ink-700">
                    <input type="checkbox" wire:model="is_published" class="rounded"> Terbitkan
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-ink-100">
            <a href="{{ route('admin.news.index') }}" class="btn-ghost">Batal</a>
            <button type="submit" class="btn-primary">Simpan</button>
        </div>
    </form>
</div>
```

- [x] **Step 7: Tambah rute**

Di `routes/web.php` grup admin:

```php
use App\Livewire\Admin\News\Index as AdminNewsIndex;
use App\Livewire\Admin\News\Form as AdminNewsForm;

Route::get('/berita', AdminNewsIndex::class)->name('news.index');
Route::get('/berita/baru', AdminNewsForm::class)->name('news.create');
Route::get('/berita/{news}/edit', AdminNewsForm::class)->name('news.edit');
```

- [x] **Step 8: Build & verifikasi**

```bash
npm run build
php artisan route:clear
```

Login admin → "Berita" → tambah berita → isi dengan Trix editor → simpan.

- [x] **Step 9: Commit**

```bash
git add -A
git commit -m "feat: admin CRUD for news with Trix editor"
```

---

## Task 3.5: CRUD Galeri

**Files:**
- Create: `app/Livewire/Admin/Gallery/Index.php`
- Create: `resources/views/livewire/admin/gallery/index.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/AdminGalleryTest.php`

(Pola lebih sederhana dari News — galeri bisa upload multiple + inline edit title/caption.)

- [x] **Step 1: Tulis test**

`tests/Feature/AdminGalleryTest.php`:

```php
<?php

use App\Models\User;

use function Pest\Laravel\{actingAs};

beforeEach(fn () => $this->admin = User::factory()->create(['is_admin' => true]));

it('shows gallery index to admin', function () {
    actingAs($this->admin)->get(route('admin.gallery.index'))->assertOk();
});
```

- [x] **Step 2: Buat Gallery Index component**

`app/Livewire/Admin/Gallery/Index.php`:

```php
<?php

namespace App\Livewire\Admin\Gallery;

use App\Models\GalleryImage;
use App\Models\School;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Kelola Galeri')]
class Index extends Component
{
    use WithFileUploads;

    public $photos = [];      // multiple upload
    public ?int $school_id = null;
    public ?string $caption = null;
    public ?int $deleteId = null;

    public function save(): void
    {
        $this->validate([
            'photos' => 'required|array|min:1',
            'photos.*' => 'image|max:4096',
            'school_id' => 'nullable|exists:schools,id',
            'caption' => 'nullable|string|max:255',
        ]);

        foreach ($this->photos as $photo) {
            GalleryImage::create([
                'image' => $photo->store('gallery', 'public'),
                'caption' => $this->caption,
                'school_id' => $this->school_id,
            ]);
        }

        $this->reset(['photos', 'caption', 'school_id']);
        flash('Foto ditambahkan.', 'success');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
    }

    public function delete(): void
    {
        GalleryImage::findOrFail($this->deleteId)?->delete();
        $this->deleteId = null;
    }

    #[Computed]
    public function schools()
    {
        return School::active()->ordered()->get(['id', 'name']);
    }

    #[Computed]
    public function images()
    {
        return GalleryImage::with('school')->ordered()->paginate(24);
    }

    public function render()
    {
        return view('livewire.admin.gallery.index');
    }
}
```

- [x] **Step 3: Buat Gallery Index view**

`resources/views/livewire/admin/gallery/index.blade.php`:

```blade
<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-ink-900">Kelola Galeri</h2>
        <p class="text-ink-500 text-sm">Upload foto kegiatan yayasan &amp; sekolah.</p>
    </div>

    {{-- Upload form --}}
    <form wire:submit="save" class="bg-white rounded-2xl shadow-soft p-6 mb-8 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Pilih Foto *</label>
                <input type="file" wire:model="photos" multiple accept="image/*" class="text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Sekolah (opsional)</label>
                <select wire:model="school_id" class="chip-input">
                    <option value="">— Galeri Yayasan —</option>
                    @foreach ($this->schools as $school)
                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Caption</label>
                <input type="text" wire:model="caption" class="chip-input">
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="btn-primary" disabled="{{ ! empty($this->photos) ? false : true }}">Upload</button>
        </div>
        <div wire:loading class="text-sm text-ink-500">Mengunggah...</div>
    </form>

    {{-- Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse ($this->images as $image)
            <div class="bg-white rounded-xl overflow-hidden shadow-soft">
                <img src="{{ $image->image_url }}" alt="{{ $image->caption ?? '' }}" class="w-full h-24 object-cover">
                <div class="p-2">
                    <p class="text-xs text-ink-600 truncate">{{ $image->caption ?? '(tanpa caption)' }}</p>
                    <button type="button" wire:click="confirmDelete({{ $image->id }})" class="text-xs text-red-600 mt-1">Hapus</button>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-ink-500 py-8">Belum ada foto.</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $this->images->links() }}</div>

    @if ($deleteId)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" wire:click="$set('deleteId', null)">
            <div class="bg-white rounded-2xl p-6 max-w-sm w-full" @click.stop>
                <h3 class="font-bold text-ink-900 mb-2">Hapus foto?</h3>
                <div class="flex gap-3 justify-end mt-4">
                    <button type="button" wire:click="$set('deleteId', null)" class="btn-ghost">Batal</button>
                    <button type="button" wire:click="delete" class="inline-flex items-center rounded-full px-5 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700">Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>
```

- [x] **Step 4: Tambah rute**

Di `routes/web.php`:

```php
use App\Livewire\Admin\Gallery\Index as AdminGalleryIndex;

Route::get('/galeri', AdminGalleryIndex::class)->name('gallery.index');
```

- [x] **Step 5: Jalankan test & verifikasi**

```bash
php artisan test --filter=AdminGalleryTest
npm run build
php artisan route:clear
```

Expected: test PASS. Login admin → "Galeri" → upload foto → tampil di grid.

- [x] **Step 6: Commit**

```bash
git add -A
git commit -m "feat: admin gallery management (multi-upload)"
```

---

## Task 3.6: Pengaturan Yayasan (SiteSetting)

**Files:**
- Create: `app/Livewire/Admin/Settings/Index.php`
- Create: `resources/views/livewire/admin/settings/index.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/AdminSettingsTest.php`

- [x] **Step 1: Tulis test**

`tests/Feature/AdminSettingsTest.php`:

```php
<?php

use App\Livewire\Admin\Settings\Index;
use App\Models\SiteSetting;
use App\Models\User;

use function Pest\Laravel\{actingAs};

beforeEach(fn () => $this->admin = User::factory()->create(['is_admin' => true]));

it('shows settings form to admin', function () {
    actingAs($this->admin)->get(route('admin.settings.index'))->assertOk();
});

it('updates site settings via livewire', function () {
    Livewire::actingAs($this->admin)->test(Index::class)
        ->set('tagline', 'Tagline Baru')
        ->call('save')
        ->assertHasNoErrors();

    expect(SiteSetting::current()->tagline)->toBe('Tagline Baru');
});
```

- [x] **Step 2: Buat Settings component**

`app/Livewire/Admin/Settings/Index.php`:

```php
<?php

namespace App\Livewire\Admin\Settings;

use App\Models\SiteSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Pengaturan Yayasan')]
class Index extends Component
{
    use WithFileUploads;

    public string $name;
    public ?string $tagline = null;
    public ?string $vision = null;
    public ?string $mission = null;
    public ?string $history = null;
    public ?string $address = null;
    public ?string $phone = null;
    public ?string $email = null;
    public ?int $established_year = null;
    public ?int $students_count = null;
    public ?string $facebook = null;
    public ?string $instagram = null;
    public ?string $youtube = null;
    public $logo = null;

    public function mount(): void
    {
        $settings = SiteSetting::current();
        $this->fill($settings->only(['name', 'tagline', 'vision', 'mission', 'history', 'address', 'phone', 'email', 'established_year', 'students_count']));
        $this->facebook = $settings->socials['facebook'] ?? null;
        $this->instagram = $settings->socials['instagram'] ?? null;
        $this->youtube = $settings->socials['youtube'] ?? null;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'history' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:32',
            'email' => 'nullable|email|max:255',
            'established_year' => 'nullable|integer|min:1900|max:'.(date('Y') + 1),
            'students_count' => 'nullable|integer|min:0',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        $socials = array_filter([
            'facebook' => $validated['facebook'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'youtube' => $validated['youtube'] ?? null,
        ]);

        if ($this->logo) {
            $validated['logo'] = $this->logo->store('yayasan', 'public');
        }

        $settings = SiteSetting::current();
        $settings->update(collect($validated)->except(['facebook', 'instagram', 'youtube'])->merge(['socials' => $socials])->toArray());

        flash('Pengaturan disimpan.', 'success');
    }

    public function render()
    {
        return view('livewire.admin.settings.index');
    }
}
```

- [x] **Step 3: Buat Settings view**

`resources/views/livewire/admin/settings/index.blade.php`:

```blade
<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-ink-900">Pengaturan Yayasan</h2>
        <p class="text-ink-500 text-sm">Identitas yayasan yang tampil di seluruh halaman publik.</p>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl shadow-soft p-6 space-y-5 max-w-3xl">
        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Nama Yayasan *</label>
            <input type="text" wire:model="name" class="chip-input" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Tagline</label>
            <input type="text" wire:model="tagline" class="chip-input">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Visi</label>
                <textarea wire:model="vision" rows="3" class="chip-input"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Misi</label>
                <textarea wire:model="mission" rows="3" class="chip-input"></textarea>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Sejarah</label>
            <textarea wire:model="history" rows="5" class="chip-input"></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Alamat</label>
                <input type="text" wire:model="address" class="chip-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Telepon</label>
                <input type="text" wire:model="phone" class="chip-input">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Email</label>
                <input type="email" wire:model="email" class="chip-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Tahun Berdiri</label>
                <input type="number" wire:model="established_year" class="chip-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Jumlah Siswa</label>
                <input type="number" wire:model="students_count" class="chip-input">
            </div>
        </div>

        <fieldset class="border border-ink-200 rounded-2xl p-4">
            <legend class="text-sm font-medium text-ink-700 px-2">Media Sosial</legend>
            <div class="space-y-3">
                <input type="url" wire:model="facebook" class="chip-input" placeholder="https://facebook.com/...">
                <input type="url" wire:model="instagram" class="chip-input" placeholder="https://instagram.com/...">
                <input type="url" wire:model="youtube" class="chip-input" placeholder="https://youtube.com/@...">
            </div>
        </fieldset>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Logo Yayasan</label>
            <input type="file" wire:model="logo" accept="image/*" class="text-sm">
        </div>

        <div class="flex justify-end pt-4 border-t border-ink-100">
            <button type="submit" class="btn-primary">Simpan Pengaturan</button>
        </div>
    </form>
</div>
```

- [x] **Step 4: Tambah rute**

Di `routes/web.php`:

```php
use App\Livewire\Admin\Settings\Index as AdminSettingsIndex;

Route::get('/pengaturan', AdminSettingsIndex::class)->name('settings.index');
```

- [x] **Step 5: Jalankan test & verifikasi**

```bash
php artisan test --filter=AdminSettingsTest
npm run build
php artisan route:clear
```

Login admin → "Pengaturan" → ubah tagline → simpan → cek halaman publik (footer) menampilkan tagline baru.

- [x] **Step 6: Commit**

```bash
git add -A
git commit -m "feat: admin site settings (yayasan identity) form"
```

---

## Task 3.7: Finalisasi — cleanup & smoke test penuh

**Files:**
- Delete: `tests/Feature/ExampleTest.php`, `tests/Unit/ExampleTest.php`, `tests/smoke-test.php`

- [x] **Step 1: Hapus tes sampel starter kit**

```bash
rm tests/Feature/ExampleTest.php tests/Unit/ExampleTest.php tests/smoke-test.php
```

- [x] **Step 2: Jalankan SELURUH tes**

```bash
php artisan test
```

Expected: semua PASS (AdminAccess, Models, PublicSchools, PublicNews, AdminSchools, AdminGallery, AdminSettings).

- [x] **Step 3: Jalankan linter (Pint)**

```bash
composer lint
```

Expected: tidak ada error (auto-fix diterapkan).

- [x] **Step 4: Smoke test manual (checklist)**

- [x] `/` beranda tampil dengan hero, stats, sekolah, berita
- [x] `/tentang` visi-misi-sejarah
- [x] `/sekolah` daftar + filter jenjang berfungsi
- [x] `/sekolah/{slug}` tab Tentang/PPDB/Galeri berfungsi
- [x] `/berita` daftar + filter + paginasi
- [x] `/berita/{slug}` artikel lengkap
- [x] `/galeri` grid + lightbox
- [x] `/ppdb` daftar per sekolah + status
- [x] Login admin → semua 5 menu berfungsi (CRUD Sekolah, Berita, Galeri, Pengaturan)
- [x] Non-admin tidak bisa akses `/admin/*`

- [x] **Step 5: Commit final**

```bash
git add -A
git commit -m "chore: remove starter kit example tests, finalize"
```

---

## Selesai

Setelah semua task di atas selesai:
- Website publik yayasan lengkap & SEO-friendly
- Admin panel CRUD untuk semua konten
- Tema hijau-emas, bahasa Indonesia
- Tes feature & unit untuk alur kritis
- Git history bersih dengan commit per task

**Login admin:** `admin@example.com` / `password`
