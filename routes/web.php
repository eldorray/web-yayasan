<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Public\About;
use App\Livewire\Public\Home;
use App\Livewire\Public\Ppdb;
use App\Livewire\Public\Gallery\Index as GalleryIndex;
use App\Livewire\Public\News\Index as NewsIndex;
use App\Livewire\Public\News\Show as NewsShow;
use App\Livewire\Public\Schools\Index as SchoolsIndex;
use App\Livewire\Public\Schools\Show as SchoolsShow;
use App\Livewire\Settings\Appearance as SettingsAppearance;
use App\Livewire\Settings\Profile as SettingsProfile;
use App\Livewire\Settings\Theme as SettingsTheme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rute publik (sebagian placeholder — akan diisi tugas berikutnya)
Route::name('public.')->group(function () {
    Route::get('/', Home::class)->name('home');
    Route::get('/tentang', About::class)->name('about');
    Route::get('/sekolah', SchoolsIndex::class)->name('schools.index');
    Route::get('/sekolah/{school:slug}', SchoolsShow::class)->name('schools.show');
    Route::get('/berita', NewsIndex::class)->name('news.index');
    Route::get('/berita/{news:slug}', NewsShow::class)->name('news.show');
    Route::get('/galeri', GalleryIndex::class)->name('gallery.index');
    Route::get('/ppdb', Ppdb::class)->name('ppdb');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', Dashboard::class)->name('dashboard');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::redirect('/', '/settings/profile');
        Route::get('/profile', SettingsProfile::class)->name('profile');
        Route::get('/appearance', SettingsAppearance::class)->name('appearance');
        Route::get('/theme', SettingsTheme::class)->name('theme');
    });

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});